<?php
include 'configs/db.php';
include 'includes/header.php';

?>

<div class="w-96 mx-auto mt-20 bg-white p-6 rounded shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    <div class="text-center text-red-500 mb-4">
        <i class="fa-solid fa-circle-xmark fa-8x"></i>
    </div>
    <h2 class="text-3xl text-center font-bold text-blue-900 mb-4">Payment Failed</h2>
    <!-- <p class="text-center mb-4">Your Token Number is: </p> -->
    <a href="departments.php" class="bg-pink-500 text-white w-full px-4 py-2 rounded hover:bg-pink-600 block text-center">Try Again</a>
</div>


