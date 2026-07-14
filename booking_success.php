<?php
include 'configs/db.php';
include 'includes/header.php'; 

$aid = $_SESSION['appointment_id'] ?? null;
$payment_query = "SELECT * FROM payments WHERE appointment_id = '$aid'";
$result = $conn->query($payment_query);
$payment = mysqli_fetch_assoc($result);


?>

<div class="w-96 mx-auto mt-20 bg-white p-6 rounded shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    <div class="text-center text-green-500 mb-4">
        <i class="fa-solid fa-circle-check fa-8x"></i>
    </div>
    <h2 class="text-3xl text-center font-bold text-blue-900 mb-4">Payment Successful</h2>
    <p class="text-center">You booked a appoinmnet for <?php echo $_SESSION['appointment_date']; ?> </p>
    <p class="text-center mb-4">Your token number is: <?php echo $_SESSION['token_number']; ?> </p>
    <div class="flex justify-center mt-4 mb-4">
                <a href="api/download_reciept.php?pmid=<?php echo $payment['pmid']; ?>" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"><i class="fas fa-file-download mr-2"></i>
                Download Reciept
                </a>
            </div> 
    <a href="view_appointments.php" class="bg-pink-500 text-white w-full px-4 py-2 rounded hover:bg-pink-600 block text-center">View Appointments</a>
</div>


