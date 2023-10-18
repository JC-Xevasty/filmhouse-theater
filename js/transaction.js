var btnBack = document.getElementById('btnBack');
var btnCheckout = document.getElementById('btnCheckout');
var btnComplete = document.getElementById('btnComplete');
var container = document.getElementById('transaction-container');

// Back and Checkout Button Event Listeners
btnBack.addEventListener('click', () => {
    container.classList.remove("checkout");
    btnCheckout.classList.remove("complete");
    btnCheckout.classList.remove("d-none");
    btnComplete.classList.add("d-none");
    
});

btnCheckout.addEventListener('click', () => {
    container.classList.add("checkout");
    btnCheckout.classList.add("complete");
    btnCheckout.classList.add("d-none");
    btnComplete.classList.remove("d-none");
});

var btnCash = document.getElementById('btnCash');
var btnCard = document.getElementById('btnCard');
var paycontainer = document.getElementById('paymethod-container');
var payment = document.getElementById('payment');
var cardNo = document.getElementById('cardNo');
var cardName = document.getElementById('cardName');

// Payment Method Handler
btnCash.addEventListener('click', () => {
    paycontainer.classList.remove("method-check");
    payment.value = "";
    cardName.value = "";
    cardNo.value = "";
});

btnCard.addEventListener('click', () => {
    paycontainer.classList.add("method-check");
    payment.value = "";
    cardName.value = "";
    cardNo.value = "";
});

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode != 45 &&(charCode < 48 || charCode > 57)))
        return false;
    return true;
}

// Cash Handler
function computeChange() {
    var lblTotal = document.getElementById("lblTotal");
    var totalAmount = lblTotal.dataset.total;
    var payment = document.getElementById("payment").value;
    var floatTotal = parseFloat(totalAmount);
    var floatPayment = parseFloat(payment);
    var lblChange = document.getElementById("lblChange");

    if (payment == ""){
        alert("Please enter payment");
    } else if (floatPayment < floatTotal){
        alert("Insufficient payment");
    } else {
        var floatChange = floatPayment - floatTotal;
        floatChange = floatChange.toFixed(2);
        var strChange = `Php ${floatChange}`;
        lblChange.textContent = strChange;
    }

}

// Card Handler
function completeCard(){
    var cardName = document.getElementById("cardName").value;
    var cardNo = document.getElementById("cardNo").value;

    if (cardName == "" && cardNo ==""){
        alert("Please enter necessary details");
    } else {
        if (cardName == ""){
            alert("Please enter account name found on the card.");
        } else if (cardNo == ""){
            alert("Please enter account number found on the card.");
        } else {
            alert("Checkout successful");
        }
    }
}

// Checking inputs before completing the transaction
function checkForm() {
    var btnCash = document.getElementById('btnCash');
    var btnCard = document.getElementById('btnCard');
    var payment = document.getElementById('payment');
    var cardNo = document.getElementById('cardNo');
    var cardName = document.getElementById('cardName');

    if (btnCash.checked == true) {
        var lblTotal = document.getElementById("lblTotal");
        var totalAmount = lblTotal.dataset.total;
        var payment = document.getElementById("payment").value;
        var floatTotal = parseFloat(totalAmount);
        var floatPayment = parseFloat(payment);
        if (payment == ""){
            alert("Please enter payment.")
            return false;
        } else if (floatPayment < floatTotal){
            alert("Insufficient payment");
            return false;
        }
        return confirm("This action can't be undone. Do you want to continue?");
    } else if (btnCard.checked == true) {
        if (cardNo.value == "" || cardName.value == ""){
            alert("Please enter necessary details.");
            return false;
        }
        return true;
    }
    return false;
}