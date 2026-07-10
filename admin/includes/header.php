<?php


if (!isset($_SESSION['admin_logged_in'])) {
        header('Location: login.php');
        exit();
}

include '../configs/db.php';


// Define the page title
$pageTitle = isset($pageTitle) ? $pageTitle : 'Default Title';
?>
<!-- Main Content -->

<div class="flex-1 flex flex-col">
    <!-- Navbar -->
    <header class="fixed top-0 left-0 ml-52 w-full bg-blue-300 text-blue-900 p-4 shadow-md z-50">
        <h1 class="text-xl font-bold"><?php echo $pageTitle; ?></h1>
    </header>

    <!-- <header class=""> -->