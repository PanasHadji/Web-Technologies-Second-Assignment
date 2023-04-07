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
</head>

<body>
  <main id="products-main">

  <!-- serch form (user enters text to search for a product)-->
    <form id="searchForm" name="searchForm" action="" method="POST">
      <h1>Best Quality Guaranteed!!</h1>
      <input type="text" name="search" placeholder="Search">
      <button type="submit" id="searchBtn" onclick="return searchValidation()">Search</button>
    </form>


    <!--Product type navigation menu-->
    <form action="" id="navForm" method="POST">
      <h3>Navigate to any product type fast!</h3>
      <button typp="submit" class="navProdBtns" name="prodType" value="Uclan Hoodie">Hoodies</button>
      <button type="submit" class="navProdBtns" name="prodType" value="UCLan Logo Jumper">Jumpers</button>
      <button type="sumbit" class="navProdBtns" name="prodType" value="UCLan Logo Tshirt">T-Shirts</button>
    </form>

    <!-- section for showing the products -->
    <div id="products-section">
      <?php
      //include 'connect.php' to be able to communicate with the database 
      include_once('connect.php');

      //if the user dosn't submit the search form
      if (!isset($_POST['search'])) {

        //if the product type isn't set, set it to 'UCLan Hoodie' (3 navigation buttons)
        if (!isset($_POST['prodType'])) $prodType = 'UCLan Hoodie';
        else $prodType = $_POST['prodType']; //else set it to the selected type

        //get all products of the selected type form the database
        $stmt = $pdo->prepare("SELECT * FROM tbl_products WHERE product_type = '$prodType'");
      } else {
        //get the searched value that was submitted
        $searchProd = $_POST['search'];
        $stmt = $pdo->prepare("SELECT * FROM tbl_products WHERE product_title LIKE '%$searchProd%'");
      }
      $stmt->execute();

      //if no product was returned form the database display a message
      if ($stmt->rowCount() == 0) {
        echo '<h1>No product found</h1>';
        echo '<script>document.getElementById("products-section").style.padding = "50%";</script>';
      } else echo '<script>document.getElementById("products-section").style.padding = "0";</script>';

      //get all products
      $products = $stmt->fetchAll();
      
      //go through each product and display it 
      foreach ($products as $product) {
        //get all the returned values from each product 
        $prodId = $product['product_id'];
        $prodTitle = $product['product_title'];
        $prodDesc = $product['product_desc'];
        $prodImg = $product['product_image'];
        $prodPrice = $product['product_price'];
      ?>
      <!-- html code to display each product -->
        <div class="prod-box">
          <img class="product-img" src="<?php echo $prodImg; ?>" alt="product image">
          <div class="prodInfo-box">
            <h3><?php echo $prodTitle; ?></h3>
            <p><?php echo $prodDesc; ?> <a href="item.php?Id=<?php echo $prodId; ?>">Read more</a></p>
            <h3><?php echo $prodPrice; ?></h3>
            <button class="buy-btn" onclick="moveToLocal('<?php echo $prodId, ',', $prodImg, ',', $prodTitle, ',', $prodPrice; ?>')">Buy</button>
          </div>
        </div>
      <?php
      }
      ?>

    </div>

    <!--anchor tag that when pressed takes the users to the top of the page-->
    <a id="top-btn" href="#products-section">Top</a>
  </main>
</body>

</html>