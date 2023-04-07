<?php
//include 'pageFoundation.php' to add the common part of all pages to this one
include('pageFoundation.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Union Shop at UCLAN</title>

  <!-- importing an external js file for the menu button functionality -->
  <script src="cart.js" defer></script>
</head>

<body>

  <!--Page Main-->
  <main id="cart-main">
    <!--header and paragraph (welcoming the user if he is logged in)-->
    <h2>Welcome <?php if (empty($_SESSION)) echo "";
                else echo $_SESSION['name']; ?> to your shopping cart</h2>
    <p>The items you've added to your shopping cart are:</p>

    <!--section with two headers-->
    <section id="cart-item-titles">
      <h4>Item</h4>
      <h4>Product</h4>
    </section>

    <!--cart section where the cart items are added/deleted using JavaScript-->
    <div id="cart-section"></div>
    <h4 id="cartPrice"></h4>
    <!--two buttons for checkout and emptying the cart-->
    <div id="cartManagment-btns">

      <!--buttons have onclick attributes for their functionality-->
      <!--shown when cart has items-->
      <button id="checkoutBtn" name="checkoutBtn" onclick="checkout()">Checkout</button>
      <button onclick="emptyCart()">Empty cart</button>
      <h5 id="loginToCheckout">Please login to checkout</h5>
    </div>

    <!--header, shown when cart is empty-->
    <h1 id="empty-cart">Cart is empty!</h1>

    <!-- section for peding orders -->
    <div id="pendingOrders">
      <h3>Pending Orders</h3>
      <?php
      //if the user isn't logged in
      if (!empty($_SESSION)) {

        //include 'connect.php' to be able to communicate with the database 
        include_once('connect.php');
        //getting username from the session item 'name'
        $username = $_SESSION['name'];

        //getting the users id from the database
        $sql = $pdo->prepare("SELECT user_id from tbl_users WHERE user_full_name = '$username';");
        $sql->execute();
        $usrId = $sql->fetch()[0]; //get first element of the returned array to avoid duplicates

        //getting all orders from the database made by the current user
        $stmt = $pdo->prepare("SELECT * FROM tbl_orders WHERE user_id = '$usrId'");
        $stmt->execute();
        $orders = $stmt->fetchAll();

        //if no orders have been returned display a message
        if ($stmt->rowCount() == 0) echo "<p style='font-size: 2vw; text-align: center'>No Pending Orders</p>";

        //go through each order
        foreach ($orders as $order) {
          //get all order values
          $orderId = $order['order_id'];
          $orderDate = $order['order_date'];
          $productIds = $order['product_ids'];

      ?>
          <!-- html code to display the orders -->
          <div class="order-box">
            <h4>Order Id: <?php echo $orderId; ?></h4>
            <p>Products: <?php echo $productIds; ?></p>
            <p>Date: <?php echo $orderDate; ?></p>
          </div>
      <?php
        }
      }
      ?>

    </div>
  </main>

  <?php
  //include 'connect.php' to be able to communicate with the database 
  include('connect.php');

  //if the user isn't logged in display and hide elements accordingly
  if (empty($_SESSION)) {
    echo '<script>
    document.getElementById("checkoutBtn").style.display = "none";
    document.getElementById("loginToCheckout").style.display = "block";
    </script>';
  }
  //if the user is logged in display and hide elements accordingly 
  else {
    echo '<script>
    document.getElementById("checkoutBtn").style.display = "inline";
    document.getElementById("loginToCheckout").style.display = "none";
    </script>';
  }

  //if the user submits an order and he is logged in
  if (isset($_POST['checkoutSubmit']) && !empty($_SESSION)) {

    $username = $_SESSION["name"]; //get user name from session value 
    $date = date("Y/m/d h:i:s"); //get systems date and time with the specified format
    $prodIds = $_GET["checkoutIds"]; //get product ids from the url

    //get the current users id from the database
    $sql = $pdo->prepare("SELECT user_id from tbl_users WHERE user_full_name = '$username'");
    $sql->execute();
    $usrId = $sql->fetch()[0]; //get first element of the returned array to avoid duplicates

    //get all orders from the database
    $sql = $pdo->prepare("SELECT * from tbl_orders");
    $sql->execute();
    //set the next orders id to the number of orders returned plus one
    $orderId = $sql->rowCount() + 1;

    //send the submitted order to the database
    $sql = $pdo->prepare("INSERT into tbl_orders(order_id, order_date, user_id, product_ids) 
      VALUES (:id,:ordTime,:usrId,:prodIds);");
    //bind the values sent to the submitted values
    $sql->bindParam(':id', $orderId);
    $sql->bindParam(':ordTime', $date);
    $sql->bindParam(':usrId', $usrId);
    $sql->bindParam(':prodIds', $prodIds);
    $sql->execute();
    //clear the local storage and clear the ids from the url 
    echo '<script>
    alert("Order submited.");
    localStorage.clear();
    window.location.href = "cart.php";
    </script>';
  }


  ?>

  <!-- checkout modal -->
  <div id="checkoutModal" class="modal">
    <div id="checkoutModalContent" class="modal-content">

      <span class="close" onclick="closeModal()">&times;</span>

      <form id="checkoutForm" name="checkoutForm" action="" method="POST">
        <h1>Are you sure you want to proceed?</h1>
        <button type="submit" id="checkoutBtn" name="checkoutSubmit">Yes</button>
        <button type="submit" onclick="closeModal(); return false">No</button>
        </p>
      </form>
    </div>
  </div>

  <?php
  //if there are ids in the url display the checkout form
  if (isset($_GET['checkoutIds'])) {
    echo "<script>document.getElementById('checkoutModal').style.display = 'block';</script>";
  }
  ?>

</body>

</html>