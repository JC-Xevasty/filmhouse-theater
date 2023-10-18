var loginModal = document.getElementById("loginModal");
var txtUsername = document.getElementById("txtUsername");
var txtPassword = document.getElementById("txtPassword");
var btnShowHide = document.getElementById("btnShowHide");

// Login Modal Event Listeners
loginModal.addEventListener('shown.bs.modal', function () {
    txtUsername.focus()
});

loginModal.addEventListener('hidden.bs.modal', function () {
    txtUsername.value = "";
    txtPassword.value = "";
});

document.getElementById('btnLoginHeader').onclick(function () {
    var modal = document.getElementById('loginModal');
    modal.classList.add('fade');
})

// Show and Hide Password
function password_show_hide(){
    if (txtPassword.type === "password"){
        txtPassword.type = "text";
        btnShowHide.classList.add("bi-eye-fill");
        btnShowHide.classList.remove("bi-eye-slash-fill");
    } else {
        txtPassword.type = "password";
        btnShowHide.classList.remove("bi-eye-fill");
        btnShowHide.classList.add("bi-eye-slash-fill");
    }
}

// Get and display date today
var date = new Date();
var dateNowDisplay = document.getElementById("dateNow");
const month = date.toLocaleString('default', { month: 'long' });

var dateNow = `${month} ${date.getDate()}, ${date.getFullYear()}`;
dateNowDisplay.innerText = dateNow;

// Tickets Button
var btnTickets = document.getElementsByClassName('btnTickets');
for (let i = 0; i < btnTickets.length; i++) {
    const button = btnTickets[i];
    if (document.getElementById('lblUser') === null) {
        button.setAttribute("data-bs-toggle", "modal");
        button.setAttribute("data-bs-target", "#loginModal");
    }
}

// Check Login Form
function checkForm () {
    var username = txtUsername.value;
    var password = txtPassword.value;

    if (username == "" || password == ""){
        alert("Please enter credentials to continue.");
        return false;
    }
    return true;
}