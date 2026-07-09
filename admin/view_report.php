<?php
$pageTitle = 'Reports   ';
include 'includes/sidemenu.php';
include 'includes/header.php';

$rid = $_GET['rid'] ?? null;
$reports_query = "SELECT * FROM reports WHERE rid = '$rid'";
$report_result = $conn->query($reports_query);



?>

<main class="flex-1 ml-52 p-2 overflow-auto mt-14">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Medical Report</h2>
        
    <!-- Report Section -->
    <?php if (mysqli_num_rows($report_result) > 0): ?>
        <?php $report = mysqli_fetch_assoc($report_result);
        $aid = $report['aid'];
        $appointment_query = "SELECT * FROM appointments WHERE aid = '$aid'";
        $appointment_result = $conn->query($appointment_query);
        $appointment = mysqli_fetch_assoc($appointment_result);
        ?>
        <div class="mb-6 bg-white shadow-md border rounded-lg p-4">

        <h2 class="text-xl text-center font-bold text-blue-700 mb-4">OHCMS</h2>
        <!-- <h2 class="text-xl font-semibold text-blue-700 mb-2">Patient Details</h2> -->

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <p><strong>Patient ID:</strong> <?php echo htmlspecialchars($report['pid']); ?></p>
            <p><strong>Appointment ID:</strong> <?php echo htmlspecialchars($report['aid']); ?></p>
            <p ><strong>Appointment Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
        </div>

        <hr class="my-4"></hr>

        <div class="grid grid-cols-1 sm:grid-cols-2  gap-4">
            <?php 
                $patient_id = $report['pid'];
                $patient_query = "SELECT * FROM patients WHERE pid = '$patient_id'";
                $patient_result = $conn->query($patient_query);
                $patient = mysqli_fetch_assoc($patient_result);
                $patient_dob = $patient['pdob'];

                $current_date = date('Y-m-d');
                $patient_age = date_diff(date_create($patient_dob), date_create($current_date))->y;
            ?>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['pname']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['pgender']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['pdob']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($patient_age); ?></p> 
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['pphone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['pemail']); ?></p>

            <?php 
                $doctor_id = $appointment['drid'];
                $doctor_query = "SELECT * FROM doctors WHERE drid = '$doctor_id'";
                $doctor_result = $conn->query($doctor_query);
                $doctor_info = mysqli_fetch_assoc($doctor_result);

                $department_id = $doctor_info['did'];
                $department_query = "SELECT * FROM departments WHERE did = '$department_id'";
                $department_result = $conn->query($department_query);
                $department_info = mysqli_fetch_assoc($department_result);
                $department_name = $department_info['dname'];
            ?>

            <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($doctor_info['drname']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($department_name); ?> (<?php echo htmlspecialchars($department_info['ddescription']); ?>)</p>
            
        </div>

        <hr class="my-4"></hr>





        <div>
            <!-- <h2 class="text-xl font-semibold text-pink-600 mb-4"><?php echo htmlspecialchars($report['report_title']); ?></h2> -->
            <!-- <p class="text-sm text-gray-500 mb-2"><strong>Report Date:</strong> <?php echo htmlspecialchars($report['report_date']); ?></p> -->
            <div class="mb-4">
                <p><span class="font-semibold">Date: </span><?php echo htmlspecialchars($report['report_date']); ?></p>
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
                <a href="../api/download_report.php?aid=<?php echo $report['aid']; ?>" 
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"><i class="fas fa-file-pdf mr-2"></i>
                Download PDF
                </a>
            </div>  
        </div>
    <?php else: ?>
        <p class="text-gray-600 text-center mt-8">No reports available.</p>
    <?php endif; ?>

    </div>

</main>

</body>
</html>
