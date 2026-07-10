<?php
include 'includes/header.php';
include 'api/notification.php';

// $patient_email = $_SESSION['pemail'];
// $drid = $_SESSION['drid'];
$drid = $_GET['drid'];
$qry = "SELECT * FROM doctors WHERE drid = '$drid'";
$res = mysqli_query($conn, $qry);
$doctor = mysqli_fetch_assoc($res);
$did = $doctor['did'];
$qry2 = "SELECT * FROM departments WHERE did = '$did'";
$res2 = mysqli_query($conn, $qry2);
$department = mysqli_fetch_assoc($res2);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = intval($_POST['rating']);
    $pid = $_SESSION['pid'];
    $drid = $_GET['drid'];

    $check = mysqli_query($conn, "SELECT * FROM ratings WHERE drid='$drid' AND pid='$pid'");
    if (mysqli_num_rows($check) == 0) {
        $insert = mysqli_query($conn, "INSERT INTO ratings (drid, pid, rating) VALUES ('$drid', '$pid', '$rating')");
        if ($insert) {
            // $_SESSION['toast-2'] = ['message' => 'Thank you for rating.', 'type' => 'success'];
            echo "<script>alert('Thank you for rating.');</script>";

        } else {
            // $_SESSION['toast-2'] = ['message' => 'Failed to submit rating.', 'type' => 'error'];
            echo "<script>alert('Failed to submit rating.');</script>";
        }
    } else {
        // $_SESSION['toast-2'] = ['message' => 'You have already rated this doctor.', 'type' => 'error'];
        echo "<script>alert('You have already rated this doctor!');</script>";
    }
}

?>

<main class="p-6 max-w-3xl mx-auto pt-20">
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
        <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Doctor Profile</h2>
        <img src="admin/<?php echo htmlspecialchars($doctor['drprofile']); ?>" alt="Doctor Profile" class="w-24 h-24 mb-3 rounded-xl border-2 border-blue-200">
        <p class = "text-gray-700 mb-4"><strong>Doctor ID:</strong> <?php echo htmlspecialchars($doctor['drid']); ?></p>
        <p class = "text-gray-700 mb-4"><strong>Department:</strong> <?php echo htmlspecialchars($department['dname']); ?> (<?php echo htmlspecialchars($department['ddescription']); ?>)</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($doctor['drname']); ?></p>
            <!-- <p><strong>Gender:</strong> <?php echo htmlspecialchars($doctor['drprofile']); ?></p> -->
            <!-- <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($doctor['pdob']); ?></p> -->
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['drphone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['dremail']); ?></p>
            <!-- <p><strong>Password:</strong> <?php echo htmlspecialchars($doctor['drpassword']); ?></p> -->

        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Rate this doctor:</h3>
            <form method="POST" action="">
                <label for="rating" class="block mb-2 text-sm text-gray-600">Select a rating:</label>
                <select name="rating" id="rating" class="border rounded px-3 py-2 text-yellow-500">
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="1">★☆☆☆☆</option>
                </select>
                <button type="submit" name="submit" class="ml-4 bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                Submit Rating
                </button>
            </form>
        </div>


        <div class="text-right mt-6">
            <!-- <a href="edit_profile.php" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">Edit Profile</a> -->
        </div>
    </div>
</main>
 
<?php include 'includes/footer.php'; ?>