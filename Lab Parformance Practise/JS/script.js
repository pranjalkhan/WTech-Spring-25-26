document.getElementById("contactForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let isValid = true;

    if (!validateFname()) isValid = false;
    if (!validateLname()) isValid = false;
    if (!validateEmail()) isValid = false;
    if (!validatePhone()) isValid = false;
    if (!validateMessage()) isValid = false;

    if (isValid) {

        let fname = document.getElementById("fname").value;
        let lname = document.getElementById("lname").value;
        let email = document.getElementById("email").value;
        let phone = document.getElementById("phone").value;
        let message = document.getElementById("message").value;

        console.log("First Name:", fname);
        console.log("Last Name:", lname);
        console.log("Email:", email);
        console.log("Phone:", phone);
        console.log("Message:", message);

        alert("Form submitted successfully!");
        document.getElementById("contactForm").reset();
    }
});

// First Name Validation
function validateFname() {
    let fname = document.getElementById("fname").value.trim();
    document.getElementById("fnameError").textContent = "";

    if (fname === "") {
        document.getElementById("fnameError").textContent = "Field Value need to be filled up";
        return false;
    }
    return true;
}

// Last Name Validation
function validateLname() {
    let lname = document.getElementById("lname").value.trim();
    document.getElementById("lnameError").textContent = "";

    if (lname === "") {
        document.getElementById("lnameError").textContent = "Field Value need to be filled up";
        return false;
    }
    return true;
}

// Email Validation
function validateEmail() {
    let email = document.getElementById("email").value.trim();
    document.getElementById("emailError").textContent = "";

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        document.getElementById("emailError").textContent = "Field Value need to be filled up";
        return false;
    }
    else if (!emailPattern.test(email)) {
        document.getElementById("emailError").textContent = "Please enter valid email";
        return false;
    }

    return true;
}

// Phone Validation
function validatePhone() {
    let phone = document.getElementById("phone").value.trim();
    document.getElementById("phoneError").textContent = "";

    let phonePattern = /^[0-9]{7,15}$/;

    if (phone === "") {
        document.getElementById("phoneError").textContent = "Field Value need to be filled up";
        return false;
    }
    else if (!phonePattern.test(phone)) {
        document.getElementById("phoneError").textContent = "Enter valid phone number";
        return false;
    }

    return true;
}

// Message Validation
function validateMessage() {
    let message = document.getElementById("message").value.trim();
    document.getElementById("messageError").textContent = "";

    if (message === "") {
        document.getElementById("messageError").textContent = "Field Value need to be filled up";
        return false;
    }
    return true;
}


