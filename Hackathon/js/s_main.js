const TextField=document.querySelector("#email");

firebase.auth().onAuthStateChanged(function(user) {
  if (user) {
    emailVar = user.email;
    TextField.innerText = emailVar;

  } else {
    window.location.replace("http://home.puiching.edu.mo/hackathon14/s_login.php")
  }
});

function logout() {
	firebase.auth().signOut().then(function() {
  
}).catch(function(error) {
  alert("An ERROR Happended...");
});
}



