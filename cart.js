//declaring variables and assign them to the html elements with the specified Id
let sectionElement = document.getElementById("cart-section");
let emptyCartMessage = document.getElementById("empty-cart");
let cartManagmentBtns = document.getElementById("cartManagment-btns");
let cartPriceEl = document.getElementById("cartPrice");

//if the browsers local storage is empty hide the two buttons
//and show the text
if (localStorage.getItem("product-1") === null) {
  emptyCartMessage.style.display = "block";
  cartManagmentBtns.style.display = "none";
  cartPriceEl.style.display = "none";
} else {
  //if the browsers local storage is not empty hide the text
  //and show the two buttons
  cartManagmentBtns.style.display = "inline";
  cartPriceEl.style.display = "block";
  emptyCartMessage.style.display = "none";
}

let totalAmount = 0;

//for loop that goes through all the element in the browsers local storage
//and display the products information
for (let i = 1; i <= localStorage.length; i++) {
  //declare variable and assign it to the value of the current key in the browsers local storage
  //then it is split at every "," creating an array of strings
  if (localStorage.getItem(`product-${i}`) !== null) {
    let displayedItemInfo = localStorage.getItem(`product-${i}`).split(",");
    totalAmount = totalAmount + Number(displayedItemInfo[3]);
    //creating a new div html element with className "cart-item-box"
    let itemBox = document.createElement("div");
    itemBox.className = "cart-item-box";

    //changing the divs' inner html code to,
    // a paragraph containg the products number in the cart
    // an image of the product
    // a paragraph containg its price
    //and a button for deleting it for the cart
    itemBox.innerHTML = `
  <p>${i}</p>
  <div class="cart-item-img"><img src="${displayedItemInfo[1]}" alt="product image"></div>
  <h5>${displayedItemInfo[2]}</h5>
  <p>£${displayedItemInfo[3]}</p>
  <button class="deleteItem-btn" onclick="deleteItem(${i})">Delete</button>
  `;

    //appending it as a child to the sectionElement
    sectionElement.appendChild(itemBox);
  }
}

document.getElementById("cartPrice").innerText = `£${totalAmount}`;

//function for deleting the product from the cart
function deleteItem(key) {
  let maxProdNum = 0;
  //if there are products after the one we want to delete,
  //the last product in the local storage is assigned to the deleted products location
  //and the last key is removed
  for (let i = 1; i <= localStorage.length; i++) {
    if (localStorage.getItem(`product-${i}`) !== null) maxProdNum = i;
  }

  if (key < maxProdNum) {
    localStorage.setItem(
      `product-${key}`,
      localStorage.getItem(`product-${maxProdNum}`)
    );
    localStorage.removeItem(`product-${maxProdNum}`);
  } else {
    //else the selected product is removed from local storage
    localStorage.removeItem(`product-${key}`);
  }

  //the page reloads to update the carts content
  location.reload();
}

//function that clears everyting inside local storage and reloads the page
function emptyCart() {
  localStorage.clear();
  location.reload();
}

//function that gets the ids of the products in the cart and store it in the url
function checkout() {
  let prodIds = "";

  //go through the local storage items and take the id of every product
  for (let i = 1; i <= localStorage.length; i++) {
    if (localStorage.getItem(`product-${i}`) !== null) {
      let displayedItemInfo = localStorage.getItem(`product-${i}`).split(",");
      prodIds += `${displayedItemInfo[0]},`;
    }
  }
  //store the ids in the url
  window.location.href = `cart.php?checkoutIds=${prodIds}`;
}
