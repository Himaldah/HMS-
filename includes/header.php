<?php
session_start();
include 'configs/db.php';

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
            <a href="index.php" class="text-2xl font-bold">Health Care</a>
            <ul class="flex space-x-6">
                <li><a href="index.php" class="hover:text-blue-200">Home</a></li>
                <li><a href="departments.php" class="hover:text-blue-200">Book Appointments</a></li>
                <li><a href="services.php" class="hover:text-blue-200">Services</a></li>
                <li><a href="contact.php" class="hover:text-blue-200">Contact</a></li>
                <li><a href="about.php" class="hover:text-blue-200">About</a></li>
                <li><a href="doctor/doctor_home.php" class="hover:text-blue-200">Doctor Home</a></li>
                <li><a href="admin/dashboard.php" class="hover:text-blue-200">Admin</a></li>

                <?php if (isset($_SESSION['pemail'])) { ?>
                    <li><a href="view_appointments.php" class="hover:text-blue-200">My Appointments</a></li>
                    <li><a href="profile.php" class="hover:text-blue-200"><i class="fa-solid fa-user" ></i> <?php echo htmlspecialchars($u_name); ?></a></li>
                    <li><a href="logout.php" class="hover:text-blue-200 text-red-500" onclick="return confirm('Are you sure to logout?')">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="hover:text-blue-200">Login</a></li>
                    <li><a href="register.php" class="hover:text-blue-200">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <!-- <script>
    const navbar = document.getElementById('navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
            navbar.classList.add('shadow-xl', 'opacity-100');
        } else {
            navbar.classList.remove('shadow-xl', 'opacity-0');
        }
    });
</script> -->




</body>

</html>
