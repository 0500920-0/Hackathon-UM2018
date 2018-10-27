<html>
<head>
	<title>UMAC System LOGIN</title>

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      
      	<link type="text/css" rel="stylesheet" href="css2/materialize.css"  media="screen,projection"/>
		
      	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

		<meta charset="UTF-8">

      	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    	<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-app.js"></script>

		<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-auth.js"></script>
		
		<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-database.js"></script>
		
		<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-firestore.js"></script>
	
		<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-messaging.js"></script>
		
		<script src="https://www.gstatic.com/firebasejs/5.4.1/firebase-functions.js"></script>

    <!-- Lato Font -->
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

    <!-- Stylesheet -->
    <link href="materialize3/css/gallery-materialize.min.opt.css?8268030955633692047" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>
	
	<style>
	
#title{
	margin-right:50%;
	top:-12px;
	position: -25%; 
}
.wrapper{
	background-color:white;
}
	</style>
	
	<body>

		 <script type="text/javascript" src="js2/materialize.js"></script>
		
		<!-- <center><div id="title">登入頁面</div></center> -->
	<nav class="nav-extended">
      <div class="nav-background">
        <div class="ea k" style="background-image: url('data/nav.png')"></div>
      </div>
      <div class="nav-wrapper db">
		<div class="brand-logo center"><font size="4px">澳門大學電子帳單系統</font></div>
		
        <ul class="bt hide-on-med-and-down">
          <li><a href="index.php">回到主頁</a></li>
          <li><a href="login.php">管理員登入</a></li>
        </ul>
        
      </div>
    </nav>


			<div class="wrapper">

				<div class="container">

					<div class="row margin">

						<div class="input-field col s12">

							<input id="email" type="text" class="validate"> <label for="email"> Email :</label>
							
        
        				</div>
        				<div class="row margin">
        					<div class="input-field col s12 center">
          					<input id="password" type="password" class="validate">
          					<label for="password">Password :</label>
        				</div>
      					</div>
 							
					
 							
 				<center><a class="btn-floating btn-large waves-effect waves-light red" href="index.php" ><i class="material-icons">arrow_back</i></a><a class="btn-floating btn-large waves-effect waves-light yellow" href="login.php" ><i class="material-icons">swap_horiz</i></a><a class="btn-floating btn-large waves-effect waves-light green" onclick="login()" ><i class="material-icons">vpn_key</i></a></center>

 				
             <p class="margin center-align medium-small"><a class="waves-effect waves-light btn" onclick="alertNotice()">Forgot password ?</a></p>
           

			
					</div>

				</div>

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
    storageBucket: "um-student-hackathon.appspot.com",
    messagingSenderId: "588507048706"
  };
  firebase.initializeApp(config);
</script>

<script src="js/s_login.js"></script>

<script src="http://home.puiching.edu.mo/js/jquery-1.10.1.min.js"></script>

<script type="text/javascript">
	 $(document).ready(function(){
    $('.modal').modal();
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
  });
</script>

</body>

</html>