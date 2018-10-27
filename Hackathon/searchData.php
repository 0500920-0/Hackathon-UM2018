<?php
require_once('curl.php');
$month = $_GET['date'];
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

$url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?zone_code=".$zoneCode."&meter_code=".$meterCode."&date_from=".$dateFrom2."&date_to=".$dateTo2;
$array = json_decode(curl($url), true);
$datas = $array["_embedded"];
$firstDay = $datas[0]['readings']['kwh'];
$firstDayEnd = end($datas);
$firstDay2 = $firstDayEnd['readings']['kwh'];
$middleDay = $firstDay2;
$secondDayValue = $firstDay - $firstDay2;

$url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?zone_code=".$zoneCode."&meter_code=".$meterCode."&date_from=".$dateFrom3."&date_to=".$dateTo3;
$array = json_decode(curl($url), true);
$datas = $array["_embedded"];
$firstDay = $datas[0]['readings']['kwh'];
$firstDayEnd = end($datas);
$firstDay2 = $firstDayEnd['readings']['kwh'];

$lastDay = $firstDay;
$thirdDayValue = $firstDay - $firstDay2;

$diff1 = $middleDay - $topDay;
$diff2 = $lastDay - $topDay;
$money = $diff2*0.963;

 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
   </head>
   <body>
     <div id="searchHighcharts" class="chart"></div>
     <pre id="data">
       <?php echo "Date,千瓦時\n";
       $oldest = end($datas);
       $latest = array_shift(array_values($datas));
       $oldestReading = $oldest['readings']['kwh'];
       $latestReading = $latest['readings']['kwh'];
           echo $dateFrom1;
           echo ",0";
           echo "\n";
           echo $dateFrom2;
           echo ",";
           echo $diff1;
           echo "\n";
           echo $dateTo3;
           echo ",";
           echo $diff2;
           echo "\n";
        ?>
     </pre>
     <div class="card">
       你的用電量是:<?=$diff2?><br>
       你的電費是:<?=$money?>
     </div>
     <div class="tableTitle">
       記錄表格
     </div>
     <table>
       <tr>
         <th>日期時間</th>
         <th>kwh</th>
       </tr>
       <?php
         echo "<tr>";
         echo "<td>".substr($dateFrom1, 0, -3)."</td>";
         echo "<td>0</td>";
         echo "<tr>";
         echo "<td>".substr($dateFrom2, 0, -3)."</td>";
         echo "<td>".$diff1."</td>";
         echo "<tr>";
         echo "<td>".substr($dateFrom3, 0, -3)."</td>";
         echo "<td>".$diff2."</td>";
       ?>
       <tr>

       </tr>
     </table>
     <script type="text/javascript">
     $(function () {
         var searchChart = Highcharts.chart('searchHighcharts', {
         title: {
             text: '<?=$month?> 用電量變化',
        style: {
          color: "black",
          fontSize: "18px",
          fontWeight: "normal"
        }
         },
      chart: {
        type: 'spline',
        zoomType: 'xy',
        alignTicks: false,
      },
      credits: {
                    text: " ",
                    href: "",
                    position: {
                      align: 'right'
                    }
                  },
         data: {
             csv: document.getElementById('data').innerHTML
         },
      xAxis: [{
        crosshair: true,
        type: 'datetime',
        plotLines: [{
          value: Date.UTC(today_y, today_m, today_d, 0, 0),
          color: "#717D7E",
          dashStyle: 'shortdash',
          width: 1
        },{
          value: Date.UTC(yesterday_y, yesterday_m, yesterday_d, 0, 0),
          color: "#717D7E",
          dashStyle: 'shortdash',
          width: 1
        }],
        labels:{
          style: {
            color: "black",
            fontSize: "12px",
          }
        },
        dateTimeLabelFormats : {
          day: '%m/%d'
        }
         }],
      yAxis: [{
             title: {
                 text: '紫外線指數',
          style: {
            color: 'black',
            fontSize: "14px",
          }
             },
        labels: {
          style: {
            color: 'black',
            fontSize: "12px",
          }
        },
        crosshair: true,
        tickInterval: 1,
         }],
      tooltip: {
        shared: true,
        headerFormat: '<span style="font-size: 12px; color: #000964;">{point.key:%Y/%m/%d %H:%M}</span><br>',
        pointFormat: '<span style="font-size: 13px;font-weight: bold; color: {series.color};">{series.name}: {point.y}<br>'
      },
         plotOptions: {
             series: {
                 marker: {
                     enabled: false
                 },
          lineWidth: 2,
          states: {
             hover: {
                 lineWidth: 2
             }
         },
          step: 'center',
          dataLabels: {
            enabled: true,
            allowOverlap: true,
            useHTML: true,
                     y: 5,
            formatter: function () {
              if (this.point.options.showLabel) {
                if (this.point.options.labelType == 'MAX') {
                  return $('<div/>').css({
                    'position' : 'relative',
                    'color' : 'white',
                    'border-style': 'solid',
                    'border-width': '2px',
                    'border-color': '#CB4335',
                    'font-size': '13px',
                    'border-radius': '2px',
                    'opacity': '0.75',
                    'backgroundColor' : this.series.color,
                    'padding' : '3px 5px',
                    'top': '-7px'
                  }).text(this.y)[0].outerHTML;
                }
              }
              return null;
            }
          }
             }
         },
         series: [{
           color: '#f83',
           pointWidth: 20,
             tooltip: {
                 valueSuffix: ''
             }
      }]
     });
     dimSeries(searchChart);
     addMaxMin(searchChart, 'MINMAX');
   });
     </script>
   </body>
 </html>
