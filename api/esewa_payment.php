<?php

session_start();
include '../configs/db.php';

// Check if the user is logged in
$u_name = "";
if (isset($_SESSION['pemail'])) {
    $u_username = $_SESSION['pemail'];
    $qry = "SELECT * FROM patients WHERE pemail = '$u_username'";
    $result = mysqli_query($conn, $qry);
    $row = mysqli_fetch_assoc($result);
    
    if ($row) {
        $u_name = $row['pname'];
    }
}

$data = $_SESSION['appointment'];

$fee = $data['fee'];
$tax = 0;
$total = $fee + $tax;

$uuid = $_SESSION['transaction_uuid'] ?? uniqid();
$success_url = "http://localhost/Hospital_Management_System/api/esewa_success.php";
$failure_url = "https://developer.esewa.com.np/failure";
$secret_key = "8gBm/:&EnhH.1/q";

$payload = "total_amount=$total,transaction_uuid=$uuid,product_code=EPAYTEST";
$signature = base64_encode(hash_hmac('sha256', $payload, $secret_key, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Care System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/ae61999827.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style src="..\css\style.css"></style>


</head>

<body class="">


    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 w-full bg-blue-900 text-white py-4 z-50 transition-shadow">


        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="../index.php" class="text-2xl font-bold">Hospital Management</a>
            <ul class="flex space-x-6">
                <li><a href="../index.php" class="hover:text-blue-200">Home</a></li>
                <li><a href="../departments.php" class="hover:text-blue-200">Book Appointments</a></li>
                <li><a href="../services.php" class="hover:text-blue-200">Services</a></li>
                <li><a href="../contact.php" class="hover:text-blue-200">Contact</a></li>
                <li><a href="../about.php" class="hover:text-blue-200">About</a></li>
                <li><a href="../doctor/doctor_home.php" class="hover:text-blue-200">Doctor Home</a></li>
                <li><a href="../admin/dashboard.php" class="hover:text-blue-200">Admin</a></li>

                <?php if (isset($_SESSION['pemail'])) { ?>
                    <li><a href="../view_appointments.php" class="hover:text-blue-200">My Appointments</a></li>
                    <li><a href="../profile.php" class="hover:text-blue-200"><i class="fa-solid fa-user" ></i> <?php echo htmlspecialchars($u_name); ?></a></li>
                    <li><a href="../logout.php" class="hover:text-blue-200 text-red-500" onclick="return confirm('Are you sure to logout?')">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="hover:text-blue-200">Login</a></li>
                    <li><a href="register.php" class="hover:text-blue-200">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>

<form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
    <input type="hidden" name="amount" value="<?php echo $fee; ?>">
    <input type="hidden" name="tax_amount" value="<?php echo $tax; ?>">
    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
    <input type="hidden" name="transaction_uuid" value="<?php echo $uuid; ?>">
    <input type="hidden" name="product_code" value="EPAYTEST">
    <input type="hidden" name="product_service_charge" value="0">
    <input type="hidden" name="product_delivery_charge" value="0">
    <input type="hidden" name="success_url" value="<?php echo $success_url; ?>">
    <input type="hidden" name="failure_url" value="<?php echo $failure_url; ?>">
    <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
    <input type="hidden" name="signature" value="<?php echo $signature; ?>">

    <div class="w-96 mx-auto mt-20 bg-white p-6 rounded shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300">
        <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Choose Payment Method</h2>
        <img src="../images/eSewa.png" alt="eSewa" class="w-24 h-24 mb-3 rounded-xl border-2 border-blue-200 item-center mx-auto">
        <p class="text-center mb-4">Total: <strong class="text-blue-900">Rs. <?php echo $fee; ?></strong></p>
        <button type="submit" class="bg-pink-500 text-white w-full py-2 rounded hover:bg-pink-600">Pay with eSewa</button>
    </div>

</form>

<?php
    include '../includes/footer.php';
?>

