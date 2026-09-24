<?php
include 'configs/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp_input = $_POST['otp'];

    $qry = mysqli_query($conn, "SELECT * FROM patients WHERE pemail = '$email' AND otp_code = '$otp_input' AND otp_verified = 0");
    
    if (mysqli_num_rows($qry) == 1) {
        mysqli_query($conn, "UPDATE patients SET otp_verified = 1, otp_code = NULL WHERE pemail = '$email'");
        echo "<script>alert('Email verified successfully!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Invalid OTP.');</script>";
    }
}
?>

<form method="POST" class="w-96 mx-auto mt-20 bg-white p-6 rounded shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Choose Payment Method</h2>
    <button type="submit" class="bg-pink-500 text-white w-full py-2 rounded hover:bg-pink-600">Esewa</button>
</form>
