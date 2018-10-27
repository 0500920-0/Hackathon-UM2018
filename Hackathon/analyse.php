<?php
require_once('curl.php');
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
      <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

      <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css2/materialize.min.css"  media="screen,projection"/>
      <title>澳大電力管理系統 :: UM POWER MANAGEMENT SYSTEM  </title>
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    </head>

    <body>
       <script type="text/javascript" src="js2/materialize.js"></script>

       <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-auth.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-database.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-firestore.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-messaging.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-functions.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
     <script src="https://code.highcharts.com/modules/data.js"></script>
     <script src="highcharts/highchartFunction.js"></script>
     <style media="screen">
       .chart{
         height: 300px;
       }
       pre{
         display: none;
       }
     </style>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
        <!-- start header nav-->

  <nav style="background-color: #1ABC9C" >
    <div class="nav-wrapper">
     <a href="#" class="brand-logo center"><font size="5">分析 :: ANALYSE</font></a>

        <a href="#" data-target="slide-out" class="sidenav-trigger" style="display: block;"><i class="fas fa-bars"></i>
</a>



       <!-- Dropdown Trigger -->
   <a class='dropdown-trigger btn-floating btn-large halfway-fab waves-effect waves-light teal' href='#' data-target='dropdown1' style="float: right;"><i class="material-icons">more_vert</i></a>
        <ul id='dropdown1' class='dropdown-content'>
          <li>
            <a href="http://home.puiching.edu.mo/hackathon14/login.php" onclick="logout()">LOGOUT</a>
          </li>


        </ul>

    </div>
  </nav>


    <ul id="slide-out" class="sidenav side-nav fixed ">
    <li><div class="user-view">
      <div class="background">
        <img src="data/purple.png">
      </div>
      <a href="#user"><img class="circle" src="data/user.png"></a>
      <a href="#name"><p class="white-text name"><font size="2">澳大電力管理系統 :: UM POWER MANAGEMENT SYSTEM  </font></p></a>
      <a href="#email" id="email"><span class="white-text email">jdandturk@gmail.com</span></a>
    </div></li>
    <li><a href="main.php" class="waves-effect"><i class="material-icons">home</i>總覽 :: OVERVIEW</a></li>
    <li><a href="analyse.php" class="waves-effect"><i class="material-icons">compare</i>分析 :: ANALYSE</a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader" class="waves-effect">帳號</a></li>
    <li><a class="waves-effect" class="waves-effect" href="changePW.php">變更密碼 :: CHANGE PW</a></li>
    <li><a class="waves-effect" class="waves-effect" onclick="logout()">登出 :: LOGOUT</a></li>
  </ul>

  <div id="analyse">

     <div class="row">
    <div class="col s12 m6">
      <div class="card white-1">
        <div class="card-content white-text">
          <span class="card-title">1A</span>
          <li id="kwhChart" class="chart"></li>
        </div>

      </div>
    </div>

    <div class="col s12 m6">
      <div class="card white-1">
        <div class="card-content white-text">
          <span class="card-title">1B</span>
         <li id="pfChart" class="chart"></li>
        </div>

      </div>
    </div>
    <div class="col s12 m6">
      <div class="card white-1">
        <div class="card-content white-text">
          <span class="card-title">1C</span>
         <li id="kvarhChart" class="chart"></li>
        </div>

      </div>
    </div>
    <div class="col s12 m6">
      <div class="card white-1">
        <div class="card-content white-text">
          <span class="card-title">1D</span>
          <li id="freqChart" class="chart"></li>
        </div>

      </div>
    </div>
    <div class="col s12 m6">
      <div class="card white-1">
        <div class="card-content white-text">
          <span class="card-title">1E</span>
          <li id="voltageChart" class="chart"></li>
        </div>

      </div>
    </div>
    <div class="col s12 m6">
      <div class="card white-1">
        <div class="card-content white-text">
          <span class="card-title">1F</span>
           <li id="lineCurrentChart" class="chart"></li>
        </div>

      </div>
    </div>
    <div class="col s12 m6">
      <div class="card white-1">
        <div class="card-content white-text">
          <span class="card-title">1G</span>
           <li id="thd_iChart" class="chart"></li>
        </div>

      </div>
    </div>
  </div>
     </div>

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

       <pre id="kvarhData">
         <?php echo "Date,無功電度\n";
         $oldest = end($datas);
         $latest = array_shift(array_values($datas));
         $oldestReading = $oldest['readings']['kvarh'];
         $latestReading = $latest['readings']['kvarh'];
         $diff = $latestReading - $oldestReading;
         $predict = $latestReading + $diff;
         echo $predictDate;
         echo ",";
         echo $predict;
         echo "\n";
         foreach ($datas as $data){
             echo $data['recordDatetime'];
             echo ",";
             echo $data['readings']['kvarh'];
             echo "\n";
           }
          ?>
       </pre>

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
        color: '#F1C40F',
             tooltip: {
                 valueSuffix: 'kwh'
             }
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


  </div>

      <!-- end header nav-->
      <!--JavaScript at end of body for optimized loading-->
     <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems);
  });
     </script>
     <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
  });
     </script>
     <script src="https://www.gstatic.com/firebasejs/5.5.6/firebase.js"></script>

    <script>
  // Initialize Firebase
      var config = {
        apiKey: "AIzaSyBvdvpCpWRsqtMZsFoHgZKqt_nKScR9-e4",
        authDomain: "um-hackathon.firebaseapp.com",
    databaseURL: "https://um-hackathon.firebaseio.com",
    projectId: "um-hackathon",
    storageBucket: "um-hackathon.appspot.com",
    messagingSenderId: "1050711158426"
  };
  firebase.initializeApp(config);
</script>
     <script src="js/index.js"></script>
    </body>
  </html>
