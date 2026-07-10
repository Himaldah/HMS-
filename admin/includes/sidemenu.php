<?php
    session_start();
    ob_start();

    $currentPage = basename($_SERVER['PHP_SELF']);

    // include '../configs/db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-white">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-52 bg-blue-900 text-white fixed h-screen p-4">
            <h2 class="text-2xl font-bold mb-4">HMS</h2>
            <nav>
                <ul class="space-y-2">
                <?php if (isset($_SESSION['aemail'])) { ?>
                    <a href="dashboard.php">
                        <li class="<?= $currentPage === 'dashboard.php' ? 'bg-blue-700' : 'hover:bg-blue-700' ?> p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </li>
                    </a>
                    <a href="patients.php">
                        <li class="<?= $currentPage === 'patients.php' ? 'bg-blue-700' : 'hover:bg-blue-700' ?> p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-user-injured"></i> Patients
                        </li>
                    </a>
                    <a href="doctors.php">
                        <li class="<?= $currentPage === 'doctors.php' ? 'bg-blue-700' : 'hover:bg-blue-700' ?> p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-user-md"></i> Doctors
                        </li>
                    </a>
                    <a href="departments.php">
                        <li class="<?= $currentPage === 'departments.php' ? 'bg-blue-700' : 'hover:bg-blue-700' ?> p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-building"></i> Departments
                        </li>
                    </a>
                    <a href="fees.php">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-money-bill"></i> Fees
                        </li>
                    </a>
                    <a href="schedules.php">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i> Schedules
                        </li>
                    </a>
                    <a href="appointments.php">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-notes-medical"></i> Appointments
                        </li>
                    </a>
                    <a href="payments.php">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fa-solid fa-dollar-sign"></i> Payments
                        </li>
                    </a>
                    <a href="reports.php">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-file-medical"></i> Reports
                        </li>
                    </a>
                    <a href="articles.php">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-book-open"></i> Articles
                        </li>
                    </a>
                    
                    <a href="#">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-cog"></i> Settings
                        </li>
                    </a>
                    <a href="logout.php" onclick="return confirm('Are you sure to logout?')">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </li>
                    </a>
                <?php } else { ?>
                    <a href="login.php">
                        <li class="hover:bg-blue-700 p-2 rounded cursor-pointer flex items-center gap-2">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </li>
                    </a>
                <?php } ?>
                </ul>
            </nav>
        </aside>
        