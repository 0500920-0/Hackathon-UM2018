<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="../highcharts/highchartFunction.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="http://home.puiching.edu.mo/~pcama/nowweatherpage/highchartFunction.js"></script>

    <style media="screen">
      li{
        list-style: none;
      }
    </style>
  </head>
  <body>
    <div id="desTitle">

    </div>
    <div id="responseCharts">
    </div>
    <script type="text/javascript">
          document.getElementById("desTitle").innerHTML = "E1-E2";
          xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("responseCharts").innerHTML = this.responseText;
              }
          };
          xmlhttp.open("GET","getAnalytics.php", true);
          xmlhttp.send();

    // $(".tabs").delegate( ".station", "click", function() {
    //   var station = $(this).data('station');
    //   var type = $(this).data('type');
    //   var url = 'http://home.puiching.edu.mo/~pcama/nowweatherpage/stationWeatherChart/' + type + '.php?stationId=' + station;
    //   xmlhttp = new XMLHttpRequest();
    //   xmlhttp.onreadystatechange = function() {
    //       if (this.readyState == 4 && this.status == 200) {
    //           $("#stationData").removeClass("invisible");
    //           $("#stationDataContent").html(this.responseText);
    //           $("#stationDataContent").append("<p class='removeStationDataChart'>還原圖表</p>");
    //       }
    //   };
    //   xmlhttp.open("GET",url ,true);
    //   xmlhttp.send();
    // });
    </script>
  </body>
</html>
