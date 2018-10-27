function alertNotice() {
  alert("Please contact ICTO for help!");
}

function login() {

    email = document.getElementById("email").value;
    const pw = document.getElementById("password").value;

	firebase.auth().signInWithEmailAndPassword(email, pw).catch(function(error) {
  // Handle Errors here.
  var errorCode = error.code;
  var errorMessage = error.message;

  alert(errorMessage);
  
  // ...
});

	
 
}

firebase.auth().onAuthStateChanged(function(user) {
  if (user) {
    window.location.replace("http://home.puiching.edu.mo/hackathon14/account.php")
  } else {
    // No user is signed in.
  }
});

firebase.auth().setPersistence(firebase.auth.Auth.Persistence.SESSION)
  .then(function() {
    // Existing and future Auth states are now persisted in the current
    // session only. Closing the window would clear any existing state even
    // if a user forgets to sign out.
    // ...
    // New sign-in will be persisted with session persistence.
    return firebase.auth().signInWithEmailAndPassword(email, password);
  })
  .catch(function(error) {
    // Handle Errors here.
    var errorCode = error.code;
    var errorMessage = error.message;
  });
