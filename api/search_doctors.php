<?php
session_start();
include '../configs/db.php';


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$department_id = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;

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

// Fetch all schedules
$schedules = [];
$schedule_query = $conn->query("SELECT * FROM doctor_schedule");
while ($row = $schedule_query->fetch_assoc()) {
    $schedules[$row['drid']][] = $row;
}

ob_start();
if ($doctor_result && $doctor_result->num_rows > 0):
    while ($doctor = $doctor_result->fetch_assoc()):
        ?>
        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300">
            <img src="admin/<?php echo htmlspecialchars($doctor['drprofile']); ?>" 
                 alt="Doctor Profile" 
                 class="w-24 h-24 mx-auto mb-3 rounded-xl border-2 border-blue-200">
            <h2 class="text-xl font-semibold text-blue-900"><?php echo htmlspecialchars($doctor['drname']); ?></h2>

            <div class="mt-2 border border-blue-200 p-2 rounded-lg bg-blue-50">
                <p class="text-gray-800 font-medium mt-2 text-sm mb-1">Available Schedules and Tokens:</p>

                <?php 

                    $upcoming = [];
                    
                    if (!empty($schedules[$doctor['drid']])){
                        

                            // Filter out past dates
                            $upcoming = array_filter($schedules[$doctor['drid']], function($s) {
                                return strtotime($s['available_date']) >= strtotime(date('Y-m-d'));
                            });

                            // Sort by date ascending
                            usort($upcoming, function($a, $b) {
                                return strtotime($a['available_date']) - strtotime($b['available_date']);
                            });

                            // Take only the first 3
                            $upcoming = array_slice($upcoming, 0, 3);
                        }
                ?>

                <?php if (!empty($upcoming)): ?>
                    <div class="text-left text-sm space-y-1">
                        <?php foreach ($upcoming as $sched): ?>
                            <div class="flex justify-between items-center bg-blue-50 px-3 py-1 rounded shadow-sm">
                                <span class="font-medium text-blue-900"><?php echo htmlspecialchars($sched['day']); ?></span>
                                <span class="font-medium"><?php echo htmlspecialchars($sched['available_date']); ?></span>
                                <span>
                                    <?php
                                    $appointmentDate = date('Y-m-d', strtotime($sched['available_date']));
                                    $appt_stmt = $conn->prepare("SELECT COUNT(*) as booked FROM appointments WHERE drid = ? AND appointment_date = ?");
                                    $appt_stmt->bind_param("is", $doctor['drid'], $appointmentDate);
                                    $appt_stmt->execute();
                                    $appt_result = $appt_stmt->get_result()->fetch_assoc();
                                    $appt_stmt->close();

                                    $bookedTokens = (int)$appt_result['booked'];
                                    $tokensAvailable = $sched['tokens'] - $bookedTokens;
                                    if ($tokensAvailable <= 0) {
                                        echo '<span class="text-red-600 font-semibold">Not Available</span>';
                                    } else {
                                        echo '<span class="text-green-600 font-semibold">Tokens: ' . htmlspecialchars($tokensAvailable) . '/' . htmlspecialchars($sched['tokens']) . '</span>';
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-sm text-center mt-4 mb-4">No upcoming schedules available</p> 
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['pemail'])): ?>
                <a href="../appointment.php?doctor_id=<?php echo $doctor['drid']; ?>"
                   class="mt-4 inline-block bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                    Book Appointment
                </a>
            <?php else : ?>
                <a href="../login.php" class="mt-4 inline-block bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">
                    Login to Book Appointment
                </a>
            <?php endif; ?> 
        </div>
    <?php endwhile;
else:
    echo '<p class="text-center text-gray-600 col-span-full">No doctors found.</p>';;
endif;

echo ob_get_clean();
?>
