<?php

include 'configs/db.php';
include 'includes/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    $_SESSION['email'] = $email;
    $_SESSION['otp'] = $otp;

    $patient_check = mysqli_query($conn, "SELECT * FROM patients WHERE pemail = '$email'");
    if (mysqli_num_rows($patient_check) == 0) {
        $error = "Email not registered.";
    } else {
        $otp_check = mysqli_query($conn, "SELECT * FROM patients WHERE pemail = '$email' AND otp_verified = 0");
        if (mysqli_num_rows($otp_check) == 0) {
            $error = "Email already verified.";
        } else {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'contact.ohcms@gmail.com';
                $mail->Password = 'ipkh fvbc wura kkeq';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('contact.ohcms@gmail.com', 'Health Care System');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body    = "<h3>Your OTP code is <strong>$otp</strong></h3>";

                $mail->send();

                // Redirect to verify OTP page
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                $error = "Mailer Error: " . $mail->ErrorInfo;
            }
        }
    }
}

?>

<form method="POST" class="w-96 mx-auto mt-20 bg-white p-6 rounded shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Enter Email</h2>

    <?php if ($error): ?>
        <p class="text-red-500 text-center mb-4"><?= $error ?></p>
    <?php endif; ?>

    <div class="mb-4">
                <input type="email" name="email" id="email" required class="w-full px-3 py-2 border rounded-md" placeholder="Email">
                <span id="email-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>
    <button type="submit" class="bg-pink-500 text-white w-full py-2 rounded hover:bg-pink-600">Get OTP</button>
</form>

<script>
    function showError(field, message) {
        document.getElementById(field + "-error").textContent = message;
    }

    function clearError(field) {
        document.getElementById(field + "-error").textContent = "";
    }

    document.getElementById("email").addEventListener("input", function () {
        const email = this.value;
        const emailRegex = /^[^\d\s][\w.]+@[a-zA-Z\d.]+\.[a-zA-Z]{2,}$/;
        if (email.includes('-') || !emailRegex.test(email)) {
            showError("email", "Enter a valid email without hyphens or spaces.");
        } else {
            clearError("email");
        }
    });
</script>