<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(E_ALL); 
include("connection/connect.php");

$usernameError = $emailError = $phoneError = $passwordError = $cpasswordError = $addressError = "";

if(isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($db, trim($_POST['username']));
    $email = mysqli_real_escape_string($db, trim($_POST['email']));
    $phone = mysqli_real_escape_string($db, trim($_POST['phone']));
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $address = mysqli_real_escape_string($db, trim($_POST['address']));

    $errors = false;

    if(empty($username)) {
        $usernameError = "Please enter a valid name";
        $errors = true;
    } elseif(!preg_match("/^[a-zA-Z-'\s]*$/", $username)) {
        $usernameError = "Only letters and white spaces are allowed in the username";
        $errors = true;
    } else {
        $username_query = mysqli_query($db, "SELECT username FROM users WHERE username = '$username'");
        if(mysqli_num_rows($username_query) > 0) {
            $usernameError = "Username already exists!";
            $errors = true;
        }
    }

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/i', $email)) {
        $emailError = "Invalid email address! Only Gmail addresses are allowed.";
        $errors = true;
    } else {
        $email_query = mysqli_query($db, "SELECT email FROM users WHERE email = '$email'");
        if(mysqli_num_rows($email_query) > 0) {
            $emailError = "Email already exists!";
            $errors = true;
        }
    }

    if (empty($phone) || !preg_match('/^9\d{9}$/', $phone)) {
        $phoneError = "Phone number must be a valid 10-digit number starting with 9!";
        $errors = true;
    }
    
    // if(empty($phone) || strlen($phone) < 10 || !is_numeric($phone)) {
    //     $phoneError = "Phone number must be a valid 10-digit number!";
    //     $errors = true;
    // }    

    if(empty($password) || strlen($password) < 6) {
        $passwordError = "Password must be at least 6 characters long!";
        $errors = true;
    } elseif($password != $cpassword) {
        $cpasswordError = "Passwords do not match!";
        $errors = true;
    }

    if(empty($address)) {
        $addressError = "Address is required";
        $errors = true;
    }

    if(!$errors) {
        $hashed_password = md5($password);
        $insert_query = "INSERT INTO users(username, email, phone, password, address) VALUES('$username', '$email', '$phone', '$hashed_password', '$address')";
        mysqli_query($db, $insert_query);
        echo "<script>alert('Registration successful! Redirecting to login page.');</script>";
        header("Location: login.php");
        exit();
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registration</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
<div style="background-image: url('images/img/pimg.jpg');">
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> 
                    <img class="img-rounded" src="images/icn.png" alt=""> 
                </a>
                <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="restaurants.php">Restaurants</a></li>
                        <?php
                            if(empty($_SESSION["user_id"])) {
                                echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>';
                                echo '<li class="nav-item"><a href="registration.php" class="nav-link active">Register</a></li>';
                            } else {
                                echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a></li>';
                                echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="page-wrapper">
        <section class="contact-page inner-page">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget">
                            <div class="widget-body">
                                <form action="" method="post">
                                <div class="form-group col-sm-12">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                                        <div class="error"><?php echo $usernameError; ?></div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                        <small class="form-text text-muted">Only Gmail addresses are allowed.</small>
                                        <div class="error"><?php echo $emailError; ?></div>
                                    </div>
                                    <div class="form-group col-sm-6">
    <label>Phone Number</label>
    <input type="tel" class="form-control" name="phone" 
           value="<?php echo htmlspecialchars($phone ?? ''); ?>" 
           maxlength="10" 
           pattern="9\d{9}" 
           title="Phone number must be a valid 10-digit number starting with 9." 
           required>
    <small class="form-text text-muted">Please enter a 10-digit phone number starting with 9.</small>
    <div class="error"><?php echo $phoneError; ?></div>
</div>

                                    <div class="form-group col-sm-6">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                        <div class="error"><?php echo $passwordError; ?></div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" name="cpassword" required>
                                        <div class="error"><?php echo $cpasswordError; ?></div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label>Delivery Address</label>
                                        <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                                        <div class="error"><?php echo $addressError; ?></div>
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-4">
                                            <p><input type="submit" value="Register" name="submit" class="btn theme-btn"></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
        </section>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>