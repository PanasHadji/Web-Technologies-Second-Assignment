<!DOCTYPE html>
<html lang="en">

<head>
  <!-- importing an external css file for styling -->
  <link rel="stylesheet" href="index.css" />
</head>

<body>
  <!-- display an image for the 404 error and an anchor tag to go back home -->
  <?php
  echo "<img id='errImg' alt='404 Error Message' src='errorPage.jpg'/>
  <a id='goHome' href='index.php'><strong>Go Back Home!!</strong></a>";
  ?>
</body>