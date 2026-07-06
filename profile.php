<?php
include 'includes/header.php';

// $patient_email = $_SESSION['pemail'];
$patient_id = $_SESSION['pid'];
$qry = "SELECT * FROM patients WHERE pid = '$patient_id'";
$res = mysqli_query($conn, $qry);
$user = mysqli_fetch_assoc($res);

if (isset($_GET["delete"])) {
    $pid = $_GET["delete"];
    $conn->query("DELETE FROM reports WHERE pid = $pid");
    $conn->query("DELETE FROM appointments WHERE pid = $pid");
    $conn->query("DELETE FROM patients WHERE pid=$pid");
    session_destroy();
    header("Location: login.php");
}

?>

<main class="p-6 max-w-3xl mx-auto pt-20">
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
        <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">My Profile</h2>
        <p class = "text-gray-700 mb-4"><strong>Patient ID:</strong> <?php echo htmlspecialchars($user['pid']); ?></p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['pname']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['pgender']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['pdob']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['pphone']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['paddress']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['pemail']); ?></p>
            
            <?php $passlenghth = strlen($user['ppassword']); ?>
            <p><strong>Password:</strong> <?php echo str_repeat('*', $passlenghth); ?></p>

        </div>

        <div class="text-right mt-6">
            <a href="edit_profile.php" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">Edit Profile</a>
            <a href="profile.php?delete=<?php echo $user['pid']; ?>" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition" onclick="return confirm('Your all appointmetns will also be delete! Are you sure to delete?')">Delete Account</a>
        </div>
    </div>
</main>
 

<?php include 'includes/footer.php'; ?>