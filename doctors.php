<?php
include 'configs/db.php'; // Ensure the database connection is included
include 'includes/header.php';

// Get department ID from URL (if available)
$department_id = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch department name if a specific department is selected
if ($department_id > 0) {
    $stmt = $conn->prepare("SELECT dname FROM departments WHERE did = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $dept_result = $stmt->get_result();
    $department = $dept_result->fetch_assoc();
    $department_name = $department ? $department['dname'] : "All Doctors";
    $stmt->close();
} else {
    $department_name = "All Doctors";
}

// Fetch doctors (filtered by department if applicable)
if ($department_id > 0 && $search !== '') {

    $stmt = $conn->prepare("SELECT DISTINCT d.* 
    FROM doctors d
    LEFT JOIN doctor_schedule s ON d.drid = s.drid
    WHERE d.did = ? AND (
        d.drname LIKE ? 
        OR s.available_date LIKE ?
        OR s.day LIKE ?
    )");

    $searchParam = "%$search%";
    $stmt->bind_param("ssss", $department_id, $searchParam, $searchParam, $searchParam);
} elseif ($department_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE did = ?");
    $stmt->bind_param("i", $department_id);
} elseif ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM doctors WHERE drname LIKE ?");
    $searchParam = "%$search%";
    $stmt->bind_param("s", $searchParam);
} else {
    $stmt = $conn->prepare("SELECT * FROM doctors");
}
$stmt->execute();
$doctor_result = $stmt->get_result();



// Fetch all schedules and group them by doctor ID
$schedules = [];
$schedule_query = $conn->query("SELECT * FROM doctor_schedule");

while ($row = $schedule_query->fetch_assoc()) {
    $schedules[$row['drid']][] = $row;
}


// Get current day (e.g., Sunday)
// $currentDay = date('l');
// $date = $row['available_date'] ?? null;

// 1. Fetch total tokens for current day from doctor_schedule
$schedule_stmt = $conn->prepare("SELECT tokens FROM doctor_schedule WHERE drid = ? AND available_date = ?");
$schedule_stmt->bind_param("ii", $doctor['drid'], $date);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();
$schedule = $schedule_result->fetch_assoc();
$schedule_stmt->close();

// $totalTokens = $schedule ? (int)$schedule['tokens'] : 0;

// 2. Count booked appointments for today
$todayDate = date('Y-m-d');
$appt_stmt = $conn->prepare("SELECT COUNT(*) as booked FROM appointments WHERE drid = ? AND appointment_date = ?");
$appt_stmt->bind_param("is", $doctor['drid'], $todayDate);
$appt_stmt->execute();
$appt_result = $appt_stmt->get_result()->fetch_assoc();
$appt_stmt->close();

$bookedTokens = (int)$appt_result['booked'];
// $tokensLeft = max($totalTokens - $bookedTokens, 0);




?>

<body class="bg-white text-gray-800 ">

    <!-- Page Header -->
    <header class="text-black text-center py-6 pt-20">
        <h1 class="text-3xl text-center font-bold text-blue-900">Doctors in <?php echo htmlspecialchars($department_name); ?></h1>
    </header>

    <form method="GET" action="" class="max-w-md mx-auto my-6 text-center">
        <?php if ($department_id > 0): ?>
            <input type="hidden" name="department_id" value="<?php echo $department_id; ?>">
        <?php endif; ?>
        <input type="text" name="search" id="searchInput" placeholder="Search doctors or schedules..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="w-2/3 px-4 py-2 border rounded-lg" required>
        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Search</button>
    </form>

    <!-- Doctors Grid -->
    <div class="container mx-auto p-6 flex-grow">
        <div id="doctorsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">


        <?php if ($doctor_result && $doctor_result->num_rows > 0): ?>
            <?php while ($doctor = $doctor_result->fetch_assoc()): ?>
                <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white">
                    <img src="admin/<?php echo htmlspecialchars($doctor['drprofile']); ?>" 
                         alt="Doctor Profile" 
                         class="w-24 h-24 mx-auto mb-3 rounded-xl border-2 border-blue-200">
                    
                    <a href="doctor_profile.php?drid=<?php echo $doctor['drid'] ?>"><h2 class="text-xl font-semibold text-blue-900"><?php echo htmlspecialchars($doctor['drname']); ?></h2></a>
                    <!-- <p class="text-gray-600"><?php echo htmlspecialchars($doctor['specialization']); ?></p> -->
                    <!-- <p class="text-gray-600">Email: <?php echo htmlspecialchars($doctor['dremail']); ?></p> -->

                    <div class="flex justify-center">
                        <p class="text-gray-700 flex items-center gap-2">
                            <span class="text-yellow-500 text-xl">
                                <?php
                                    $drid = $doctor['drid'];
                                    $avg_result = mysqli_query($conn, "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total FROM ratings WHERE drid='$drid'");
                                    $avg_data = mysqli_fetch_assoc($avg_result);
                                    $avg_rating = round($avg_data['avg_rating'], 1);
                                    $total_ratings = $avg_data['total'];
                                    $fullStars = floor($avg_rating);
                                    $halfStar = ($avg_rating - $fullStars) >= 0.5 ? true : false;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                                    for ($i = 0; $i < $fullStars; $i++) echo '★';
                                    if ($halfStar) echo '☆';
                                    for ($i = 0; $i < $emptyStars; $i++) echo '☆';
                                ?>
                            </span>
                            <span class="text-gray-600 text-sm">
                                <!-- (<?php echo $avg_rating ? $avg_rating : "Not rated yet"; ?>, -->
                                <!-- (<?php echo $total_ratings; ?> rating <?php echo $total_ratings == 1 ? "" : "s"; ?>) -->
                                (<?php echo $total_ratings; ?>)
                            </span>
                        </p>
                    </div>



                    <div class="mt-2 border border-blue-200 p-2 rounded-lg bg-blue-50">
    <!-- Schedule cards here -->


                    <p class="text-gray-800 font-medium mt-2 text-sm mb-1">Available Schedules and Tokens:</p>
                    <?php 
                    $upcoming = [];
                    if (!empty($schedules[$doctor['drid']])){
                            $upcoming = array_filter($schedules[$doctor['drid']], function($s) {
                                return strtotime($s['available_date']) >= strtotime(date('Y-m-d'));
                            });

                            usort($upcoming, function($a, $b) {
                                return strtotime($a['available_date']) - strtotime($b['available_date']);
                            });
                            $upcoming = array_slice($upcoming, 0, 3);
                        }
                        ?>
                        <?php if (!empty($upcoming)): ?> 
                            <div class="text-left text-sm space-y-1">
                            <?php foreach ($upcoming as $sched): ?>
                                <div class="flex justify-between items-center bg-blue-50 px-3 py-1 rounded shadow-sm">
                                    <span class="font-medium text-blue-900">
                                        <?php echo htmlspecialchars($sched['day']); ?>
                                    </span>
                                    <span class="font-medium">
                                        <!-- <?php echo date('h:i A', strtotime($sched['start_time'])) . ' - ' . date('h:i A', strtotime($sched['end_time'])); ?> -->
                                        <?php echo htmlspecialchars($sched['available_date']); ?>
                                    </span>
                                    <span>
                                    <?php
                                    $appointmentDate = date('Y-m-d', strtotime($sched['available_date']));
                                    $appt_stmt = $conn->prepare("SELECT COUNT(*) as booked FROM appointments WHERE drid = ? AND appointment_date = ?");
                                    $appt_stmt->bind_param("is", $doctor['drid'], $appointmentDate);
                                    $appt_stmt->execute();
                                    $appt_result = $appt_stmt->get_result()->fetch_assoc();
                                    $appt_stmt->close();

                                    $bookedTokens = (int)$appt_result['booked'];
                                    $tokensLeft = max($sched['tokens'] - $bookedTokens, 0);
   
                                    // Check if tokens are available
                                    $tokensAvailable = $sched['tokens'] - $bookedTokens;
                                    if ($tokensAvailable <= 0) {
                                        echo '<span class="text-red-600 font-semibold">Not Available</span>';
                                    } else {
                                        echo '<span class="text-green-600 font-semibold">Tokens: ' . htmlspecialchars($tokensAvailable) . '/' . htmlspecialchars($sched['tokens']) . '</span>';
                                    }

                                    ?>
                                    </span>
                                    
                                    <!-- <span class="text-pink-600 font-semibold">
                                        Booked: <?php echo $bookedTokens; ?>
                                    </span> -->
                                    <!-- <span>
                                    <a href="appointment.php?doctor_id=<?php echo $doctor['drid']; ?>"
                           class="mt-4 inline-block bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                            Book Appointment
                        </a></span> -->
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm text-center mt-4 mb-4">No upcoming schedules available</p>
                    <?php endif; ?>

                    </div>
                    
                    <!-- <p class="text-gray-800 font-medium mt-2 text-sm">Tokens Lefts: <?php echo $tokensLeft; ?></p> -->

                    <?php if (isset($_SESSION['pemail'])): ?>
                        <a href="appointment.php?department_id=<?php echo $department_id; ?>&&doctor_id=<?php echo $doctor['drid']; ?>"
                           class="mt-4 inline-block bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                            Book Appointment
                        </a>
                    <?php else : ?>
                        <a href="login.php" class="mt-4 inline-block bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                            Login to Book Appointment
                        </a>
                    <?php endif; ?> 
                </div>
            <?php endwhile; ?>

    <?php else: ?>
        <p class="text-center text-gray-600 col-span-full">No doctors found for "<?php echo htmlspecialchars($search); ?>"</p>
    <?php endif; ?>

        </div>
    </div>

<script>
    document.getElementById("searchInput").addEventListener("input", function () {
        let searchQuery = this.value;
        let deptId = "<?php echo $department_id; ?>";

        fetch("api/search_doctors.php?search=" + encodeURIComponent(searchQuery) + "&department_id=" + deptId)
            .then(response => response.text())
            .then(data => {
                document.getElementById("doctorsGrid").innerHTML = data;
            });
    });
</script>

</body>
</html>

<?php
    include 'includes/footer.php';
?>

