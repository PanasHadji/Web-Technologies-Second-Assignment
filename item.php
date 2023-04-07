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

  <main>
    <!-- section to dispaly the viewed product -->
    <div id="item-main">
      <?php
      //include 'connect.php' to be able to communicate with the database 
      include_once('connect.php');

      //get the products Id form the url
      $curProdId = $_GET['Id'];

      //if the id in the url is not set navigate the user back to the products page
      if ($curProdId == "") {
        header("Location:products.php");
      } else {
        //else get the product form the database
        $stmt = $pdo->prepare("SELECT * FROM tbl_products WHERE product_id = '$curProdId'");
      }
      $stmt->execute();
      $products = $stmt->fetchAll();

      foreach ($products as $product) {
        //get all information form the returned product
        $prodTitle = $product['product_title'];
        $prodDesc = $product['product_desc'];
        $prodImg = $product['product_image'];
        $prodPrice = $product['product_price'];
      ?>
      <!-- html code for displaying the product  -->
        <div class="prod-box">
          <img class="product-img" src="<?php echo $prodImg; ?>" alt="product image">
          <div class="prodInfo-box">
            <h3><?php echo $prodTitle; ?></h3>
            <p><?php echo $prodDesc; ?></p>
            <h3><?php echo $prodPrice; ?></h3>
            <button class="buy-btn" onclick="moveToLocal('<?php echo $curProdId, ',', $prodImg, ',', $prodTitle, ',', $prodPrice; ?>')">Buy</button>
          </div>
        </div>
      <?php
      }
      ?>

    </div>
      <!-- displaying the average rating -->
    <h2 id="avgRate">Average rating:
      <?php
      //get the products id form the url
      $curProdId = $_GET['Id'];
      //get the product from the database
      $stmt = $pdo->prepare("SELECT * FROM tbl_reviews WHERE product_id = '$curProdId'");
      $stmt->execute();
      $ratings = $stmt->fetchAll();

      //if the database returned any ratings calculate the sum
      if ($stmt->rowCount() > 0) {
        $count = 0;
        $sumRating = 0;
        foreach ($ratings as $rating) {
          $sumRating = $sumRating + $rating['review_rating']; //getting the ratings value
          $count = $count + 1;
        }
        $avg = round(($sumRating / $count), 1); //calculate the average and show it with one decimal
        
        //display it
        echo " <span style='color:black'>$avg/5</span>";
      } 
      //if no rating returned show a message
      else echo " <span style='color:black'>No Rating</span>";
      ?>

    </h2>

    <h3 style="text-align: center"><u>Reviews</u></h3>
    <!-- section for displaying the reviews -->
    <div id="prevReviews">
      <?php
      //include 'connect.php' to be able to communicate with the database 
      include_once('connect.php');

      //get product id from url
      $curProdId = $_GET['Id'];
      //get all reviews for this product
      $stmt = $pdo->prepare("SELECT * FROM tbl_reviews WHERE product_id = '$curProdId'");
      $stmt->execute();
      $reviews = $stmt->fetchAll();

      //if there are no reviews display a message 
      if ($stmt->rowCount() == 0) echo "<p style='font-size: 2vw; text-align: center'>No Reviews Yet</p>";

      //go through each review
      foreach ($reviews as $review) {
        //get information about the review
        $revUserId = $review['user_id'];
        $revTitle = $review['review_title'];
        $revDesc = $review['review_desc'];
        $revRating = $review['review_rating'];
        $revTime = $review['review_timestamp'];
      ?>
        <!-- html code to display the reviews -->
        <div class="review-box">
          <h2><u><?php echo $revTitle; ?></u></h2>
          <div class="reviewInfo-box">
            <h3>User Id: <?php echo $revUserId; ?></h3>
            <p>Description: <?php echo $revDesc; ?></p>
            <p>Rating: <strong><?php echo $revRating; ?></strong></p>
            <p>Time of submit: <?php echo $revTime; ?></p>
          </div>
        </div>
      <?php
      }
      ?>

    </div>
    <div id="review">
      <!-- review form -->
      <form id="reviewForm" name="reviewForm" action="" method="POST">
        <h2><u>Leave Your Review!!</u></h2>
        <label>Title:<br> <input type="text" name="reviewTitle" placeholder="give it a title"></label>
        <label>Description:<br> <input type="text" name="reviewDesc" placeholder="leave your review"></label>
        <label>Rating:<br> <input type="number" name="reviewRating" min="0" max="5" placeholder="5"></label>
        <button type="submit" id="reviewSubmitBtn" name="reviewSubmit" onclick="return validateReview()">Submit</button>
      </form>
    </div>

    <?php
    //if the user isn't logged in display a message
    if (empty($_SESSION)) {
      echo "<p style='font-size: 2vw; margin-bottom: 7%'>Please login to leave a review</p>";
    }
    ?>

  </main>

  <?php
  //include 'connect.php' to be able to communicate with the database
  include('connect.php');

  //if the user isn't logged in hide the review form
  if (empty($_SESSION)) {
    echo '<script>
    document.getElementById("review").style.display = "none";
    </script>';
  } else {
    //else show it
    echo '<script>
    document.getElementById("review").style.display = "block";
    </script>';
  }

  //if the user submits a review
  if (isset($_POST['reviewSubmit'])) {
    //get the users name from the session value
    $username = $_SESSION['name'];
    //get all submited values
    $curProdId = $_GET['Id'];
    $revTitle = $_POST['reviewTitle'];
    $revDesc = $_POST['reviewDesc'];
    $revRating = $_POST['reviewRating'];
    $date = date("Y/m/d h:i:s"); //get systems date and time in specified format

    //get the users id form the database with the same name
    $sql = $pdo->prepare("SELECT user_id from tbl_users WHERE user_full_name = '$username';");
    $sql->execute();
    $usrId = $sql->fetch()[0]; //get [0] to avoid duplicates

    //get all reviews from the database where the user id is the same as the current users and the product id is the same as the current product
    $sql = $pdo->prepare("SELECT * from tbl_reviews WHERE user_id = '$usrId' AND product_id = '$curProdId';");
    $sql->execute();

    //if there was no review returned
    if ($sql->rowCount() == 0) {
      //send the review to the database
      $sql = $pdo->prepare("INSERT into tbl_reviews( user_id, product_id, review_title, review_desc, review_rating, review_timestamp) 
      VALUES (:usrId,:prodId,:revT,:revD,:rating,:revTime );");
      //bind all parameters to the submitted values
      $sql->bindParam(':usrId', $usrId);
      $sql->bindParam(':prodId', $curProdId);
      $sql->bindParam(':revT', $revTitle);
      $sql->bindParam(':revD', $revDesc);
      $sql->bindParam(':rating', $revRating);
      $sql->bindParam(':revTime', $date);
      $sql->execute();
      echo '<script>alert("Review submited.");</script>';
    } else {
      //if there was already a review from the current user overwrite it with the new information 
      echo '<script>alert("Updated existing review.");</script>';
      $sql = $pdo->prepare("UPDATE tbl_reviews SET user_id=:usrId, product_id=:prodId, review_title=:revT, review_desc=:revD, review_rating=:rating, review_timestamp=:revTime WHERE user_id = '$usrId' AND product_id = '$curProdId';");
      $sql->bindParam(':usrId', $usrId);
      $sql->bindParam(':prodId', $curProdId);
      $sql->bindParam(':revT', $revTitle);
      $sql->bindParam(':revD', $revDesc);
      $sql->bindParam(':rating', $revRating);
      $sql->bindParam(':revTime', $date);
      $sql->execute();
    }
  }
  ?>

</body>

</html>