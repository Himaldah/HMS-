<?php
include 'includes/header.php';

// $patient_email = $_SESSION['pemail'];
$patient_id = $_SESSION['pid'];
$qry = "SELECT * FROM patients WHERE pid = '$patient_id'";
$res = mysqli_query($conn, $qry);
$user = mysqli_fetch_assoc($res);

// Update logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['pname'];
    $gender = $_POST['pgender'];
    $dob = $_POST['pdob'];
    $phone = $_POST['pphone'];
    $address = $_POST['paddress'];
    $password = $_POST['ppassword'];
    $email = $_POST['pemail'];

    $update = "UPDATE patients SET pname = '$name', pphone = '$phone', paddress = '$address', pdob = '$dob', pgender = '$gender', pemail = '$email', ppassword = '$password' WHERE pid = '$patient_id'";
    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Profile updated!'); window.location='profile.php';</script>";
    } else {
        echo "<p class='text-red-500'>Error updating profile.</p>";
    }
}
?>

<main class="p-6 max-w-3xl mx-auto pt-20">
    <form method="POST" class="bg-white rounded-lg shadow p-6 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
        <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Edit Profile</h2>

        <div class="mb-4">
            <label class="block text-gray-700">Name</label>
            <input type="text" name="pname" value="<?php echo htmlspecialchars($user['pname']); ?>" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Gender</label>
            <select name="pgender" class="w-full p-2 border rounded">
                <option value="Male" <?php if($user['pgender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($user['pgender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if($user['pgender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Date of Birth</label>
            <input type="date" name="pdob" value="<?php echo htmlspecialchars($user['pdob']); ?>" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Phone</label>
            <input type="text" name="pphone" value="<?php echo htmlspecialchars($user['pphone']); ?>" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Address</label>
            <input type="text" name="paddress" value="<?php echo htmlspecialchars($user['paddress']); ?>" required class="w-full px-3 py-2 border rounded-md">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="pemail" value="<?php echo htmlspecialchars($user['pemail']); ?>" class="w-full p-2 border rounded">
        </div>

        <div class="mb-4 relative">
            <label class="block text-gray-700">Password</label>
            <input type="password" id="passwordField" name="ppassword" value="<?php echo htmlspecialchars($user['ppassword']); ?>" class="w-full p-2 border rounded pr-10">
            <span onclick="togglePassword()" class="absolute right-3 top-9 cursor-pointer text-gray-500 hover:text-gray-700">
                <i id="eyeIcon" class="fas fa-eye"></i>
            </span>
        </div>

        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">Save Changes</button>
    </form>
    
    <script>
function togglePassword() {
    const input = document.getElementById("passwordField");
    const icon = document.getElementById("eyeIcon");
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</main>

<?php include 'includes/footer.php'; ?>