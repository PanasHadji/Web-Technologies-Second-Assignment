//creating a variable and assigning it to the html element with the specified Id
let menuBox = document.getElementById("dropdown-content");
let modalIn = document.getElementById("modalIn");
let modalOut = document.getElementById("modalOut");
let checkoutModal = document.getElementById("checkoutModal");
let loginForm = document.getElementById("loginForm");
let signupForm = document.getElementById("signupForm");

let myInput = document.getElementById("Spass");
let letter = document.getElementById("letter");
let capital = document.getElementById("capital");
let number = document.getElementById("number");
let length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function () {
  document.getElementById("message").style.display = "block";
};

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function () {
  document.getElementById("message").style.display = "none";
};

// When the user starts to type something inside the password field
myInput.onkeyup = function () {
  // Validate lowercase letters
  let lowerCaseLetters = /[a-z]/g;
  if (myInput.value.match(lowerCaseLetters)) {
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }

  // Validate capital letters
  let upperCaseLetters = /[A-Z]/g;
  if (myInput.value.match(upperCaseLetters)) {
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  let numbers = /[0-9]/g;
  if (myInput.value.match(numbers)) {
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate length
  if (myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
};

//validating for the signUp and login forms
function validation(select) {

  //if user is trying to signUp
  if (select === "signup") {
    //getting all submited form values
    let id = document.f1.Suser.value;
    let ps = document.f1.Spass.value;
    let confirmPs = document.f1.confirmPass.value;
    let ad = document.f1.address.value;
    let email = document.f1.email.value;

    //email format
    let re = /^\S+@\S+\.\S+$/;

    if (  //if all values are empty
      id.length == "" &&
      ps.length == "" &&
      ad.length == "" &&
      email.length == ""
    ) {
      alert("SignUp fields are empty");
      return false;
    } else { 
      if (id.length == "") { //if name is empty
        alert("User Name is empty");
        return false;
      }
      if (ps.length == "") { //if password is empty
        alert("Password field is empty");
        return false;
      }
      if (ps !== confirmPs) { //if password doesn't match confiramtion password
        alert("Passwords don't match");
        return false;
      }
      if (ad.length == "") { //if address is empty
        alert("Address field is empty");
        return false;
      }
      if (email.length == "") { //if email is empty
        alert("Email field is empty");
        return false;
      }
      if (!re.test(email)) { //if email doesn't has a correct formt
        alert("Incorrect email format");
        return false;
      }

      if ( //if password doesn't meet criteria
        letter.classList != "valid" ||
        capital.classList != "valid" ||
        number.classList != "valid" ||
        length.classList != "valid"
      ) {
        alert("Password doesn't match criteria");
        return false;
      }
    }
  } else if (select === "login") { //if user is trying to login
    //get submited values
    let id = document.f2.Luser.value;
    let ps = document.f2.Lpass.value;

    if (id.length == "" && ps.length == "") { //if values are empty
      alert("User Name and Password fields are empty");
      return false;
    } else {
      if (id.length == "") { //if name is empty
        alert("User Name is empty");
        return false;
      }
      if (ps.length == "") { //if password is empty
        alert("Password field is empty");
        return false;
      }
    }
  }
}

//function for toggling the visibility of the menuBox element
function toggleMenu() {
  //when click, if is menuBox displayed, hide it
  if (menuBox.style.display === "flex") menuBox.style.display = "none";
  //when click, if is menuBox hidden, display it
  else menuBox.style.display = "flex";
}

//An event listener on the browser window to detect resizing
window.addEventListener("resize", function removeMenu() {
  //when resized, if windows with is larger or equal to 480 hide menuBox
  menuBox.style.display = "none";
});

//validate product search 
function searchValidation() {
  //get search value
  let search = document.searchForm.search.value;
  if (search.length == "") { //if value is empty
    alert("Search field is empty");
    return false;
  }
}

//validate submited review
function validateReview() {
  //get submited values
  let reviewTitle = document.reviewForm.reviewTitle.value;
  let reviewDesc = document.reviewForm.reviewDesc.value;
  let reviewRating = document.reviewForm.reviewRating.value;

  if (reviewTitle.length == "") { //if title is empty
    alert("Title field is empty");
    return false;
  }
  if (reviewDesc.length == "") { //if description is empty
    alert("Description field is empty");
    return false;
  }
  if (reviewRating.length == "") { //if rating is empty
    alert("Rating field is empty");
    return false;
  }
}

//fuction to store products in localstorage
function moveToLocal(prodId, prodImg, prodTitle, prodPrice) {

  let numOfProductsInCart = 1; //first product index

  //find how many products are already in the cart to place the correct next index
  for (let i = 1; i <= localStorage.length; i++) {
    if (localStorage.getItem(`product-${i}`) !== null) numOfProductsInCart++;
  }

  //store product in the correct next index
  localStorage.setItem(`product-${numOfProductsInCart}`, [
    prodId,
    prodImg,
    prodTitle,
    prodPrice,
  ]);

  //shows an alert message to the user with the item that he added
  alert("Added to cart!");
}

//function to open the signUp or logout modals
function openModal(select) {
  //if user sugning in open signUp modal and form 
  if (select === "in") {
    modalIn.style.display = "block";
    document.getElementById("signupForm").style.display = "block";
  } 
  //if logging out open logout modal
  else if (select === "out") modalOut.style.display = "block";
}

// When the user clicks on <span> (x), close the modals
function closeModal() {
  //if the user is in "cart.php" page also reset the url (remove any product ids)
  if (location.href.includes("cart.php")) {
    checkoutModal.style.display = "none";
    window.location.href = "cart.php";
  }
  modalIn.style.display = "none";
  modalOut.style.display = "none";
}

//function to toggle the signUp and login forms in the modal 
function toggleLogin(select) {
  if (select === "login") {
    loginForm.style.display = "block";
    signupForm.style.display = "none";
    document.getElementById("sign-btn").style.display = "block";
    document.getElementById("log-btn").style.display = "none";
  } else if (select === "signup") {
    signupForm.style.display = "block";
    loginForm.style.display = "none";
    document.getElementById("sign-btn").style.display = "none";
    document.getElementById("log-btn").style.display = "block";
  }
}

// When the user clicks anywhere outside the modal, close it
window.onclick = function (event) {
  if (
    event.target === modalIn ||
    event.target === modalOut ||
    event.target === checkoutModal
  ) {
    closeModal();
  }
};
