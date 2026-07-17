<?php
session_start(); // Needed to access session variables
if (isset($_SESSION['doctor_loggedin'])) {
    include 'includes/header.php'; // Only include if already logged in
    header("Location: doctor_home.php"); // Optional: redirect if already logged in
    exit();
}
include '../configs/db.php'; // Ensure DB connection
?>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // $encrypted_u_password = md5($u_password);
        // $encrypted_u_password = $u_password;
        $qry = "SELECT * FROM doctors WHERE dremail = '$email' AND drpassword = '$password' ";
        $result = mysqli_query($conn, $qry);
        if(mysqli_num_rows($result) == 1)
        {
            $_SESSION['doctor_loggedin'] = true;
            $_SESSION['dremail'] = $email;
            $doctor = mysqli_fetch_assoc($result);
            $_SESSION['drid'] = $doctor['drid'];
            // $_SESSION['pid'] = $patient['pid'];
            // $_SESSION['pname'] = $patient['pname'];
            echo '<script type="text/javascript"> alert("Logged in Successfully!"); window.location.assign("doctor_home.php");</script>';
            exit();
        }
        else
        {
            echo '<script type="text/javascript"> alert("Invalid Credentials!");</script>';
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-white pt-20">

    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 w-full bg-blue-900 text-white py-4 z-50 transition-shadow">


        <div class="container mx-auto flex justify-between items-center px-6">  
            <a href="../index.php" class="text-2xl font-bold">HealthCare</a>
            <ul class="flex space-x-6">
                <!-- <li><a href="index.php" class="hover:underline">Home</a></li> -->
                <!-- <li><a href="departments.php" class="hover:underline">Book Appointments</a></li> -->
                <!-- <li><a href="services.php" class="hover:underline">Services</a></li> -->
                <!-- <li><a href="contact.php" class="hover:underline">Contact</a></li> -->
                <!-- <li><a href="about.php" class="hover:underline">About</a></li> -->
                <!-- <li><a href="doctor_profile.php" class="hover:underline">Doctor Profile</a></li> -->

                <?php if (isset($_SESSION['dremail'])) { ?>
                    <li><a href="doctor_home.php" class="hover:text-blue-200">Home</a></li>
                    <li><a href="app_schedules.php" class="hover:text-blue-200">My Appointments</a></li>
                    <li><a href="set_availability.php" class="hover:text-blue-200">Set Availability</a></li>
                    <li><a href="profile.php" class="hover:text-blue-200"><i class="fa-solid fa-user" ></i> <?php echo htmlspecialchars($drname); ?></a></li>
                    <li><a href="logout.php" class="hover:text-blue-200 text-red-400">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="hover:text-blue-200">Login</a></li>
                    <!-- <li><a href="register.php" class="hover:underline">Register</a></li> -->
                <?php } ?>
            </ul>
        </div>
    </nav>


<div class="bg-white p-8 rounded-lg shadow-md w-96 mx-auto mt-10">
    <h2 class="text-2xl font-bold text-center mb-6">Doctor Login</h2>
        
    <?php if(isset($error)): ?>
        <p class="text-red-500 text-sm text-center"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="" name="login" onsubmit="return validateForm()">
        <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="w-full px-3 py-2 border rounded-md">
                <span id="email-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>
        <div class="mb-4 relative">
            <label class="block text-gray-700">Password</label>
            <input type="password" id="passwordField" name="password" required class="w-full px-3 py-2 border rounded-md">
            <span onclick="togglePassword()" class="absolute right-3 top-8 cursor-pointer text-gray-500 hover:text-gray-700">
                <i id="eyeIcon" class="fas fa-eye"></i>
            </span>
        </div>
        
        <button type="submit" class="w-full bg-pink-500 text-white p-2 rounded hover:bg-pink-600">Login</button>
        <!-- <p class="text-center mt-3">Don't have an account? <a href="register.php" class="text-blue-500">Register</a> -->
        </p>
    </form>
</div>

<!-- <?php include 'includes/footer.php'; ?> -->


<script>
    function showError(field, message) {
        document.getElementById(field + "-error").textContent = message;
    }

    function clearError(field) {
        document.getElementById(field + "-error").textContent = "";
    }

    document.getElementById("email").addEventListener("input", function () {
        const email = this.value;
        const emailRegex = /^[^\d\s][\w.]+@[a-zA-Z\d.]+\.[a-zA-Z]{2,}$/;
        if (email.includes('-') || !emailRegex.test(email)) {
            showError("email", "Enter a valid email (no hyphens or spaces, and must start with a letter or number).");
        } else {
            clearError("email");
        }
    });

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
