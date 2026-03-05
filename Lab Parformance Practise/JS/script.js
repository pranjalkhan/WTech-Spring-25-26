document.getElementById("contactForm").addEventListener("submit", function(event) {
  event.preventDefault();

  // Collect values
  let fname = document.getElementById("fname").value.trim();
  let lname = document.getElementById("lname").value.trim();
  let email = document.getElementById("email").value.trim();
  let phone = document.getElementById("phone").value.trim();
  let message = document.getElementById("message").value.trim();

  // Reset all error messages
  document.getElementById("fnameError").textContent = "";
  document.getElementById("lnameError").textContent = "";
  document.getElementById("emailError").textContent = "";
  document.getElementById("phoneError").textContent = "";
  document.getElementById("messageError").textContent = "";

  let isValid = true;

  // First Name
  if (!fname) {
    document.getElementById("fnameError").textContent = "Field Value need to be filled up";
    isValid = false;
  }

  // Last Name
  if (!lname) {
    document.getElementById("lnameError").textContent = "Field Value need to be filled up";
    isValid = false;
  }

  // Email
  if (!email) {
    document.getElementById("emailError").textContent = "Field Value need to be filled up";
    isValid = false;
  } else {
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      document.getElementById("emailError").textContent = "Please enter a valid email address";
      isValid = false;
    }
  }

  // Phone
  if (!phone) {
    document.getElementById("phoneError").textContent = "Field Value need to be filled up";
    isValid = false;
  } else {
    let phonePattern = /^[0-9]{7,15}$/;
    if (!phonePattern.test(phone)) {
      document.getElementById("phoneError").textContent = "Please enter a valid phone number (7–15 digits)";
      isValid = false;
    }
  }

  // Message
  if (!message) {
    document.getElementById("messageError").textContent = "Field Value need to be filled up";
    isValid = false;
  }

  // If all valid → print values
  if (isValid) {
    console.log("First Name:", fname);
    console.log("Last Name:", lname);
    console.log("Email:", email);
    console.log("Phone:", phone);
    console.log("Message:", message);
    alert("Form submitted successfully!");
  }
});


