<html>
<title>澳大用戶電子賬單 :: eBill</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link type="text/css" rel="stylesheet" href="css2/account.css"  media="screen,projection"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
 <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-app.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-auth.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-database.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-firestore.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-messaging.js"></script>

    <script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-functions.js"></script>
    <!-- Lato Font -->
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

    <link href="css2/materialize.css" rel="stylesheet">


    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="http://home.puiching.edu.mo/~pcama/nowweatherpage/highchartFunction.js"></script>
    <!-- Prism -->
    <!-- <link href="materialize3/css/prism.css?8268030955633692047" rel="stylesheet"> -->
	<style media="screen">
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
<body>
<script src="js2/materialize.js"></script>

  <nav style="background-color: #1ABC9C">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo center"><font size="4">查詢 :: CHECKING</font></a>
        <a href="#" data-target="slide-out" class="sidenav-trigger" style="display: block;"><i class="fas fa-bars"></i>
</a>
       <a class='dropdown-trigger btn-floating btn-large halfway-fab waves-effect waves-light teal' href='#' data-target='dropdown1' style="float: right;"><i class="material-icons">more_vert</i></a>
        <ul id='dropdown1' class='dropdown-content'>
          <li>
            <a href="http://home.puiching.edu.mo/hackathon14/s_login.php" onclick="logout()">LOGOUT</a>
               <a class="waves-effect waves-light modal-trigger" href="#modal1">INFO</a>
          </li>


        </ul>
      </nav>
 <div id="modal1" class="modal">
        <div class="modal-content">
        <h4>關於 :: INFO</h4>
        <p>VERSION : 1.0</p>
        <p>MADE IN MACAU</p>
        </div>
        <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">OK</a>
        </div>
        </div>
  <ul id="slide-out" class="sidenav">

    <li><div class="user-view">
      <div class="background">
        <img src="data/blue.png" style="width:100%">
      </div>
      <a href="#user"><img class="circle" src="data/test.png" ></a>
      <a href="#name"><span class="black-text name">User</span></a>
      <a href="#email" id="email"><span class="black-text email">testing@umac.mo</span></a>
    </div></li>
	<ul>
    <li><a href="account.php" class="waves-effect"><i class="material-icons">home</i>查詢 :: CHECKING</a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">帳號</a></li>
    <li><a class="waves-effect" href="s_changePW.php">變更密碼 :: CHANGE PW</a></li>
    <li><a class="waves-effect" onclick="logout()">登出 :: LOGOUT</a></li>
	</ul>
  </ul>
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


  <script src="https://www.gstatic.com/firebasejs/5.5.6/firebase.js"></script>
  <script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyC8KSM4PBuhryAPwgaelCIRBL2qJk0FZMc",
    authDomain: "um-student-hackathon.firebaseapp.com",
    databaseURL: "https://um-student-hackathon.firebaseio.com",
    projectId: "um-student-hackathon",
    storageBucket: "",
    messagingSenderId: "588507048706"
  };
  firebase.initializeApp(config);
</script>
<script type="text/javascript">
  
  $(document).ready(function(){
    $('.modal').modal();
  });
</script>
</body>
<script src="js/s_main.js"></script>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
  });



document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems);
  });

</script>

</html>
