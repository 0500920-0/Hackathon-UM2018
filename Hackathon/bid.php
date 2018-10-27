
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="http://home.puiching.edu.mo/~pcama/nowweatherpage/highchartFunction.js"></script>
    <style>
        #post-443, #execphp-23{
          box-shadow: none;
          background: none;
          border: none;
          margin-bottom: 0;
        }
        #weatherData_uvIndex > ul{
          margin: 0;
          padding: 0;
        }
        .mainTitleLink{
          color: white !important;
          text-decoration: none;
        }
        .mainTitleLink:hover > .mainTitle{
          background: #26A69A;
          color: white;
        }
        .mainTitle{
          border-radius: 3px;
          font-size: 2em;
          background: #009688;
          font-weight: 500;
          padding: 10px 20px;
          margin-bottom: .2em;
          transition: all .3s ease-out;
        }
        .contaniner{
          display: flex;
          flex-wrap: wrap;
          flex-direction: row;
        }
        .card{
          margin: 0 0 2rem;
          margin-bottom: .5em;
          background: white;
          border: 1px solid rgba(0,0,0,0.2);
          border-top: none;
          box-shadow: inset 0 2px #1177aa;
        }
        .cardTitle{
          font-size: 1.3em;
          padding: .2em .6em;
          border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        #searchData{
          width: 100%;
        }
        .selectRemark{
          color: grey;
          text-align: center;
          font-weight: bold;
          margin: 70px 0 !important;
        }
        .removeSearchStationDataChart{
          margin: 10px auto !important;
          color: black;
          width: 83px;
          padding: 5px 6px;
          border: solid 1px #1177aa;
          border-radius: 3px;
          cursor: pointer;
          text-align: center;
        }
        .removeSearchStationDataChart:hover{
          background: #E5E7E9;
        }
        #data{
          display: none;
        }
        /* searchData */
        fieldset{
          width: 100%;
          border: none;
        }
        input[type=date]{
          margin-right: 10px;
        }
        input[type=submit]{
          margin-left: 10px;
        }
        select{
          margin-left:5px;
        }
        .tableTitle{
          background: #2E86C1;
          padding: 10px;
          color: white;
          font-size: 1.2em;
          margin-top: 5px;
        }
      table{
          width: 100%;
          font-size: .9em;
          background: #EBF5FB;
          border: 2px solid #2E86C1;
          border-spacing: 0;
          table-layout: auto;
        }
        table th{
          padding: 5px;
          background: #FFEE58;
          text-align: center;
          font-weight: bold;
          border: 1px solid #ddd !important;
        }
        table td{
            text-align: center;
            border: 1px solid #ddd !important;
            padding: 0;
          }
        table tr td:first-child{
            background: #FFA726;
          }
    </style>
  </head>
  <body>
    <div class="contaniner">
      <div class="card" id="searchData">
        <div class="cardTitle">
          你的賬單過去資料查詢
        </div>
        <div class="cardContent">
            <form action="searchData.php" method="post" class="ajax">
              <fieldset>
                日期：<input type="month" name="date" id="date" min="2018-01" max=<?=date("Y-m",strtotime("-1 months"))?> value=<?=date("Y-m",strtotime("-1 months"))?>>
                <input type="submit" id="submit">
              </fieldset>
            </form>
          <div id="form-message">
            <p class="selectRemark">
              請選擇
            </p>

          </div>
        </div>
      </div>

    </div>

    <script type="text/javascript">
    $("body").delegate(".removeSearchStationDataChart", "click", function(){
          $("#form-message").html("<p class='selectRemark'>請選擇</p>");
    });


  $("form").submit(function(event){
    event.preventDefault();
    var date = $("#date").val();
    var station = $("#station").val();
    var url = 'http://home.puiching.edu.mo/hackathon14/searchdata.php?date=' + date;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $("#form-message").html(this.responseText);
            $("#form-message").append("<p class='removeSearchStationDataChart'>還原圖表</p>");

        }
    };
    xmlhttp.open("GET",url ,true);
    xmlhttp.send();


  });

    </script>
  </body>
</html>
