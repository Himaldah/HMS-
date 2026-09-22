<?php
include 'configs/db.php';
include 'includes/header.php';


$otp = $_SESSION['otp'] ?? null;
$email = $_SESSION['email'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_input = $_POST['email'];
    $otp_input = $_POST['otp'];

    // Sanitize input
    // $email_input = mysqli_real_escape_string($conn, $email_input);
    // $otp_input = mysqli_real_escape_string($conn, $otp_input);

    $qry = mysqli_query($conn, "SELECT * FROM patients WHERE pemail = '$email_input' AND otp_verified = 0");

    if (mysqli_num_rows($qry) == 1) {
        if ($otp_input == $otp) {
            mysqli_query($conn, "UPDATE patients SET otp_verified = 1, otp_code = NULL WHERE pemail = '$email_input'");
            unset($_SESSION['otp']);
            unset($_SESSION['email']);
            echo "<script>alert('Email verified successfully!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Invalid OTP.');</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP or email not found.');</script>";
    }
}
?>

<form method="POST" class="w-96 mx-auto mt-20 bg-white p-6 rounded shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Verify OTP</h2>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
    <input type="number" name="otp" class="w-full p-2 border rounded mb-4" placeholder="Enter OTP" required>
    <button type="submit" class="bg-pink-500 text-white w-full py-2 rounded hover:bg-pink-600">Verify</button>
</form>
