// Adding style to <select>
var formSelects = document.getElementsByClassName('form-select');
for (let i = 0; i < formSelects.length; i++) {
    const select = formSelects[i];
    var style = getComputedStyle(select).backgroundImage;
    style = style.replace("%23343a40", "red");
    select.style.backgroundImage = style;

}

// Seat Status Handler
var count = 0;
var seats = document.getElementsByClassName("seat");
var seatsAvailable = 0;

for (let i = 0; i < seats.length; i++) {
    const seat = seats[i];

    if (!(seat.classList.contains("leg"))) {
        if (!(seat.classList.contains("seat-selected")) && !(seat.classList.contains("seat-occupied"))) {
            seatsAvailable++;
        }
    }
}
seatListener();

function seatListener() {
    var seatsList = document.getElementById("seatsList");
    for (let i = 0; i < seats.length; i++) {
        const seat = seats[i];

        seat.addEventListener("click", (event) => {
            var seatsAvailable = document.getElementById("seatsAvailable").dataset.num;
            if (!(seat.classList.contains("seat-selected")) && !(seat.classList.contains("seat-occupied")) && !(seat.classList.contains("leg"))) {
                seat.classList.add("seat-selected");
                seatsAvailable--;
                updateSeatList("add", seat.textContent);
            } else if (seat.classList.contains("seat-selected") && !(seat.classList.contains("leg"))) {
                seat.classList.remove("seat-selected");
                seatsAvailable++;
                updateSeatList("remove", seat.textContent);
            }
            document.getElementById("seatsAvailable").innerHTML = `Seats Available: ${seatsAvailable}`;
            document.getElementById("seatsAvailable").dataset.num = seatsAvailable;
        })
    }
}


// Seats List Handler
var seatsArray = [];
function updateSeatList(action, value) {

    if (action == "add") {
        seatsArray.push(value);
    } else if (action == "remove") {
        for (let i = 0; i < seatsArray.length; i++) {
            if (seatsArray[i] == value) {
                seatsArray.splice(i, 1);
            }
        }
    }

    seatsList.innerHTML = "";
    seatsArray.sort();
    document.getElementById("seatsSelected").innerHTML = seatsArray.length;
    for (let i = 0; i < seatsArray.length; i++) {
        var li = document.createElement("input");
        li.className = "list-group-item seat-item";
        li.setAttribute("type", "text");
        li.setAttribute("name", "seatsList[]");
        li.setAttribute("value", seatsArray[i]);
        li.setAttribute("disabled", "");
        seatsList.appendChild(li);
    }
}

document.getElementById('movies').addEventListener("change", function () {
    seatsArray = [];
    document.getElementById("seatsSelected").innerHTML = seatsArray.length;
});

document.getElementById('rooms').addEventListener("change", function () {
    seatsArray = [];
    document.getElementById("seatsSelected").innerHTML = seatsArray.length;
});

document.getElementById('times').addEventListener("change", function () {
    seatsArray = [];
    document.getElementById("seatsSelected").innerHTML = seatsArray.length;
});

// Ticket Type Checkbox and Counter Handler
var chkAdult = document.getElementById("chkAdult");
var chkChild = document.getElementById("chkChild");
var chkSenior = document.getElementById("chkSenior");
var chkPWD = document.getElementById("chkPWD");

var adultCounter = document.getElementById("adultCounter");
var childCounter = document.getElementById("childCounter");
var seniorCounter = document.getElementById("seniorCounter");
var pwdCounter = document.getElementById("pwdCounter");

var adultCount = 0;
var childCount = 0;
var seniorCount = 0;
var pwdCount = 0;

var adultCountLabel = document.getElementById("adultCountLabel");
var childCountLabel = document.getElementById("childCountLabel");
var seniorCountLabel = document.getElementById("seniorCountLabel");
var pwdCountLabel = document.getElementById("pwdCountLabel");

// Ticket Type Handler
function ticketTypeChk() {
    if (chkAdult.checked == true) {
        adultCounter.classList.remove("d-none");
        if (adultCount == 0) { adultCount = 1 };
        adultCountLabel.innerHTML = adultCount;
        chkAdult.value = adultCount;
    } else {
        adultCounter.classList.add("d-none");
        adultCount = 0;
        chkAdult.value = adultCount;
    }

    if (chkChild.checked == true) {
        childCounter.classList.remove("d-none");
        if (childCount == 0) { childCount = 1 };
        childCountLabel.innerHTML = childCount;
        chkChild.value = childCount;
    } else {
        childCounter.classList.add("d-none");
        childCount = 0;
        chkChild.value = childCount;
    }

    if (chkSenior.checked == true) {
        seniorCounter.classList.remove("d-none");
        if (seniorCount == 0) { seniorCount = 1 };
        seniorCountLabel.innerHTML = seniorCount;
        chkSenior.value = seniorCount;
    } else {
        seniorCounter.classList.add("d-none");
        seniorCount = 0;
        chkSenior.value = seniorCount;
    }

    if (chkPWD.checked == true) {
        pwdCounter.classList.remove("d-none");
        if (pwdCount == 0) { pwdCount = 1 };
        pwdCountLabel.innerHTML = pwdCount;
        chkPWD.value = pwdCount;
    } else {
        pwdCounter.classList.add("d-none");
        pwdCount = 0;
        chkPWD.value = pwdCount;
    }
}

// Ticket Counter Handler
document.getElementById("adultPlus").addEventListener("click", function () {
    adultCount++;
    adultCountLabel.innerHTML = adultCount;
    chkAdult.value = adultCount;
});

document.getElementById("adultMinus").addEventListener("click", function () {
    if (adultCount > 1) {
        adultCount--;
        adultCountLabel.innerHTML = adultCount;
        chkAdult.value = adultCount;
    } else {
        chkAdult.checked = false;
        ticketTypeChk();
    }
});

document.getElementById("childPlus").addEventListener("click", function () {
    childCount++;
    childCountLabel.innerHTML = childCount;
    chkChild.value = childCount;
});

document.getElementById("childMinus").addEventListener("click", function () {
    if (childCount > 1) {
        childCount--;
        childCountLabel.innerHTML = childCount;
        chkChild.value = childCount;
    } else {
        chkChild.checked = false;
        ticketTypeChk();
    }
});

document.getElementById("seniorPlus").addEventListener("click", function () {
    seniorCount++;
    seniorCountLabel.innerHTML = seniorCount;
    chkSenior.value = seniorCount;
});

document.getElementById("seniorMinus").addEventListener("click", function () {
    if (seniorCount > 1) {
        seniorCount--;
        seniorCountLabel.innerHTML = seniorCount;
        chkSenior.value = seniorCount;
    } else {
        chkSenior.checked = false;
        ticketTypeChk();
    }
});

document.getElementById("pwdPlus").addEventListener("click", function () {
    pwdCount++;
    pwdCountLabel.innerHTML = pwdCount;
    chkPWD.value = pwdCount;
});

document.getElementById("pwdMinus").addEventListener("click", function () {
    if (pwdCount > 1) {
        pwdCount--;
        pwdCountLabel.innerHTML = pwdCount;
        chkPWD.value = pwdCount;
    } else {
        chkPWD.checked = false;
        ticketTypeChk();
    }
});

// Check inputs before proceeding to Transaction Summary
function checkForm() {
    var totalCount = adultCount + childCount + seniorCount + pwdCount;
    var movieSelect = document.getElementById("movies");
    var roomSelect = document.getElementById("rooms");
    var timeSelect = document.getElementById("times");

    var check_ticketCount = (totalCount == 0);
    var check_movieSelect = (movieSelect.value == "");
    var check_roomSelect = (roomSelect.value == "");
    var check_timeSelect = (timeSelect.value == "");
    var check_Seats = (seatsArray.length == 0);

    if (check_ticketCount && check_movieSelect && check_roomSelect && check_timeSelect && check_Seats) {
        alert("Complete necessary details. Please select a movie.");
        return false;
    } else if (check_ticketCount || check_movieSelect || check_roomSelect || check_timeSelect || check_Seats) {
        var msg = "";
        if (check_movieSelect) {
            msg = "Please select a movie.";
        } else if (check_roomSelect) {
            msg = "Please select a room.";
        } else if (check_timeSelect) {
            msg = "Please select a screening time.";
        } else if (check_ticketCount) {
            msg = "Please choose the type and quantity of tickets";
        } else if (check_Seats) {
            msg = "Please select seats";
        }
        alert(msg);
        return false;
    } else if (totalCount != seatsArray.length) {
        alert("The number of tickets doesn't match the number of seats. Please check again.");
        return false;
    }
    var seatitems = document.getElementsByClassName("seat-item");
    for (let i = 0; i < seatitems.length; i++) {
        const seat = seatitems[i];
        seat.removeAttribute("disabled");
        
    }
    return true;
}

// jQuery
// Form Select onChange Listener
$(document).ready(function() {
    populateSeats("reset");
    // Updates Room List when Movie is selected
    $("#movies").change(function() {
        var movieID = $("#movies").val();
        $.ajax({
            url: 'includes/movies.inc.php',
            method: 'post',
            data: 'movieID=' + movieID
        }).done(function(rooms) {
            $('#ticketPrice').text('Price: Php ');
            populateSeats("reset");
            $('#rooms').empty();
            $('#times').empty();
            $('#rooms').append('<option selected disabled value="">Cinema Room</option>')
            $('#times').append('<option selected disabled value="">Schedule</option>')
            rooms = JSON.parse(rooms);
            if (!jQuery.isEmptyObject(rooms)) {
                $('#ticketPrice').text('Price: Php ' + rooms[0]['ticketPrice'].toFixed(2));
            }
            rooms.forEach(function(room) {
                $('#rooms').append('<option value="' + room.roomID + '">' + room['roomName'] + '</option>');
            })
        })
    });
    // Updates Schedule List when Room is selected
    $("#rooms").change(function() {
        var roomID = $("#rooms").val();
        $.ajax({
            url: 'includes/movies.inc.php',
            method: 'post',
            data: 'roomID=' + roomID
        }).done(function(times) {
            times = JSON.parse(times);
            populateSeats("reset");
            $('#times').empty();
            $('#times').append('<option selected disabled value="">Schedule</option>');
            times.forEach(function(time) {
                const timeString = time['screening_time'];
                const timeString12hr = new Date('1970-01-01T' + timeString + 'Z')
                    .toLocaleTimeString('en-US', {
                        timeZone: 'UTC',
                        hour12: true,
                        hour: 'numeric',
                        minute: 'numeric'
                    });
                $('#times').append('<option value="' + timeString + '">' + timeString12hr + '</option>')
            })
        })
    });
    // Updates Seat status when Schedule is selected
    $("#times").change(function() {
        var screening_time = $("#times").val();
        var room_ID = $("#rooms").val();
        $.ajax({
            url: 'includes/movies.inc.php',
            method: 'post',
            data: {
                room_ID: room_ID,
                screeningTime: screening_time
            }
        }).done(function(seats) {
            seats = JSON.parse(seats);
            occupied = JSON.parse(seats['occupied']);
            populateSeats("reset");
            populateSeats("modify");
            $('#seatsAvailable').text('Seats Available: ' + seats['no_of_available']);
            $('#seatsAvailable').attr("data-num", seats['no_of_available']);
            $('#rowA').children().each(function() {
                if (jQuery.inArray($(this).attr("id"), occupied) >= 0) {
                    $(this).addClass("seat-occupied");
                }
            });
            $('#rowB').children().each(function() {
                if (jQuery.inArray($(this).attr("id"), occupied) >= 0) {
                    $(this).addClass("seat-occupied");
                }
            });
            $('#rowC').children().each(function() {
                if (jQuery.inArray($(this).attr("id"), occupied) >= 0) {
                    $(this).addClass("seat-occupied");
                }
            });
            $('#rowD').children().each(function() {
                if (jQuery.inArray($(this).attr("id"), occupied) >= 0) {
                    $(this).addClass("seat-occupied");
                }
            });
            $('#rowE').children().each(function() {
                if (jQuery.inArray($(this).attr("id"), occupied) >= 0) {
                    $(this).addClass("seat-occupied");
                }
            });
        })
    })    

    // Resetting and Creating Seats
    function populateSeats(val) {
        if (val === 'reset') {
            $('#rowA').children().addClass("seat-occupied");
            $('#rowB').children().addClass("seat-occupied");
            $('#rowC').children().addClass("seat-occupied");
            $('#rowD').children().addClass("seat-occupied");
            $('#rowE').children().addClass("seat-occupied");
            $('#rowA').children().removeClass("seat-selected");
            $('#rowB').children().removeClass("seat-selected");
            $('#rowC').children().removeClass("seat-selected");
            $('#rowD').children().removeClass("seat-selected");
            $('#rowE').children().removeClass("seat-selected");
            $('#seatsList').empty();
            $('#seatsAvailable').text('Seats Available: 0');
            $('#seatsAvailable').attr("data-num", 0);
        } else if (val === 'modify') {
            $('#rowA').children().removeClass("seat-occupied");
            $('#rowB').children().removeClass("seat-occupied");
            $('#rowC').children().removeClass("seat-occupied");
            $('#rowD').children().removeClass("seat-occupied");
            $('#rowE').children().removeClass("seat-occupied");
        }
    }
});