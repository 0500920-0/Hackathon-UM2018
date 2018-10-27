<meta charset="utf-8"/>
<?php

function dump ($it) {
    $json = json_encode($it);
    // echo "<pre>";
    var_dump($it);
    echo"\n";
    // exit;
}

require_once 'chatfuel-broadcast-func.php';

$db = new mysqli("localhost", "hackathon14", "pcms", "hackathon14");
mysqli_set_charset($db, "utf8");
if ($db->connect_errno) {
    $err_no = $mysqli->connect_errno;
    $error = $mysqli->connect_error;
    throw new Error("Failed to connect to MySQL: ($err_no) $error");
}

require_once 'curl.php';

$dateFrom = date('Y-m-d', strtotime('-45 minute'))."T".date('H:i:s', strtotime('-45 minute'));
$dateTo = date('Y-m-d')."T".date('H:i:s');

$url = "https://api.data.umac.mo/service/facilities/power_meter_locations/v1.0.0/all";
$data = curl($url);
if (!isset($data)) exit;
$data = $data['_embedded'];

function getZoneCode ($zone) {
    return $zone['zoneCode'];
}
function getMeterArray ($zone) {
    return $zone['meters'];
}
function getMeter ($zoneCode, $zoneMeters) {
    foreach ($zoneMeters as $meter) {
        $meterArray[] = array_merge(array('zoneCode' => $zoneCode), $meter);
    }
    return $meterArray;
}
function numInRange ($val, $from, $to) {
    return $from < $val && $val < $to;
}
function unusualVolt ($volt) {
    return !numInRange($volt, 229, 231); // (215,245)
}
function unusualFreq ($freq) {
    return !numInRange($freq, 49, 50.01); // (49,51)
}
$zoneCodeArray = array_map('getZoneCode', $data);
$zoneMeterArray = array_map('getMeterArray', $data);
$metersArray = array_map('getMeter', $zoneCodeArray, $zoneMeterArray);

foreach ($metersArray as $index => $meters) {
    foreach ($meters as $key => $meter) {
        $sql = "SELECT email, fb_id FROM user WHERE zone='$meter[zoneCode]';";
        $res = $db->query($sql);
        if ($res->num_rows === 0) break;
        $broadcastList = array();
        while ($row = $res->fetch_assoc()) {
            $broadcastList[] = $row;
        }

        $url =  "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?zone_code=$meter[zoneCode]&meter_code=$meter[code]&date_from=$dateFrom&date_to=$dateTo";
        // echo "$url\n";
        $record = curl($url);
        $record = $record['_embedded'];

        $meter['descript'] = isset($meter['descript']) ? $meter['descript'] : '';
        $meter['extraInfo'] = isset($meter['extraInfo']) ? $meter['extraInfo'] : '';
        $meter['type'] = isset($meter['type']) ? $meter['type'] : '';
        $meter['deltaKwh'] = isset($record[0]['readings']['kwh']) ? $record[0]['readings']['kwh'] - $record[1]['readings']['kwh'] : 'N/A';
        $meter['freq'] = isset($record[0]['readings']['freq']) ? $record[0]['readings']['freq'] : 50/*正常*/;
        $meter['v1'] = isset($record[0]['readings']['v1']) ? $record[0]['readings']['v1'] : 230/*正常*/;
        $meter['v2'] = isset($record[0]['readings']['v2']) ? $record[0]['readings']['v2'] : 230/*正常*/;
        $meter['v3'] = isset($record[0]['readings']['v3']) ? $record[0]['readings']['v3'] : 230/*正常*/;

        $unusualVolt = unusualVolt($meter['v1']) || unusualVolt($meter['v2']) || unusualVolt($meter['v3']);
        $unusualFreq = unusualFreq($meter['freq']);

        $json = json_encode($record);

        if ($unusualVolt || $unusualFreq) foreach ($broadcastList as $user) {
            broadcast(
                $user['fb_id'],
                $user['email'],
                'alert',
                array('alert_message' => "電錶 $meter[code]: $meter[descript] $meter[extraInfo] $meter[type]
15分鐘內消耗: $meter[deltaKwh]"
                    . ($unusualVolt ? "\n電壓異常\n三相電壓: $meter[v1], $meter[v2], $meter[v3]\n" : '')
                    . ($unusualFreq ? "\n交流電頻率異常\n頻率: $meter[freq]\n" : '')
                )
            );
            usleep(100000);
        }
        usleep(100000);
    }
    usleep(100000);
}


?>
