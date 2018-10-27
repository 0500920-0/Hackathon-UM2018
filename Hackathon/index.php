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

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>澳大電力管理系統</title>

    <!-- Lato Font -->
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
	
	      <link type="text/css" rel="stylesheet" href="css2/materialize.css"  media="screen,projection"/>

    <!-- Stylesheet -->
    <link href="materialize3/css/gallery-materialize.min.opt.css?8268030955633692047" rel="stylesheet">
    
    <script type="text/javascript" src="js2/materialize.min.js"></script>


    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
     <script src="highcharts/highchartFunction.js"></script>
    <!-- Prism -->
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
    <link href="materialize3/css/prism.css?8268030955633692047" rel="stylesheet">
</head>
<style>
#text{
	margin-left: 16%;
	margin-top: 3%;
	margin-right: 16%;
	height: 300px;
	position: absolute;
	margin-bottom:30px;
	
	
}

.container{
	background-color:#19BC9C;
}
#credit{
	padding-bottom: 15px;
}

footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
}

body {
  margin-bottom: 700px;
}
</style>
  <body style="background-color:white">
    <!-- Navbar and Header -->
    <nav class="nav-extended">
      <div class="nav-background">
        <div class="ea k" style="background-image: url('data/Mu.jpg');"></div>
      </div>
      <div class="nav-wrapper db">
        <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
		
	  <ul class="hide-on-med-and-down" style="float:right">
        <li class="k"><a href="index.php">主頁</a></li>
        <li><a href="login.php">管理員登入</a></li>
        <li><a href="s_login.php">學生登入</a></li>
      </ul>
		
  


        <div class="nav-header de">
          <h1>澳門大學電力管理系統</h1>
          <div class="ge">Hackathon team 14</div>
        </div>
      </div>
	  
<!-- <div class="categories-wrapper af lighten-1 col s6">
        <div class="categories-container" style="background-color:#19BC9C">
          <ul style="margin-left:16%" class="tabs tabs-fixed-width tab-demo z-depth-1">
            <li  class="tab"><a href="#abstract">簡介</a></li>
            <li class="tab"><a href="#realtime">實時電力數據</a></li>
            <li  class="tab"><a href="#team">制作團隊</a></li>
          </ul>
        </div>
      </div> -->
	  <div class="col s6">
        <div class="categories-container" style="background-color:#19BC9C;color:white">
          <ul class="tabs tabs-fixed-width tab-demo z-depth-1">
            <li  class="tab" style="margin-left:16%"><a href="#abstract">簡介</a></li>
            <li class="tab" style="margin-left:16%"><a href="#realtime">實時電力數據</a></li>
            <li  class="tab" style="margin-left:16%"><a href="#team">制作團隊</a></li>
          </ul>
        </div>
      </div>
	  
    </nav>
<ul id="slide-out" class="sidenav">
    
    <li><a href="index.php">主頁</a></li>
    <li><a href="login.php">管理員登入</a></li>
    <li><a href="s_login.php">學生登入</a></li>
  </ul>
<div id="text" >


<div id="abstract" class="col s12">
	<div class="col-md-6 col-sm-6 col-xs-12">
			<h3>簡介</h3>
		<div class="separetor2"></div>
		<p style="font-family: sans-serif;"><font size="3">這是一個基於澳門大學的電錶數據，而得出的電力系統模型。它除了能夠實時監察電力數據，亦可以對澳大的不同電錶進行電能監控。在緊急的時候，電管理系統亦可利用SMS與Messenger程式，向用戶傳送即時警報，保障澳大學生的安全。而電力系統模型亦設有電子賬單系統，能利用一個更為環保的方法，讓用戶能準時繳交電費，達致一箭雙鵰的效果。我們的系統還設有電流分析與預測，這樣可以有效地減低澳大學生們的整體用電量。
	</div>
</div>

<div id="realtime" class="col s12">
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
<div id="team" class="col s12">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<h3>制作團隊</h3>
		<div class="separetor2"></div>
		<p style="font-family: sans-serif;"><font size="3">由澳門培正中學學生於24小時內製作</font></p>
</div>
<img src="data/together.jpg" style="width:80%"/>
</div>
</div> 
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
  });

</script>
</body>

		<footer>
          <div style="background-color:#19BC9C;">
            	<center><a class="brand-logo" href="https://www.umac.mo/zh-hant/index.html"><img src="data/MU logo.png" style="width:20%"/></a>
				<div id="credit"><font style="color:white">Made with love by Normal Students</center></div>
		  </div>
		</footer>

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
            
    <!-- Core Javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/materialize/0.98.0/js/materialize.min.js"></script>
    
   

  
</html>