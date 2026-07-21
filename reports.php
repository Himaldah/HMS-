<?php
$pageTitle = 'Appointments List';
include 'includes/header.php';

$patient_email = $_SESSION['pemail'] ?? null;
$patient_qry = "SELECT * FROM patients WHERE pemail = '$patient_email'";
$patient_result = mysqli_query($conn, $patient_qry);
$patient = mysqli_fetch_assoc($patient_result);
$patient_id = $patient['pid'] ?? null;
$patient_name = $patient['pname'] ?? null;
$patient_phone = $patient['pphone'] ?? null;
$patient_dob = $patient['pdob'] ?? null;
$patient_gender = $patient['pgender'] ?? null;
$patient_address = $patient['paddress'] ?? null;

$appointment_id = $_GET['aid'] ?? null;

// Fetch Appointments
$appointments = $conn->query("SELECT * FROM appointments WHERE aid = $appointment_id" );
if ($appointments->num_rows > 0) {
    $appointment = $appointments->fetch_assoc();
    $appointment_date = $appointment['appointment_date'] ?? null;
    $appointment_time = $appointment['atime'] ?? null;
} else {
    echo "<p class='text-red-500'>No appointment found.</p>";
    exit;
}

$report_qry = "SELECT * FROM reports WHERE pid = $patient_id AND aid = $appointment_id";
$report_result = mysqli_query($conn, $report_qry);

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

$current_date = date('Y-m-d');
$patient_age = date_diff(date_create($patient_dob), date_create($current_date))->y;


?>

<header class="text-black text-center py-4">
    <h1 class="text-2xl font-semibold">Medical Report</h1>
</header>

<main class="p-6 max-w-4xl mx-auto pt-20">
    <!-- Patient Details Card -->
        
    <!-- Report Section -->
    <?php if (mysqli_num_rows($report_result) > 0): ?>
        <?php $report = mysqli_fetch_assoc($report_result) ?>
        <div class="mb-6 bg-white shadow-md border rounded-lg p-4">

        <h2 class="text-xl text-center font-bold text-blue-700 mb-4">OHCMS</h2>
        <!-- <h2 class="text-xl font-semibold text-blue-700 mb-2">Patient Details</h2> -->

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 ml-4">
            <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($patient_id); ?></p>

            <p><strong>Appointment ID:</strong> <?php echo htmlspecialchars($appointment_id); ?></p>
            <p ><strong>Appointment Date:</strong> <?php echo htmlspecialchars($appointment_date); ?></p>

        </div>

        <hr class="my-4"></hr>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ml-4">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($patient_name); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient_gender); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient_dob); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($patient_age); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($patient_address); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient_phone); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient_email); ?></p>
            <!-- <p><strong>Appointment Time:</strong> <?php echo htmlspecialchars($appointment_time); ?></p> -->
        </div>

        <hr class="my-4"></hr>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ml-4">
            <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($doctor_info['drname']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($department_name); ?>(<?php echo htmlspecialchars($department_info['ddescription']); ?>)</p>
        </div>

        <hr class="my-4"></hr>

        <div class="ml-4">
            <!-- <h2 class="text-xl font-semibold text-pink-600 mb-4"><?php echo htmlspecialchars($report['report_title']); ?></h2> -->
            <!-- <p class="text-sm text-gray-500 mb-2"><strong>Report Date:</strong> <?php echo htmlspecialchars($report['report_date']); ?></p> -->
            <div class="mb-4">
                <p><span class="font-semibold">Report Date: </span><?php echo htmlspecialchars($report['report_date']); ?></p>
                <!-- <label class="block text-gray-700">Date: </label>
                <input type="date" name="date" class="w-full px-3 py-2 border rounded-md"> -->
            </div>
                <!-- <textarea name="description" placeholder="Write Description" class="w-full px-3 py-2 border rounded-md"></textarea> -->
                <h3 class="text-xl font-semibold mb-1 ">Description</h3>
                <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($report['report_description'])); ?></p>


            <?php if (!empty($report['file_path'])): ?>
                <a href="<?php echo htmlspecialchars($report['file_path']); ?>" target="_blank"
                   class="inline-block mt-2 text-white bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 transition">
                   View Attached File
                </a>
            <?php endif; ?>
            <!-- <a href="download_report.php?aid=<?php echo $appointment_id; ?>" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"><i class="fas fa-file-pdf mr-2"></i> Download PDF</a> -->
            <div class="flex justify-end mt-4">
                <a href="api/download_report.php?aid=<?php echo $appointment_id; ?>" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"><i class="fas fa-file-pdf mr-2"></i>
                Download PDF
                </a>
            </div>  
        </div>
    <?php else: ?>
        <p class="text-gray-600 text-center mt-8">No reports available.</p>
    <?php endif; ?>
</main>


    <!-- Doctor Info Section (optional) -->
<!-- <?php
$appointment = $appointments->fetch_assoc();
$drid = $appointment['drid'] ?? null;

$doctor_stmt = $conn->prepare("SELECT drname, dremail FROM doctors WHERE drid = ?");
$doctor_stmt->bind_param("i", $drid);
$doctor_stmt->execute();
$doctor_result = $doctor_stmt->get_result();
$doctor_info = $doctor_result->fetch_assoc();
$doctor_stmt->close();
?>

<?php if ($doctor_info): ?>
    <div class="mb-6 bg-white shadow-md border rounded-lg p-4">
        <h2 class="text-xl font-semibold text-blue-700 mb-2">Doctor Details</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
            <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($doctor_info['drname']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor_info['dremail']); ?></p>
        </div>
    </div>
<?php endif; ?> -->

</main>

