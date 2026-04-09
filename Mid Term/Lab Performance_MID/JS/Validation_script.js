console.log("Connected Javascript file successfully.");

const price = 1000;
const days = 30;

let q = document.getElementById("quantity");
let t = document.getElementById("totalPrice");
let e = document.getElementById("errorRow");

q.addEventListener("input", function () {

    let qty = parseInt(q.value) || 0;

    if (qty < 0) {
        e.innerHTML = "Quantity cannot be negative. Resetting to 0.";
        qty = 0;
        q.value = 0;
    } else {
        e.innerHTML = "";
    }
    let total = price * qty * days;
    t.value = total;
    if (total > 1000) {
        alert("Congratulations! You are eligible for a gift coupon.");
    }
});
