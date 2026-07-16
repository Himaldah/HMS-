<?php
include 'includes/header.php';

$drid = $_SESSION['drid'];
$drqry = "SELECT * FROM doctors WHERE drid = '$drid'";
$result = mysqli_query($conn, $drqry);
$doctor = mysqli_fetch_assoc($result);

$rows_count_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE drid = '$drid' AND status = 'Pending'"))['count'];
$rows_count_confirmed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE drid = '$drid' AND status = 'Confirmed'"))['count'];
$rows_count_cancelled = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE drid = '$drid' AND status = 'Cancelled'"))['count'];
$rows_count_completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE drid = '$drid' AND status = 'Completed'"))['count'];


// Count total appointments
$total_appointments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE drid = '$drid'"))['total'];

// Count upcoming appointments (you can define today or future)
$upcoming = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE drid = '$drid' AND appointment_date >= CURDATE()"))['total'];

// Count total reports
$total_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM reports WHERE aid IN (SELECT aid FROM appointments WHERE drid = '$drid')"))['total'];

// Count schedules
$total_schedules = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM doctor_schedule WHERE drid = '$drid'"))['total'];
?>

<!-- <header class="relative bg-gradient-to-r from-blue-300 to-pink-300 px-10 pt-24 pb-16 h-screen overflow-hidden"> -->
<header class="relative px-10 pt-20 pb-16 h-screen overflow-hidden">
<main class="p-6 max-w-6xl mx-auto  ">
    <h2 class="text-2xl font-bold text-blue-900 mb-6 text-center">Welcome, <?php echo htmlspecialchars($doctor['drname']); ?></h2>
    <h2 class="text-2xl font-bold text-blue-900 mb-6 ">Dashboard</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6 mb-6">

        <!-- Schedule Days -->
        <div class="bg-white border rounded-lg shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
            <p class="text-black-600">Total Schedules</p>
            <h3 class="text-3xl font-bold text-black-600"><?php echo $total_schedules; ?></h3>
        </div>

        <!-- Total Appointments -->
        <div class="bg-white border rounded-lg shadow  hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
            <p class="text-black-600">Total Appointments</p>
            <h3 class="text-3xl font-bold text-black-700"><?php echo $total_appointments; ?></h3>
        </div>

        </div>

        <hr class="border-t-2 border-pink-500">


        <!-- Upcoming Appointments -->
        <!-- <div class="bg-white border rounded-lg shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
            <p class="text-gray-600">Upcoming</p>
            <h3 class="text-3xl font-bold text-green-600"><?php echo $upcoming; ?></h3>
        </div> -->

    
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6 mb-6">
        <div class="bg-white border rounded-lg shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
            <p class="text-yellow-500">Pending Appointments</p>
            <h3 class="text-3xl font-bold text-yellow-500"><?php echo $rows_count_pending; ?></h3>
        </div>

        <div class="bg-white border rounded-lg shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
            <p class="text-blue-500">Confirmed Appointments</p>
            <h3 class="text-3xl font-bold text-blue-500"><?php echo $rows_count_confirmed; ?></h3>
        </div>

        <div class="bg-white border rounded-lg shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
            <p class="text-red-500">Cancelled Appointments</p>
            <h3 class="text-3xl font-bold text-red-500"><?php echo $rows_count_cancelled; ?></h3>
        </div>

        <div class="bg-white border rounded-lg shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
            <p class="text-green-500">Completed Appointments</p>
            <h3 class="text-3xl font-bold text-green-500"><?php echo $rows_count_completed; ?></h3>
        </div>
        
        </div>

        <hr class="border-t-2 border-blue-500">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                <!-- Total Reports -->
                <div class="bg-white border rounded-lg shadow hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-5 text-center">
                <p class="text-black-600">Reports Created</p>
                <h3 class="text-3xl font-bold text-black-600"><?php echo $total_reports; ?></h3>
            </div>
        </div>

  

    <!-- <div class="bg-white rounded-lg shadow p-4 mt-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-3">Weekly Appointments Overview</h2>
    <canvas id="appointmentsChart" height="100"></canvas>
    </div> -->

</main>
</header>
<!-- 
<script>
const ctx = document.getElementById('appointmentsChart').getContext('2d');
const appointmentsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        datasets: [{
            label: 'Appointments',
            data: [5, 3, 8, 6, 4, 7], // Replace with PHP data if needed
            backgroundColor: 'rgba(59, 130, 246, 0.5)', // Tailwind blue-500 w/ opacity
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script> -->
