<meta charset="utf-8">
<?php
require_once('../curl.php');
$dateFrom = date('Y-m-d', strtotime('-24 hour'))."T".date('H:i:s', strtotime('-24 hour'));
$dateTo = date('Y-m-d')."T".date('H:i:s');
$predictDate = date('Y-m-d', strtotime('+24 hour'))."T".date('H:i:s', strtotime('+24 hour'));
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
        $extraInfo = $locInfo['extraInfo'];
        $type = $locInfo['type'];
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
     <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
     <script src="https://code.highcharts.com/highcharts.js"></script>
     <script src="https://code.highcharts.com/modules/data.js"></script>
     <script src="../highcharts/highchartFunction.js"></script>
     <style media="screen">
       .chart{
         height: 300px;
       }
       pre{
         display: none;
       }
     </style>
   </head>
   <body>
     <li id="kwhChart" class="chart"></li>
     <pre id="kwhData">
       <?php echo "Date,千瓦時\n";
       $oldest = end($datas);
       $latest = array_shift(array_values($datas));
       $oldestReading = $oldest['readings']['kwh'];
       $latestReading = $latest['readings']['kwh'];
       $diff = $latestReading - $oldestReading;
       $predict = $latestReading + $diff;
       echo $predictDate;
       echo ",";
       echo $predict;
       echo "\n";
       foreach ($datas as $data){
           echo $data['recordDatetime'];
           echo ",";
           echo $data['readings']['kwh'];
           echo "\n";
         }
        ?>
     </pre>
     <?php if($type=="來電櫃"||$type=="N22-CCP(11kV)"):?>
       <li id="kvarhChart" class="chart"></li>
       <pre id="kvarhData">
         <?php echo "Date,無功電度\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['kvarh'];
             echo "\n";
           }
          ?>
       </pre>
       <li id="pfChart" class="chart"></li>
       <pre id="pfData">
         <?php echo "Date,功率因數\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['pf'];
             echo "\n";
           }
          ?>
       </pre>
       <li id="freqChart" class="chart"></li>
       <pre id="freqData">
         <?php echo "Date,頻率\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['freq'];
             echo "\n";
           }
          ?>
       </pre>
       <li id="voltageChart" class="chart"></li>
       <pre id="voltageData">
         <?php echo "Date,電壓1,電壓2,電壓3\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['v1'];
             echo ",";
             echo $data['readings']['v2'];
             echo ",";
             echo $data['readings']['v3'];
             echo "\n";
           }
          ?>
       </pre>
     <?php endif;?>
     <?php if($type=="來電櫃"):?>
       <li id="thd_iChart" class="chart"></li>
       <pre id="thd_iData">
         <?php echo "Date,三相電壓1,三相電壓2,三相電壓3\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['thd_i1'];
             echo ",";
             echo $data['readings']['thd_i2'];
             echo ",";
             echo $data['readings']['thd_i3'];
             echo "\n";
           }
          ?>
       </pre>
       <li id="lineCurrentChart" class="chart"></li>
       <pre id="lineCurrentData">
         <?php echo "Date,電流1,電流2,電流3\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['i1'];
             echo ",";
             echo $data['readings']['i2'];
             echo ",";
             echo $data['readings']['i3'];
             echo "\n";
           }
          ?>
       </pre>
     <?php elseif($type=="電容櫃"||$type=="N22-CCP(11kV)"):?>
       <li id="lineCurrentChart" class="chart"></li>
       <pre id="lineCurrentData">
         <?php echo "Date,電流1,電流2,電流3\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['i1'];
             echo ",";
             echo $data['readings']['i2'];
             echo ",";
             echo $data['readings']['i3'];
             echo "\n";
           }
          ?>
       </pre>
     <?php endif;?>
     <script type="text/javascript">
     $(function () {
         var kwhChart = Highcharts.chart('kwhChart', {
         title: {
             text: '<?=$meterCode?> <?=$descript?> 過去24小時千瓦時變化及未來24小時趨勢',
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
        // color: '#F1C40F',
        // tooltip: {
        //    valueSuffix: 'kwh'
        // },
        zoneAxis: 'y',
        zones: [{
            value: 5500000,
        }, {
          value: 5700000,
          dashStyle: 'dot'
        }]
      }]
     });
     // dimSeries(kwhChart);
     // addMaxMin(kwhChart, 'MINMAX');

     var kvarhChart = Highcharts.chart('kvarhChart', {
     title: {
         text: '<?=$meterCode?> <?=$descript?> 過去24小時無功電度變化及未來24小時趨勢',
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
         csv: document.getElementById('kvarhData').innerHTML
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
             text: 'kVarh',
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
    color: 'black',
         tooltip: {
             valueSuffix: 'kVarh'
         }
    }]
    });
    // dimSeries(kvarhChart);
    // addMaxMin(kvarhChart, 'MINMAX');

    var pfChart = Highcharts.chart('pfChart', {
    title: {
        text: '<?=$meterCode?> <?=$descript?> 過去24小時功率因數變化',
   style: {
     color: "black",
     fontSize: "18px",
     fontWeight: "normal"
   }
    },
 chart: {
   type: 'column',
   // zoomType: 'x',
   // alignTicks: false,
 },
 credits: {
               text: "",
               position: {
                 align: 'right'
               }
             },
    data: {
        csv: document.getElementById('pfData').innerHTML
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
            text: '功率因數',
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
     lineWidth: 1,
     states: {
        hover: {
            lineWidth: 1
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
        tooltip: {
            valueSuffix: ''
        },
        pointWidth: 10
 }]
});
// dimSeries(pfChart);
// addMaxMin(pfChart, 'MINMAX');

var freqChart = Highcharts.chart('freqChart', {
title: {
    text: '<?=$meterCode?> <?=$descript?> 過去24小時頻率變化',
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
    csv: document.getElementById('freqData').innerHTML
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
        text: '頻率',
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
color: '#3498DB',
    tooltip: {
        valueSuffix: 'Hz'
    }
}]
});
// dimSeries(freqChart);
// addMaxMin(freqChart, 'MINMAX');

var lineCurrentChart = Highcharts.chart('lineCurrentChart', {
title: {
    text: '<?=$meterCode?> <?=$descript?> 過去24小時電流變化',
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
    csv: document.getElementById('lineCurrentData').innerHTML
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
        text: '電流',
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
color: '#4D5656',
    tooltip: {
        valueSuffix: 'A'
    }
},{
color: '#839192',
    tooltip: {
        valueSuffix: 'A'
    }
},{
color: '#1B2631',
    tooltip: {
        valueSuffix: 'A'
    }
}]
});
// dimSeries(lineCurrentChart);
// addMaxMin(lineCurrentChart, 'MINMAX');

var voltageChart = Highcharts.chart('voltageChart', {
title: {
    text: '<?=$meterCode?> <?=$descript?> 過去24小時電壓變化',
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
    csv: document.getElementById('voltageData').innerHTML
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
        text: '電壓',
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
series: []
});
// dimSeries(voltageChart);
// addMaxMin(voltageChart, 'MINMAX');

var thd_iChart = Highcharts.chart('thd_iChart', {
title: {
    text: '<?=$meterCode?> <?=$descript?> 過去24小時三相電流變化',
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
    csv: document.getElementById('thd_iData').innerHTML
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
        text: '三相電流',
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
        valueSuffix: 'A'
    }
}]
});
// dimSeries(thd_iChart);
// addMaxMin(thd_iChart, 'MINMAX');

   });
     </script>
   </body>
 </html>
