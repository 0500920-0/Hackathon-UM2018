<meta charset="utf-8">
<?php
require_once('curl.php');
$dateFrom = date('Y-m-d', strtotime('-24 hour'))."T".date('H:i:s', strtotime('-24 hour'));
$dateTo = date('Y-m-d')."T".date('H:i:s');
$predictDate = date('Y-m-d', strtotime('+24 hour'))."T".date('H:i:s', strtotime('+24 hour'));
$zoneCode = "E1-E2";

$url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?zone_code=".$zoneCode."&date_from=".$dateFrom."&date_to=".$dateTo;
$array = json_decode(curl($url), true);
$datas = $array["_embedded"];


// $url = "https://api.data.umac.mo/service/facilities/power_meter_locations/v1.0.0/all";
// $array = json_decode(curl($url), true);
// $locations = $array["_embedded"];
// foreach($locations as $location){
//   if($location['zoneCode']==$zoneCode){
//     foreach($location['meters'] as $locInfo){
//       if($locInfo['code']==$meterCode){
//         $descript = $locInfo['descript'];
//         $extraInfo = $locInfo['extraInfo'];
//         $type = $locInfo['type'];
//         break;
//       }
//     }
//   }
// }
var_dump($datas);
       $oldest = end($datas);
       $latest = array_shift(array_values($datas));
       $oldestReading = $oldest['readings']['kwh'];
       $latestReading = $latest['readings']['kwh'];
       $diff = $latestReading - $oldestReading;
       $predict = $latestReading + $diff;

 ?>