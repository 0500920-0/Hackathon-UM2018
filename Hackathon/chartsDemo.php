<meta charset="utf-8">
<?php
require_once('curl.php');
$dateFrom = date('Y-m-d', strtotime('-24 hour'))."T".date('H:i:s', strtotime('-24 hour'));
$dateTo = date('Y-m-d')."T".date('H:i:s');
$zoneCode = "E1-E2";
$meterCode = "1A01_1";

$url = "https://api.data.umac.mo/service/facilities/power_consumption/v1.0.0/all?zone_code=".$zoneCode."&meter_code=".$meterCode."&date_from=".$dateFrom."&date_to=".$dateTo;
$array = json_decode(curl($url), true);
$datas = $array["_embedded"];

$url = "https://api.data.umac.mo/service/facilities/power_meter_locations/v1.0.0/all";
$array = json_decode(curl($url), true);
$locations = $array["_embedded"];
foreach($locations as $location){
  if($location['zoneCode']==$zoneCode){
    foreach($location['meters'] as $locInfo){
      if($locInfo['code']==$meterCode){
        $descript = $locInfo['descript'];
        break;
      }
    }
  }
}

 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title></title>
     <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
     <script src="https://code.highcharts.com/highcharts.js"></script>
     <script src="https://code.highcharts.com/modules/data.js"></script>
     <script src="highcharts/highchartFunction.js"></script>
     <style media="screen">
       #kwhChart{
         height: 300px;
       }
       pre{
         display: none;
       }
     </style>
   </head>
   <body>
     <li id="kwhChart"></li>
     <pre id="kwhData">
       <?php echo "Date,千瓦時\n";
       foreach ($datas as $data){
           echo $data['recordDatetime'];
           echo ",";
           echo $data['readings']['kwh'];
           echo "\n";
         }
        ?>
     </pre>
     <script type="text/javascript">
     $(function () {

         var kwhChart = Highcharts.chart('kwhChart', {
         title: {
             text: '<?=$meterCode?> <?=$descript?> 過去24小時千瓦時變化',
        style: {
          color: "black",
          fontSize: "18px",
          fontWeight: "normal"
        }
         },
      chart: {
        type: 'spline',
        zoomType: 'x',
        alignTicks: false,
      },
      credits: {
                    text: "",
                    position: {
                      align: 'right'
                    }
                  },
         data: {
             csv: document.getElementById('kwhData').innerHTML
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
                 text: '千瓦時',
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
        tickInterval: 1
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
                else if (this.point.options.labelType == 'MIN') {
                  return $('<div/>').css({
                    'position' : 'relative',
                    'color' : 'white',
                    'border-style': 'solid',
                    'border-width': '2px',
                    'border-color': '#154360',
                    'font-size': '13px',
                    'border-radius': '2px',
                    'opacity': '0.75',
                    'backgroundColor' : this.series.color,
                    'padding' : '3px 5px',
                    'top': '32px'
                  }).text(this.y)[0].outerHTML;
                }
              }
              return null;
            }
          }
             }
         },
         series: [{
        color: '#F1C40F',
             tooltip: {
                 valueSuffix: 'kwh'
             }
      }]
     });
     dimSeries(kwhChart);
     addMaxMin(kwhChart, 'MINMAX');
   });
     </script>
   </body>
 </html>
