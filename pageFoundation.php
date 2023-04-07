<!-- This page contains all common page elements 
(header, footer, signup/login/logout modals and their php code) -->
<?php
// start a session when the page is loaded
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Union Shop at UCLAN</title>

  <!-- importing an external css file for styling and a js file for the menu button functionality -->
  <link rel="stylesheet" href="index.css" />
  <script src="index.js" defer></script>
</head>

<body>
  <!--Page Header-->
  <header>
    <section id="header-content">
      <!--Uclan Logo image-->
      <img id="uclanLogo" src="UclanLogo.png" alt="UCLAN logo image" />

      <!--header with  shop name-->
      <h2>Student Shop</h2>

      <!--Navigation menu (desktop and tablet view)-->
      <nav class="navMenu">
        <a id="loginDesk" onclick="openModal('in')">SignUp/Login</a>
        <a id="logoutDesk" onclick="openModal('out')">Logout</a>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
      </nav>

      <!--Hamburger menu (phone view)-->
      <div class="dropdown">
        <input class="dropbtn" type="image" src="navIcon.png" alt="Navigation menu icon" onclick="toggleMenu()" />
      </div>
    </section>

    <!--Navigation menu (appear when hamburger menu is pressed)-->
    <nav id="dropdown-content">
      <a id="loginPhone" onclick="openModal('in')">SignUp / Login</a>
      <a id="logoutPhone" onclick="openModal('out')">Logout</a>
      <a href="index.php">Home</a>
      <a href="products.php">Products</a>
      <a href="cart.php">Cart</a>
    </nav>
  </header>

  <?php
  //include connect.php to be able to iteract with the database
  include('connect.php');

  //if a user is logged in (there is a session) show appropriate elements
  if (empty($_SESSION)) {
    echo '<script>
    document.getElementById("loginDesk").style.display = "block";
    document.getElementById("loginPhone").style.display = "block";
    document.getElementById("logoutDesk").style.display = "none";
    document.getElementById("logoutPhone").style.display = "none";
    </script>';
  }
  //if a user is not logged in (there isn't a session) show appropriate elements
  else {
    echo '<script>
    document.getElementById("loginDesk").style.display = "none";
    document.getElementById("loginPhone").style.display = "none";
    document.getElementById("logoutDesk").style.display = "block";
    document.getElementById("logoutPhone").style.display = "block";
    </script>';
  }

  //if the user submits the login inforamtion and there is no session
  if (isset($_POST['loginSubmit']) && empty($_SESSION)) {
    //get submitted values for name and password
    $username = $_POST['Luser'];
    $password = $_POST['Lpass'];

    //find the hash value of that password
    $hash = password_hash($password, PASSWORD_BCRYPT);

    //get the password of the user with the submited name from the database
    $sql = $pdo->prepare("SELECT user_pass from tbl_users WHERE user_full_name = '$username'");
    $sql->execute();

    //if the database return something
    if ($sql->rowCount() > 0) {
      //get user password
      $userPass = $sql->fetch();

      //compare hashed value with password in the database, and if they match
      if (password_verify($password, $userPass['user_pass'])) {
        //set a SESSION value 'name' to the users name and refresh the page
        $_SESSION["name"] = $username;
        echo '<script>
        alert("Login successful.");
        parent.window.location.reload(true);
        </script>';
      }
      //else display an error message
      else {
        echo '<script>alert("Login failed. Invalid username or password.")</script>';
      }
    }
    //if passwords dont match display an error message
    else echo '<script>alert("User does not exist.")</script>';
  }

  //if the user submits the signUp information
  if (isset($_POST['signUpSubmit'])) {
    //get all submitted values
    $username = $_POST['Suser'];
    $password = $_POST['Spass'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $date = date("Y/m/d h:i:s"); //get systems date and time is specified format

    //hash the inputed password
    $hash = password_hash($password, PASSWORD_BCRYPT);

    //get a name from the database that is the same as the users input name
    $sql = $pdo->prepare("SELECT user_full_name from tbl_users WHERE user_full_name = '$username'");
    $sql->execute();

    //if the database returned a name display that a user with this name already exists
    if ($sql->rowCount() > 0) {
      echo '<script>alert("User already exists.")</script>';
    } else {
      //else insert all the information into the database
      $sql = $pdo->prepare("INSERT into tbl_users(user_full_name,user_address,user_email,user_pass,user_timestamp) 
      VALUES (:name,:address,:email,:pass,:time );");
      //binding all the parameters to the inputed values
      $sql->bindParam(':name', $username);
      $sql->bindParam(':pass', $hash);
      $sql->bindParam(':address', $address);
      $sql->bindParam(':email', $email);
      $sql->bindParam(':time', $date);
      $sql->execute();
      echo '<script>alert("User added.")</script>';
    }
  }

  //if the user submited the logout form and there is an existing session
  if (isset($_POST['logoutSubmit']) && !empty($_SESSION)) {
    //unset the session values and destroy the session
    session_unset();
    session_destroy();
    //dispaly a message and reload the page
    echo '<script>
        alert("Logout successful.");
        parent.window.location.reload(true);
        </script>';
  }

  ?>

  <!-- modal for signing up and logging in  -->
  <div id="modalIn" class="modal">
    <div class="modal-content">
      <!-- 'x' for closing the modal -->
      <span class="close" onclick="closeModal()">&times;</span>

      <!-- form for signing up -->
      <form id="signupForm" name="f1" action="" onsubmit="return validation('signup')" method="POST">
        <h1>Create An Account</h1>
        <p>
          <label> UserName:<br>
            <input type="text" id="Suser" name="Suser" /></label>
        </p>
        <p>
          <label> Address:<br>
            <input type="text" id="address" name="address" /></label>
        </p>
        <p>
          <label> Email:<br>
            <input type="email" id="email" name="email" /></label>
        </p>
        <p>
          <label> Password:<br>
            <input type="password" id="Spass" name="Spass" /></label>
        </p>
        <!-- display password criteria when user is typing a password -->
        <div id="message">
          <h3>Password must contain the following:</h3>
          <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
          <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
          <p id="number" class="invalid">A <b>number</b></p>
          <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>
        <p>
          <label>Confirm Password:<br>
            <input type="password" id="confirmPass" name="confirmPass" /></label>
        </p>
        <p>
          <input type="submit" name="signUpSubmit" value="Submit" />
        </p>
      </form>

      <!-- form for logging in -->
      <form id="loginForm" name="f2" action="" onsubmit="return validation('login')" method="POST">
        <h1>Login To An Existing Account </h1>
        <p>
          <label> UserName:<br>
            <input type="text" id="Luser" name="Luser" /></label>
        </p>
        <p>
          <label> Password:<br>
            <input type="password" id="Lpass" name="Lpass" /></label>
        </p>
        <p>
          <input type="submit" name="loginSubmit" value="Submit" />
        </p>
      </form>

      <label id="sign-btn">Don't have an account? <button onclick="toggleLogin('signup')">SignUp</button></label>
      <label id="log-btn">Already have an account? <button onclick="toggleLogin('login')">Login</button></label>
    </div>
  </div>

  <!-- modal for logging out  -->
  <div id="modalOut" class="modal">
    <div id="logoutModalContent" class="modal-content">
      <!-- 'x' for closing the modal -->
      <span class="close" onclick="closeModal()">&times;</span>

      <!-- form for logging out -->
      <form id="logoutForm" name="f3" action="" method="POST">
        <p>Are You sure you want to logout?</p>
        <button type="submit" name="logoutSubmit">Yes</button>
        </p>
      </form>

    </div>
  </div>

  <!--Page Footer-->
  <footer>

    <!--three sections with headers and paragraphs below them-->
    <section class="colum">
      <h2>Links</h2>

      <p><a href="#">Student Union</a></p>
    </section>
    <section class="colum">
      <h2>Contact</h2>

      <p>Email: suinformation@uclan.ac.uk</p>
      <p>Phone: 01772 89 3000</p>
    </section>
    <section class="colum">
      <h2>Location</h2>

      <p>
        University of Central Lancashire Students' Union. Fylde Road, Preston.
        PR1 7BY Registered in England Company Number: 762317 Registered
        Charity Number: 1142616
      </p>
    </section>
  </footer>
</body>

</html>