function changePW() {
var user = firebase.auth().currentUser;
const newPassword = document.getElementById("password").value;

user.updatePassword(newPassword).then(function() {
  alert("Success");
   window.location.replace("http://home.puiching.edu.mo/hackathon14/login.php")
}).catch(function(error) {
  alert("Failed");
});
}