<?php
include 'Connection.php';
session_start();
session_regenerate_id();

// Include PHPMailer files
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send a welcome email
function sendWelcomeEmail($to, $name)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'thewardrobearvs@gmail.com';
        $mail->Password   = 'rbxxakuqegvoemhm';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('thewardrobearvs@gmail.com', 'TheWardrobe');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to TheWardrobe!';
        $mail->Body    = 'Thank you for registering, ' . $name . '! We are glad to have you with us.';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// User creation
if (isset($_POST['btnreg'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $type = $_POST['type'];
    $passwordhash = password_hash($password, PASSWORD_DEFAULT);

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm)) {
        echo "<script type='text/javascript'>alert('Fields cannot be empty');";
        echo "window.location.href = 'register.php';</script>";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script type='text/javascript'>alert('Enter a valid Email');";
        echo "window.location.href = 'register.php';</script>";
        exit;
    }
    $phonePattern = '/^\d{10}$/';
    if (!preg_match($phonePattern, $phone)) {
        echo "<script type='text/javascript'>alert('Enter a valid Phone Number');";
        echo "window.location.href = 'register.php';</script>";
        exit;
    }
    if (strlen($password) < 8) {
        echo "<script type='text/javascript'>alert('Password must be at least 8 characters long');";
        echo "window.location.href = 'register.php';</script>";
        exit;
    }
    if ($password !== $confirm) {
        echo "<script type='text/javascript'>alert('Password and confirm password do not match');";
        echo "window.location.href = 'register.php';</script>";
        exit;
    }

    $sql = "SELECT * FROM user WHERE Username=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rowCount = mysqli_num_rows($result);

    if ($rowCount > 0) {
        echo "<script type='text/javascript'>alert('Username already exists');";
        echo "window.location.href = 'register.php';</script>";
        exit;
    } else {
        $sql = "INSERT INTO user (UserType, Username, Email, PhoneNumber, Password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $type, $name, $email, $phone, $passwordhash);
        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            die('Could not enter data: ' . mysqli_error($conn));
            exit;
        } else {
            // Send the welcome email
            sendWelcomeEmail($email, $name);
            echo "<script type='text/javascript'>alert('Account created');";
            echo "window.location.href = 'login.php';</script>";
            exit;
        }
        mysqli_stmt_close($stmt);
    }
}

// Login
if (isset($_POST['btnlogin'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM user WHERE Username=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $loginSuccessful = false;

    while ($row = mysqli_fetch_assoc($result)) {
        $hashedPasswordFromDB = $row['Password'];
        if (password_verify($password, $hashedPasswordFromDB)) {
            $loginSuccessful = true;
            $resultUser = $row['UserType'];
            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['user_type'] = $resultUser;
            $_SESSION['user_name'] = $row['Username'];
            if ($resultUser == "Admin") {
                $_SESSION["Admin"] = true;
                header("location: Admin dashboard/index.php");
                die();
            } elseif ($resultUser == "Customer") {
                $_SESSION["Customer"] = true;
                header("location: home.php");
                exit();
            }
        }
    }

    if (!$loginSuccessful) {
        echo "<script type='text/javascript'>alert('Login unsuccessful');";
        echo "window.location.href = 'login.php';</script>";
        exit;
    }
}
