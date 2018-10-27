<?php
require_once 'render-json.php';
$msg = $_GET['last_user_freeform_input'];

require_once('../curl.php');

function getMoney(){
  $date = date('y-m-d');
  $timestamp = strtotime($date);
  $newDate = strtotime('-1 month',$timestamp);
  $month = date('Y-m', $newDate);
  $dateFrom1 = $month."-01T00:00:00";
  $dateTo1 = $month."-02T00:00:00";
  $dateFrom2 = $month."-15T00:00:00";
  $dateTo2 = $month."-16T00:00:00";
  $timestamp = strtotime($dateFrom1);
  $newDate = strtotime('+1 month',$timestamp);
  $dateTo3 = date('Y-m-d', $newDate)."T00:00:00";
  $timestamp = strtotime($dateTo3);
  $newDate = strtotime('-1 day',$timestamp);
  $dateFrom3 = date('Y-m-d', $newDate)."T00:00:00";
  $zoneCode = "E1-E2";
  $meterCode = "1A01_1";


  $url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?zone_code=".$zoneCode."&meter_code=".$meterCode."&date_from=".$dateFrom1."&date_to=".$dateTo1;
  $array = json_decode(curl($url), true);
  $datas = $array["_embedded"];
  $firstDay = $datas[0]['readings']['kwh'];
  $firstDayEnd = end($datas);
  $firstDay2 = $firstDayEnd['readings']['kwh'];
  $topDay = $firstDay;
  $firstDayValue = $firstDay - $firstDay2;


  $url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?zone_code=".$zoneCode."&meter_code=".$meterCode."&date_from=".$dateFrom3."&date_to=".$dateTo3;
  $array = json_decode(curl($url), true);
  $datas = $array["_embedded"];
  $firstDay = $datas[0]['readings']['kwh'];
  $firstDayEnd = end($datas);
  $firstDay2 = $firstDayEnd['readings']['kwh'];

  $lastDay = $firstDay;
  $thirdDayValue = $firstDay - $firstDay2;

  $diff2 = $lastDay - $topDay;
  $money = $diff2*0.963;
}

kwh();
function kwh(){
  $zoneCode = "E1-E2";
  $meterCode = "1A01_1";
  $date = date('Y-m-d')."T".date('H:i:s');
  $timestamp = strtotime($date);
  $newDate = strtotime('-60 minutes',$timestamp);
  $month = date('Y-m-d', $newDate)."T".date('H:i:s', $newDate);
  //now
    $url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?meter_code=".$meterCode."&date_from=".$month;
    $array = json_decode(curl($url), true);
    $datas = $array["_embedded"];
    $oldestData = end($datas);
    $oldestDate = substr($oldestData['recordDatetime'], 0, 16);
    $oldestValue = $oldestData['readings']['kwh'];
    $latestData = array_shift(array_values($datas));
    $latestDate = substr($latestData['recordDatetime'], 0, 16);
    $latestValue = $latestData['readings']['kwh'];
    $diff = $latestValue - $oldestValue;
    return  "在".$oldestDate."至".$latestDate."期間,你總共用了".$diff."度電";
}

function category(){
  $targetZone = "E1-E2";

  //now

  $zoneCode = $targetZone;
    $url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/".$zoneCode."/records";
    $array = json_decode(curl($url), true);
    $datas = $array["_embedded"];

    $url = "https://api.data.umac.mo/service/facilities/power_meter_locations/v1.0.0/".$zoneCode;
    $array = json_decode(curl($url), true);
    $locations = $array["_embedded"][0]["meters"];
    $lighting[$zoneCode] = 0;
    $HVAC[$zoneCode] = 0;
    $Others[$zoneCode] = 0;
    foreach($datas as $data){
      $zone = $data['zoneCode'];
      $meterCode = $data['meterCode'];
      $update = $data['recordDatetime'];
      $value = $data['readings']['kwh'];

      $kwhs[$zone] += $value;
      foreach($locations as $location){
        if($location["code"] == $meterCode){
          $category = $location["primaryCategory"];
          if($category=="Lighting And Socket"){
            $lighting[$zone] += $value;
          }else if($category=="HVAC"){
            $HVAC[$zone] += $value;
          }else{
            $Others[$zone] += $value;
          }
        }
      }
    }

  $all = $lighting[$targetZone] + $HVAC[$targetZone] + $Others[$targetZone];
  $lightingPer = $lighting[$targetZone]/$all*100;
  $HVACPer = $HVAC[$targetZone]/$all*100;
  $OthersPer = $Others[$targetZone]/$all*100;

  return "你現時的Zone [其它]共用".$OthersPre."%[HVAC]共用".$HVACPer."%[Lighting And Socket]共用".$lightingPer."%";
}
if (preg_match("/\b電費\b/i", $msg) === 1) {
  getMoney();
  $message = "你上月的電費是:".$money."元\n共使用".$diff2."度電";
    renderMsg($message);
} else if(preg_match("/^Hello$/u", $msg) === 1){
   renderMsg('Hello!');
} else if(preg_match("/\b幾多度電\b/i", $msg) === 1){
   $message =  kwh();
   renderMsg($message);
} else if(preg_match("/\b部分\b/i", $msg) === 1){
   $message = category();
   renderMsg($message);
}else{
    renderMsg('唔好意思，我唔係幾明白你講咩😅');
}
