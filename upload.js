// Πάρε αντικείμενα κλάσης modal
var modal = document.getElementsByClassName("modal");
            
// κουμπί που εμφανίζει upload modal
var btn0 = document.getElementById("upload")

// κουμπί που κλείνει upload modal
var span = document.getElementsByClassName("close");
 
btn0.onclick = function() {
  modal[0].style.display = "block";
}

span[0].onclick = function() {
  modal[0].style.display = "none";
}

// Όταν ο χρήστης κλικάρει εκτός modal αυτό κλείνει
window.onclick = function(event) {
  if (event.target == modal[0]) {
    modal[0].style.display = "none";
  }
}