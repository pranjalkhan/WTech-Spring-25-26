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
    document.getElementById("fnameError").innerHTML = "";

    if (fname === "") {
        document.getElementById("fnameError").innerHTML = "Field Value need to be filled up";
        return false;
    }
    else if( !/^[a-zA-Z]+$/.test(fname)) {
        document.getElementById("fnameError").innerHTML = "First name should contain only letters";
        return false;
    }
    return true;
}

// Last Name Validation
function validateLname() {
    let lname = document.getElementById("lname").value.trim();
    document.getElementById("lnameError").innerHTML = "";

    if (lname === "") {
        document.getElementById("lnameError").innerHTML = "Field Value need to be filled up";
        return false;
    }
    else if( !/^[a-zA-Z]+$/.test(lname)) {
        document.getElementById("lnameError").innerHTML = "Last name should contain only letters";
        return false;
    }
    return true;
}

// Email Validation
function validateEmail() {
    let email = document.getElementById("email").value.trim();
    document.getElementById("emailError").innerHTML = "";

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        document.getElementById("emailError").innerHTML = "Field Value need to be filled up";
        return false;
    }
    else if (!emailPattern.test(email)) {
        document.getElementById("emailError").innerHTML = "Please enter valid email";
        return false;
    }

    return true;
}

// Phone Validation
function validatePhone() {
    let phone = document.getElementById("phone").value.trim();
    document.getElementById("phoneError").innerHTML = "";

    let phonePattern = /^[0-9]{7,15}$/;

    if (phone === "") {
        document.getElementById("phoneError").innerHTML = "Field Value need to be filled up";
        return false;
    }
    else if (!phonePattern.test(phone)) {
        document.getElementById("phoneError").innerHTML = "Enter valid phone number";
        return false;
    }

    return true;
}

// Message Validation
function validateMessage() {
    let message = document.getElementById("message").value.trim();
    document.getElementById("messageError").innerHTML = "";

    if (message === "") {
        document.getElementById("messageError").innerHTML = "Field Value need to be filled up";
        return false;
    }
    return true;
}


