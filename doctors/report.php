<?php
$pageTitle = 'Appointments List';
include 'includes/header.php';

$appointment_id = $_GET['aid'] ?? null;

// Fetch Appointments
$appointments = $conn->query("SELECT * FROM appointments WHERE aid = $appointment_id" );
if ($appointments->num_rows > 0) {
    $appointment = $appointments->fetch_assoc();
    $appointment_date = $appointment['appointment_date'] ?? null;
    $appointment_time = $appointment['atime'] ?? null;
    $patient_id = $appointment['pid'] ?? null;
} else {
    echo "<p class='text-red-500'>No appointment found.</p>";
    exit;
}

// $patient_email = $_SESSION['pemail'] ?? null;
$patient_qry = "SELECT * FROM patients WHERE pid = '$patient_id'";
$patient_result = mysqli_query($conn, $patient_qry);
$patient = mysqli_fetch_assoc($patient_result);
// $patient_id = $patient['pid'] ?? null;
$patient_name = $patient['pname'] ?? null;
$patient_phone = $patient['pphone'] ?? null;
$patient_dob = $patient['pdob'] ?? null;
$patient_gender = $patient['pgender'] ?? null;
$patient_email = $patient['pemail'] ?? null;


$report_qry = "SELECT * FROM reports WHERE pid = $patient_id AND aid = $appointment_id";
$report_result = mysqli_query($conn, $report_qry);
// $report = mysqli_fetch_assoc($report_result);

$drid = $appointment['drid'] ?? null;

$doctor_stmt = $conn->prepare("SELECT * FROM doctors WHERE drid = ?");
$doctor_stmt->bind_param("i", $drid);
$doctor_stmt->execute();
$doctor_result = $doctor_stmt->get_result();
$doctor_info = $doctor_result->fetch_assoc();

$department_id = $doctor_info['did'] ?? null;
$department_stmt = $conn->prepare("SELECT * FROM departments WHERE did = ?");
$department_stmt->bind_param("i", $department_id);
$department_stmt->execute();
$department_result = $department_stmt->get_result();
$department_info = $department_result->fetch_assoc();
$department_name = $department_info['dname'] ?? null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Check if a report already exists
    $check_stmt = $conn->prepare("SELECT * FROM reports WHERE pid = ? AND aid = ?");
    $check_stmt->bind_param("ii", $patient_id, $appointment_id);
    $check_stmt->execute();
    $existing_report = $check_stmt->get_result();

    if ($existing_report->num_rows > 0) {
        // Update report
        $stmt = $conn->prepare("UPDATE reports SET report_description = ?, report_date = ? WHERE pid = ? AND aid = ?");
        $stmt->bind_param("ssii", $description, $date, $patient_id, $appointment_id);

        if ($stmt->execute()) {
            echo "<p class='text-green-500 text-center'>Report Updated!</p>";
        } else {
            echo "<p class='text-red-500 text-center'>Error updating report: " . $stmt->error . "</p>";
        }
    } else {
        // Insert new report
        $stmt = $conn->prepare("INSERT INTO reports (aid, pid, report_description, report_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $appointment_id, $patient_id, $description, $date);

        if ($stmt->execute()) {
            echo "<p class='text-green-500 text-center'>Report Added!</p>";
        } else {
            echo "<p class='text-red-500 text-center'>Error inserting report: " . $stmt->error . "</p>";
        }
    }
}

$current_date = date('Y-m-d');
$patient_age = date_diff(date_create($patient_dob), date_create($current_date))->y;

?>

<header class="text-black text-center py-4">
    <h1 class="text-2xl font-semibold">Medical Report</h1>
</header>

<main class="p-6 max-w-4xl mx-auto">
    <!-- Patient Details Card -->
        
    <!-- Report Section -->
    
        <div class="mb-6 bg-white shadow-md border rounded-lg p-4">

        <h2 class="text-xl text-center font-bold text-blue-700 mb-4">OHCMS</h2>
        <!-- <h2 class="text-xl font-semibold text-blue-700 mb-2">Patient Details</h2> -->

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($patient_id); ?></p>

            <p><strong>Appointment ID:</strong> <?php echo htmlspecialchars($appointment_id); ?></p>
            <p ><strong>Appointment Date:</strong> <?php echo htmlspecialchars($appointment_date); ?></p>

        </div>

        <hr class="my-4"></hr>

        <div class="grid grid-cols-1 sm:grid-cols-2  gap-4">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($patient_name); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient_gender); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient_dob); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($patient_age); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient_phone); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient_email); ?></p>
            <!-- <p><strong>Appointment Time:</strong> <?php echo htmlspecialchars($appointment_time); ?></p> -->
            <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($doctor_info['drname']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($department_name); ?>(<?php echo htmlspecialchars($department_info['ddescription']); ?>)</p>
            
        </div>

        <hr class="my-4"></hr>


        <!-- <h3 class="text-xl font-semibold mb-1 ">Description</h3> -->

        <form method="POST" action="">

        <?php if ($report_result->num_rows > 0) {
                $report = $report_result->fetch_assoc(); ?>
        <div class="mb-4">
            <label class="block text-gray-700">Date</label>
            <input type="date" name="date" class="w-full px-3 py-2 border rounded-md" value="<?php echo htmlspecialchars($report['report_date']) ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Description</label>
                <textarea name="description" placeholder="Write Description" class="w-full px-3 py-2 border rounded-md"><?php echo htmlspecialchars($report['report_description']) ?></textarea>

                <button type="submit" class="bg-pink-500 text-white p-2 rounded hover:bg-pink-600">Update</button>

            <?php } else{ ?>
                
                <div class="mb-4">
            <label class="block text-gray-700">Date</label>
            <input type="date" name="date" class="w-full px-3 py-2 border rounded-md">
            </div>
        <label class="block text-gray-700">Description</label>
                <textarea name="description" placeholder="Write Description" class="w-full px-3 py-2 border rounded-md"></textarea>
                <button type="submit" class="bg-pink-500 text-white p-2 rounded hover:bg-pink-600">Add</button>
            <?php } ?>
        </div>
        
        
        <!-- <p class="text-center mt-3">Don't have an account? <a href="register.php" class="text-blue-500">Register</a> -->
        </p>
    </form>

            <?php if (!empty($report['file_path'])): ?>
                <a href="<?php echo htmlspecialchars($report['file_path']); ?>" target="_blank"
                   class="inline-block mt-2 text-white bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 transition">
                   View Attached File
                </a>
            <?php endif; ?>
            <div class="flex justify-end mt-4">
                <a href="download_report.php?aid=<?php echo $appointment_id; ?>" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"><i class="fas fa-file-pdf mr-2"></i>
                Download PDF
                </a>
            </div>
        <!-- <?php if (mysqli_num_rows($report_result) > 0): ?>
            <?php $report = mysqli_fetch_assoc($report_result) ?>

        
    <?php else: ?>
        <p class="text-gray-600 text-center mt-8">No reports available.</p>
    <?php endif; ?> -->
</main>

