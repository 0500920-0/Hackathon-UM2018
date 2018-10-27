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
      
  <nav style="background-color: #1ABC9C">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo center"><font size="4">變更密碼 :: CHANGE PW</font></a>
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
    <div class="row margin">
       <p>&nbsp;&nbsp;&nbsp;&nbsp;變更密碼時 :</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;請注意密碼必須至少有6位字元，且密碼必須為英數混合密碼。</p>
                  <div class="input-field col s12 center">
                    <input id="password" type="password" class="validate">
                    <label for="password">Password :</label>
                </div>
                <center><a class="btn waves-effect waves-light" onclick="changePW()" />Save </a></center>
                </div>

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
     <script src="js/changePW.js"></script>
       <script src="js/s_main.js"></script>
    </body>
  </html>    