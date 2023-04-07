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

  <!--Page Main-->
  <main id="home-main">
    <h1 id="specialOffersHeader">Special Offers</h1>
    <div id="specialOffers">
      <?php
      //include 'connect.php' to be able to communicate with the database 
      include_once('connect.php');
      //get all special offers from the database
      $stmt = $pdo->prepare("SELECT * FROM tbl_offers");
      $stmt->execute();
      $offers = $stmt->fetchAll();

      //go through every offer and display it
      foreach ($offers as $offer) {
        //get offer title and description
        $offerTitle = $offer['offer_title'];
        $offerDec = $offer['offer_dec'];
      ?>
        <!-- html to display the offers -->
        <div class="offer">
          <h2><?php echo $offerTitle; ?></h2>
          <p><?php echo $offerDec; ?></p>
        </div>
      <?php
      }
      ?>

    </div>

    <!--header with paragraphs below it-->
    <h1>Where opportunity creates success</h1>
    <p>
      Every student at The University of Central Lancashire is automatically a
      member of the Students' Union. We're here to make life better for
      students - inspiring you to succeed and achieve your goals.
    </p>
    <p>
      Everything you need to know about Uclan Students' Union. Your membership
      starts here.
    </p>

    <!--header with html video below it-->
    <h2>Together</h2>
    <video class="video" controls>
      <source src="uclanOpenDay.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>

    <!--header with iframe video youtube embed below it-->
    <h2>Join our global community</h2>
    <iframe class="video" src="https://www.youtube.com/embed/i2CRunZv9CU">
    </iframe>
  </main>

</body>

</html>