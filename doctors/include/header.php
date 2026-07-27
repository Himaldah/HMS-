<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/ae61999827.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="">

    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 w-full bg-blue-900 text-white py-4 z-50 transition-shadow">


        <div class="container mx-auto flex justify-between items-center px-6">  
            <a href="../index.php" class="text-2xl font-bold">Hospital Management</a>
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
                    <li><a href="logout.php" class="hover:text-blue-200 text-red-400" onclick="return confirm('Are you sure to logout?')">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="hover:text-blue-200">Login</a></li>
                    <!-- <li><a href="register.php" class="hover:underline">Register</a></li> -->
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
