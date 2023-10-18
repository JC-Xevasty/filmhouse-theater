// Dashboard Navigation Links
document.getElementById("btnProfile").addEventListener("click", function () {
    open("dashboard.php", "_self");
});

document.getElementById("btnMovies").addEventListener("click", function () {
    open("dashboard-movies.php", "_self");
});

document.getElementById("btnSchedules").addEventListener("click", function () {
    open("dashboard-schedules.php", "_self");
});

document.getElementById("btnAccounts").addEventListener("click", function () {
    open("dashboard-accounts.php", "_self");
});

document.getElementById("btnTransactions").addEventListener("click", function () {
    open("dashboard-transactions.php", "_self");
});

// Input Listener for Number inputs
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode != 45 && (charCode < 48 || charCode > 57))) || charCode == 45)
        return false;
    return true;
}

function isContactNo(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode != 45 && (charCode < 48 || charCode > 57)))
        return false;
    return true;
}

function isPrice(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && ((charCode == 45 || charCode == 43) && (charCode < 48 || charCode > 57)))
        return false;
    return true;
}

// Editing Profile Information
function editInfo() {
    var inputFields = document.querySelectorAll("input");

    for (let i = 0; i < inputFields.length; i++) {
        const field = inputFields[i];
        field.removeAttribute("disabled");
    }
    document.getElementById("btnSave").removeAttribute("disabled");
    document.getElementById("btnShowHide").setAttribute("onclick", "password_show_hide()");
    document.getElementById("acctError").classList.add("d-none");

}

// Show and Hide Password
function password_show_hide() {
    if (txtPassword.type === "password") {
        txtPassword.type = "text";
        btnShowHide.classList.add("bi-eye-fill");
        btnShowHide.classList.remove("bi-eye-slash-fill");
    } else {
        txtPassword.type = "password";
        btnShowHide.classList.remove("bi-eye-fill");
        btnShowHide.classList.add("bi-eye-slash-fill");
    }
}

// Checkbox Handler used in tables
var num_of_rows = document.querySelectorAll('.rowCheck').length;
var tableRows = document.getElementsByClassName("rowCheck");
var selectAllChk = document.getElementById("chkselectAll");
for (let i = 0; i < tableRows.length; i++) {

    const row = tableRows[i];

    row.addEventListener("click", function () {
        const fullRow = row.closest("tr");
        if (row.checked == true) {
            fullRow.classList.add("table-active");
            var num_of_selected = document.querySelectorAll('.rowCheck:checked').length;
            if (num_of_rows == num_of_selected) {
                selectAllChk.checked = true;
            }
        } else {
            fullRow.classList.remove("table-active");
            if (selectAllChk.checked == true) {
                selectAllChk.checked = false;
            }
        }
    });

}

// Select All Checkbox for tables
function selectAll() {
    document.querySelectorAll("td input").forEach(row => {
        if (chkselectAll.checked == true) {
            row.checked = true;
            row.closest("tr").classList.add("table-active");
        } else {
            row.checked = false;
            row.closest("tr").classList.remove("table-active");
        }
    });
}

function selectAllTransaction() {
    $(".label-movie").text("");
    $(".label-room").text("");
    $(".label-time").text("");
    $(".label-ticket").text("");
    $(".label-seat").text("");
    $(".label-total").text("");
    $(".label-payment").text("");
    document.querySelectorAll("td input").forEach(row => {
        if (chkselectAll.checked == true) {
            row.checked = true;
            row.closest("tr").classList.add("table-active");
        } else {
            row.checked = false;
            row.closest("tr").classList.remove("table-active");
        }
    });
}

// CRUD Buttons in the Dashboard
function addAccount() {
    open("create-account.php", "_self");
}

function addMovie() {
    open("manage-movies.php", "_self");
}

function cancelAddMovie() {
    open("dashboard-movies.php", "_self");
}

function cancelEditMovie() {
    var val = confirm("Any changes made will not be saved. Do you want to go back?");
    if (val) {
        open("dashboard-movies.php", "_self");
    }
}

function cancelEditRoom() {
    var val = confirm("Any changes made will not be saved. Do you want to go back?");
    if (val) {
        open("dashboard-schedules.php", "_self");
    }
}

function cancelAddAccount() {
    open("dashboard-accounts.php", "_self");
}

// Load and Display Images for movie posters
var loadPoster = function (event) {
    var imgPoster = document.getElementById('imgPoster');
    if (document.getElementById('posterInput').files.length === 0) {
        imgPoster.src = "img/no_poster.png";
    } else {
        imgPoster.src = URL.createObjectURL(event.target.files[0]);
    }
};


// Checkbox Listener when editing and deleting data
var submitButton;
function buttonClicked(button) {
    submitButton = button;
}

function checkMovieSelect(form) {
    var num_of_movies_checked = document.querySelectorAll('.rowCheck:checked').length;

    if (submitButton.value == "edit") {
        if (num_of_movies_checked <= 0) {
            alert("Please select a movie to be edited.");
            return false;
        } else if (num_of_movies_checked > 1) {
            alert("Select one movie only.");
            return false;
        }
        return true;
    } else if (submitButton.value == "delete") {
        if (num_of_movies_checked <= 0) {
            alert("Please select a movie to be deleted.");
            return false;
        }
        return confirm("This action can't be undone. Do you really want to delete the movie/s selected?");
    }
}

function checkScheduleSelect(form) {
    var num_of_movies_checked = document.querySelectorAll('.rowCheck:checked').length;

    if (submitButton.value == "edit") {
        if (num_of_movies_checked <= 0) {
            alert("Please select a room to be viewed.");
            return false;
        } else if (num_of_movies_checked > 1) {
            alert("Select one room only.");
            return false;
        }
        return true;
    } else if (submitButton.value == "delete") {
        if (num_of_movies_checked <= 0) {
            alert("Please select a movie to be deleted.");
            return false;
        }
        return confirm("This action can't be undone. Do you really want to delete the movie/s selected?");
    }
}

function checkAccountSelect() {
    var num_of_accts = document.querySelectorAll('.rowCheck').length;
    var num_of_accts_checked = document.querySelectorAll('.rowCheck:checked').length;

    if (num_of_accts_checked <= 0) {
        alert("Please select an account to be deleted.");
        return false;
    } else if (num_of_accts == num_of_accts_checked) {
        alert("You can't delete all accounts.");
        return false;
    }
    return confirm("This action can't be undone. Do you really want to delete the account/s selected?");

}

function checkTransactionSelect() {
    var num_of_transactions_checked = document.querySelectorAll('.rowCheckThis:checked').length;

    if (num_of_transactions_checked <= 0) {
        alert("Please select a transaction to be deleted.");
        return false;
    }
    return confirm("This action can't be undone. Do you really want to delete the transaction/s selected?");

}

// Select table row by clicking the row
$(".table-row").click(function() {
    if (($(this).closest("tr").find(".rowCheckThis").is(':checked'))) {
        $(this).closest("tr").find(".rowCheckThis").prop('checked', false);
        $(this).closest("tr").removeClass('table-active');
        $('.label-movie').text("");
        $('.label-room').text("");
        $('.label-time').text("");
        $('.label-ticket').text("");
        $('.label-seat').text("");
        $('.label-total').text("");
        $('.label-payment').text("");
        $('#chkselectAll').prop('checked', false);
    } else {
        $('.table-row').each(function() {
            $(this).closest('tr').removeClass('table-active');
            $(this).closest("tr").find(".rowCheckThis").prop('checked', false);
        });
        var $item = $(this).closest("tr").find(".rowCheckThis").val();
        $(this).closest("tr").find(".rowCheckThis").prop('checked', true);
        $(this).closest("tr").addClass('table-active');
        $.ajax({
            url: 'includes/transactions.inc.php',
            method: 'post',
            data: 'transNo=' + $item
        }).done(function(details) {
            details = JSON.parse(details);
            $('.label-movie').text(details['movieTitle']);
            $('.label-room').text(details['roomName']);
            $('.label-time').text(details['formattedTime']);
            $('.label-ticket').text(details['tickets']);
            $('.label-seat').text(details['seats']);
            $('.label-total').text("Php " + details['total_amount'].toFixed(2));
            $('.label-payment').text("Php " + details['payment'].toFixed(2));
        })
    }
});

// Select table row by checking the checkbox
$(".rowCheckThis").click(function() {
    var num_of_rows = document.querySelectorAll('.rowCheckThis').length;
    if (!$(this).is(':checked')) {
        $(this).prop('checked', false);
        $(this).closest("tr").removeClass('table-active');
        $('.label-movie').text("");
        $('.label-room').text("");
        $('.label-time').text("");
        $('.label-ticket').text("");
        $('.label-seat').text("");
        $('.label-total').text("");
        $('.label-payment').text("");
        $('#chkselectAll').prop('checked', false);
    } else {
        var num_of_selected = document.querySelectorAll('.rowCheckThis:checked').length;
        if (num_of_selected == 1) {
            var $item = $(this).val();
            $(this).prop('checked', true);
            $(this).closest("tr").addClass('table-active');
            $.ajax({
                url: 'includes/transactions.inc.php',
                method: 'post',
                data: 'transNo=' + $item
            }).done(function(details) {
                details = JSON.parse(details);
                $('.label-movie').text(details['movieTitle']);
                $('.label-room').text(details['roomName']);
                $('.label-time').text(details['formattedTime']);
                $('.label-ticket').text(details['tickets']);
                $('.label-seat').text(details['seats']);
                $('.label-total').text("Php " + details['total_amount'].toFixed(2));
                $('.label-payment').text("Php " + details['payment'].toFixed(2));
            })
        } else if (num_of_selected > 1) {
            $(this).prop('checked', true);
            $(this).closest("tr").addClass('table-active');
            $('.label-movie').text("");
            $('.label-room').text("");
            $('.label-time').text("");
            $('.label-ticket').text("");
            $('.label-seat').text("");
            $('.label-total').text("");
            $('.label-payment').text("");
            if (num_of_rows == num_of_selected) {
                $('#chkselectAll').prop('checked', true);
            }
        }
    }
});