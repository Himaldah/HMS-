<?php
include 'includes/header.php';

// $patient_email = $_SESSION['pemail'];
$drid = $_SESSION['drid'];
$qry = "SELECT * FROM doctors WHERE drid = '$drid'";
$res = mysqli_query($conn, $qry);
$doctor = mysqli_fetch_assoc($res);
?>

<main class="p-6 max-w-3xl mx-auto pt-20">
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">My Profile</h2>
        <p class = "text-gray-700 mb-4"><strong>Doctor ID:</strong> <?php echo htmlspecialchars($doctor['drid']); ?></p>
        <p class = "text-gray-700 mb-4"><strong>Department ID:</strong> <?php echo htmlspecialchars($doctor['did']); ?></p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($doctor['drname']); ?></p>
            <!-- <p><strong>Gender:</strong> <?php echo htmlspecialchars($doctor['drprofile']); ?></p> -->
            <!-- <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($doctor['pdob']); ?></p> -->
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['drphone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['dremail']); ?></p>
            <p><strong>Password:</strong> <?php echo htmlspecialchars($doctor['drpassword']); ?></p>

        </div>

        <div class="text-right mt-6">
            <a href="edit_profile.php" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">Edit Profile</a>
        </div>
    </div>
</main>
 