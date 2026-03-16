//static data input
var Unit_Price = 1000;
var Days = 30;

var quantityInput = document.getElementById('quantity');
var totalPriceInput = document.getElementById('totalPrice');
var errorMsg = document.getElementById('errorMsg');

var couponAlertShown = false;

function calculateTotal() {
    var qty = parseFloat(quantityInput.value);

    if (qty < 0) {
        quantityInput.value = 0;
        qty = 0;
        errorMsg.textContent = 'Quantity cannot be less than 0.';
    } else {
        errorMsg.textContent = '';
    }

    if (quantityInput.value === '' || isNaN(qty)) {
        totalPriceInput.value = 0;
        couponAlertShown = false;
        return 0;
    }

    var total = UNIT_PRICE * qty * DAYS;
    totalPriceInput.value = total;

    return total;
}
