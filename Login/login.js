document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting in the default manner
    login();
});

function login() {
    var un = document.getElementById("uname").value;
    var p = document.getElementById("pass").value; // Corrected the id from "password" to "pass"
    
    if (un === 'shan' && p === '1234') { // Corrected the variable names
        alert("Successful");
        window.location.assign("admin_dashboard.php");
    } else {
        alert("Enter your valid details...");
    }
}
