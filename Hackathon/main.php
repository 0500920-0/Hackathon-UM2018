<?php
require_once('curl.php');
$dateFrom = date('Y-m-d', strtotime('-168 hour'))."T".date('H:i:s', strtotime('-168 hour'));

$targetZone = "E1-E2";
$zoneCodes = array('E1-E2','E12','E32','N6','N23','E21','N24','N21','E11','N1-N2');

$khws['E1-E2'] = 0;
$khws['E12']  = 0;
$khws['E32']  = 0;
$khws['N6'] = 0;
$khws['N23']  = 0;
$khws['E21']  = 0;
$khws['N24']  = 0;
$khws['N21']  = 0;
$khws['E11']  = 0;
$khws['N1-N2'] = 0;

//now
foreach ($zoneCodes as $zoneCode){
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
    if(!empty($data['readings']['kvarh'])){
      $kvarh[$zone] = $data['readings']['kvarh'];
    }
    if(!empty($data['readings']['pf'])){
      $pf[$zone] = $data['readings']['pf'];
    }

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
}

arsort($kwhs);
$all = $lighting[$targetZone] + $HVAC[$targetZone] + $Others[$targetZone];
$lightingPer = $lighting[$targetZone]/$all*100;
$HVACPer = $HVAC[$targetZone]/$all*100;
$OthersPer = $Others[$targetZone]/$all*100;


?>


 <!DOCTYPE html>
  <html>
    <head>
      <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

      <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css2/materialize.min.css"  media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

       <title>澳大電力管理系統 :: UM POWER MANAGEMENT SYSTEM  </title>

    </head>

    <body>
       <script type="text/javascript" src="js2/materialize.js"></script>

       <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-auth.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-database.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-firestore.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-messaging.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-functions.js"></script>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
     <script src="highcharts/highchartFunction.js"></script>
     <style media="screen">
       .chart{
         height: 300px;
       }
       .chart2{
         height: 180px;
       }
       pre{
         display: none;
       }
     </style>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
        <!-- start header nav-->

  <nav style="background-color: #1ABC9C">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo center"><font size="4">總覽 :: OVERVIEW</font></a>
        <a href="#" data-target="slide-out" class="sidenav-trigger" style="display: block;"><i class="fas fa-bars"></i>
</a>
       <!-- Dropdown Trigger -->
   <a class='dropdown-trigger btn-floating btn-large halfway-fab waves-effect waves-light teal' href='#' data-target='dropdown1' style="float: right;"><i class="material-icons">more_vert</i></a>
        <ul id='dropdown1' class='dropdown-content'>
          <li>
            <a href="http://home.puiching.edu.mo/hackathon14/login.php" onclick="logout()">LOGOUT</a>
          </li>
          <li>
            <a class="waves-effect waves-light modal-trigger" href="#modal1">INFO</a> 
          </li>
 <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>關於 :: INFO</h4>
        <p>VERSION : 1.0</p>
        <p>MADE IN MACAU</p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">OK</a>
    </div>
  </div>

        </ul>
      </div>
  </nav>


    <ul id="slide-out" class="sidenav side-nav fixed ">
    <li><div class="user-view">
      <div class="background">
        <img src="data/purple.png">
      </div>
      <a href="#user"><img class="circle" src="data/user.png"></a>
      <a href="#name"><p class="white-text name"><font size="2">澳大電力管理系統 </font></p><p class="white-text name"><font size="2">UM POWER MANAGEMENT SYSTEM </font></p></a>
      <a href="#email" id="email"><span class="white-text email"></span></a>
    </div></li>
    <li><a href="main.php" class="waves-effect"><i class="material-icons">home</i>總覽 :: OVERVIEW</a></li>
    <li><a href="analyse.php" class="waves-effect"><i class="material-icons">compare</i>分析 :: ANALYSE</a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">帳號</a></li>
    <li><a class="waves-effect" href="changePW.php"><i class="material-icons">vpn_key</i>變更密碼 :: CHANGE PW</a></li>
    <li><a class="waves-effect" onclick="logout()"><i class="material-icons">directions_walk</i>登出 :: LOGOUT</a></li>
  </ul>

 <div id="main">
   <div class="row">
     <div class="col s12 m3">
       <div class="card white-1">
         <div class="card-content white-text">
           <span class="card-title">1A</span>
           <li id="categoryUsage" class="chart"></li>
         </div>

       </div>
     </div>
     <div class="col s12 m3">
       <div class="card white-1">
         <div class="card-content white-text">
           <span class="card-title">1A</span>
           <li id="pf" class="chart2"></li>
         </div>

       </div>
     </div>
     <div class="col s12 m3">
       <div class="card white-1">
         <div class="card-content white-text">
           <span class="card-title">1A</span>
            <li id="kvarh" class="chart2"></li>
         </div>

       </div>
     </div>
     <div class="col s12 m3">
       <div class="card white-1">
         <div class="card-content white-text">
           <span class="card-title">1A</span>
            <li id="nowKwh" class="chart"></li>
         </div>

       </div>
     </div>
     <div class="col s12 m12">
       <div class="card white-1">
         <div class="card-content white-text">
           <span class="card-title">1A</span>
            <li id="buildingsUsage" class="chart"></li>
         </div>

       </div>
     </div>
   </div>
 </div>





  <pre id="buildingsUsageData">
    <?php
    echo "Zone,Lighting And Socket,HVAC,Others\n";
    foreach ($kwhs as $zone => $value){
      echo $zone;
      echo ",";
      echo $lighting[$zone];
      echo ",";
      echo $HVAC[$zone];
      echo ",";
      echo $Others[$zone];
      echo "\n";
    }
     ?>
  </pre>

 <!-- <div id="analyse" style="display: none;">
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
     </div> -->
     <section id="main">

     </section>



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
<script type="text/javascript">


     Highcharts.chart('buildingsUsage', {
         chart: {
             type: 'column'
         },
         title: {
             text: '最新各地區用電量排行'
         },
         subtitle: {
           text: '<?=$update?>'
         },
         credits: {
             enabled: false
         },
         data: {
             csv: document.getElementById('buildingsUsageData').innerHTML
         },
         yAxis: {
             min: 0,
             title: {
                 text: 'kwh'
             },
             stackLabels: {
                 enabled: true,
                 style: {
                     fontWeight: 'bold',
                     color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                 }
             }
         },
         legend: {
             align: 'right',
             x: -30,
             verticalAlign: 'top',
             y: 25,
             floating: true,
             backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
             borderColor: '#CCC',
             borderWidth: 1,
             shadow: false
         },
         tooltip: {
             headerFormat: '<b>{point.x}</b><br/>',
             pointFormat: '{series.name}: {point.y} kwh<br/>Total: {point.stackTotal} kwh'
         },
         plotOptions: {
             column: {
                 stacking: 'normal',
                 dataLabels: {
                     enabled: false
                 }
             }
         }
     });

Highcharts.chart('categoryUsage', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '現時各類別用電量'
    },
    subtitle: {
      text: '<?=$update?>'
    },
    credits: {
        enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Lighting And Socket',
            y: <?=$lightingPer?>,
            sliced: true,
            selected: true
        }, {
            name: 'HVAC',
            y: <?=$HVACPer?>
        }, {
            name: 'Others',
            y: <?=$OthersPer?>
        }]
    }]
});



var gaugeOptions = {

    chart: {
        type: 'solidgauge'
    },

    title: null,

    pane: {
        center: ['50%', '85%'],
        size: '140%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#55BF3B'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.9, '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

// The speed gauge
Highcharts.chart('kvarh', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: 10000000,
        title: {
            text: 'kvarh'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'kvarh',
        data: [<?=$kvarh[$targetZone]?>],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                   '<span style="font-size:12px;color:silver">kvarh</span></div>'
        },
        tooltip: {
            valueSuffix: ' kvarh'
        }
    }]

}));

// The RPM gauge
Highcharts.chart('pf', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: -200,
        max: 200,
        title: {
            text: 'pf'
        }
    },

    series: [{
        name: 'pf',
        data: [<?=$pf[$targetZone]?>],
        dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.1f}</span><br/>' +
                   '<span style="font-size:12px;color:silver"> </span></div>'
        },
        tooltip: {
            valueSuffix: ' '
        }
    }]

}));



Highcharts.chart('nowKwh', {

    chart: {
        type: 'gauge',
        plotBackgroundColor: null,
        plotBackgroundImage: null,
        plotBorderWidth: 0,
        plotShadow: false
    },

    title: {
        text: 'kwh'
    },

    pane: {
        startAngle: -150,
        endAngle: 150,
        background: [{
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#FFF'],
                    [1, '#333']
                ]
            },
            borderWidth: 0,
            outerRadius: '109%'
        }, {
            backgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0, '#333'],
                    [1, '#FFF']
                ]
            },
            borderWidth: 1,
            outerRadius: '107%'
        }, {
            // default background
        }, {
            backgroundColor: '#DDD',
            borderWidth: 0,
            outerRadius: '105%',
            innerRadius: '103%'
        }]
    },

    // the value axis
    yAxis: {
        min: 0,
        max: 100000000,

        minorTickInterval: 'auto',
        minorTickWidth: 1,
        minorTickLength: 10,
        minorTickPosition: 'inside',
        minorTickColor: '#666',

        tickPixelInterval: 30,
        tickWidth: 2,
        tickPosition: 'inside',
        tickLength: 10,
        tickColor: '#666',
        labels: {
            step: 2,
            rotation: 'auto'
        },
        title: {
            text: 'km/h'
        },
        plotBands: [{
            from: 0,
            to: 40000000,
            color: '#55BF3B' // green
        }, {
            from: 40000001,
            to: 80000000,
            color: '#DDDF0D' // yellow
        }, {
            from: 80000001,
            to: 100000000,
            color: '#DF5353' // red
        }]
    },

    series: [{
        name: 'kwh',
        data: [<?=$kwhs[$targetZone]?>],
        tooltip: {
            valueSuffix: ' km/h'
        }
    }]
}
);
</script>
<script type="text/javascript">
  
  $(document).ready(function(){
    $('.modal').modal();
  });
          
</script>
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
