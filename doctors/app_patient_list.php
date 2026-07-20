<?php
$pageTitle = 'Appointments List';
include 'includes/header.php';

$appointment_date = $_GET['appointment_date'];


$drid = $_SESSION['drid'];

if (isset($_GET['action']) && isset($_GET['aid'])) {
    $action = $_GET['action'];
    $aid = intval($_GET['aid']);

    if ($action === 'complete') {
        mysqli_query($conn, "UPDATE appointments SET status = 'Completed' WHERE aid = $aid AND drid = '$drid'");
    } elseif ($action === 'confirm') {
        mysqli_query($conn, "UPDATE appointments SET status = 'Confirmed' WHERE aid = $aid AND drid = '$drid'");
    } elseif ($action === 'cancel') {
        mysqli_query($conn, "UPDATE appointments SET status = 'Cancelled' WHERE aid = $aid AND drid = '$drid'");
    }

    // Optional: redirect back to the same page to avoid resubmission on refresh
    header("Location: app_patient_list.php?appointment_date=" . urlencode($appointment_date));
    exit;
}


$drqry = "SELECT * FROM doctors WHERE drid = '$drid'";
$result = mysqli_query($conn, $drqry);
$doctor = mysqli_fetch_assoc($result);

$patient_email = $_SESSION['pemail'] ?? null;
$patient_qry = "SELECT * FROM patients WHERE pemail = '$patient_email'";
$patient_result = mysqli_query($conn, $patient_qry);
$patient = mysqli_fetch_assoc($patient_result); 
$patient_id = $patient['pid'] ?? null;
$patient_name = $patient['pname'] ?? null;
$patient_phone = $patient['pphone'] ?? null;

// Fetch Appointments
$filter_sql = "SELECT appointments.*, doctors.drname, doctors.did FROM appointments JOIN doctors ON appointments.drid = doctors.drid WHERE appointments.drid = '$drid' AND appointments.appointment_date = '$appointment_date'";

// Add filters
if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $filter_sql .= " AND appointments.status = '$status'";
}

if (!empty($_GET['gender'])) {
    $gender = mysqli_real_escape_string($conn, $_GET['gender']);
    $filter_sql .= " AND appointments.other_gender = '$gender'";
}

// if (!empty($_GET['date'])) {
//     $date = mysqli_real_escape_string($conn, $_GET['date']);
//     $filter_sql .= " AND appointments.appointment_date = '$date'";
// }

$filter_sql .= "  ORDER BY aid ASC, token_num ASC";

$appointments = $conn->query($filter_sql);


$sn = 1;

?>

<header class="text-black text-center py-4 pt-20">
        <h1 class="text-3xl font-bold text-blue-900 text-center">Appointment Patient List</h1>
    </header>

    <form method="GET" class="mb-4 flex flex-wrap justify-center gap-4">    
            
            <!-- <input type="date" name="appointment_date" value="<?php echo $appointment_date ?? ''; ?>" class="border p-2 rounded-lg" placeholder="Appointment Date"> -->

            <input type="text" class="hidden" name="appointment_date" value="<?php echo $appointment_date ?>">
            
            <select name="gender" class="border p-2 rounded-lg">
                <option value="">All Gender</option>
                <option value="Male" <?php if(($_GET['gender'] ?? '') === 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if(($_GET['gender'] ?? '') === 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if(($_GET['gender'] ?? '') === 'Other') echo 'selected'; ?>>Other</option>
            </select>

            <select name="status" class="border p-2 rounded-lg">
                <option value="">All Status</option>
                <option value="Pending" <?php if(($_GET['status'] ?? '') === 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Confirmed" <?php if(($_GET['status'] ?? '') === 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="Completed" <?php if(($_GET['status'] ?? '') === 'Completed') echo 'selected'; ?>>Completed</option>
                <option value="Cancelled" <?php if(($_GET['status'] ?? '') === 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 self-center">Filter</button>
            <a href="app_patient_list.php?appointment_date=<?php echo $appointment_date ?>" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 self-center">Clear</a>
        </form>

<main class="flex-1 p-2 overflow-auto flex justify-center">
    <div class="container mx-auto">
        <div class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white">
            <!-- <h2 class="text-xl font-semibold text-blue-700 mb-4 text-center">Appointments List</h2> -->
            <div class="flex justify-center">
                <table class="border-collapse border border-gray-300 w-full">
                    <thead>
                        <tr class="bg-pink-500 text-white ">
                            <th class="border border-blue-300 px-4 py-2">S.N</th>
                            <th class="border border-blue-300 px-4 py-2">AID</th>
                            <th class="border border-blue-300 px-4 py-2">Token Number</th>
                            <!-- <th class="border border-blue-300 px-4 py-2">Booked At</th> -->
                            <!-- <th class="border border-blue-300 px-4 py-2">Department</th> -->

                            <!-- <th class="border border-blue-300 px-4 py-2">Doctor</th> -->
                            <th class="border border-blue-300 px-4 py-2">Patient Name</th>
                            <th class="border border-blue-300 px-4 py-2">Gender</th>
                            <th class="border border-blue-300 px-4 py-2">DOB</th>
                            <th class="border border-blue-300 px-4 py-2">Phone</th>
                            <th class="border border-blue-300 px-4 py-2">Payment</th>

                            <!-- <th class="border border-blue-300 px-4 py-2">Date</th> -->
                            <th class="border border-blue-300 px-4 py-2">Status</th>
                            <th class="border border-blue-300 px-4 py-2">Action</th>
                            <th class="border border-blue-300 px-4 py-2">Report</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if ($appointments && $appointments->num_rows > 0): ?>
                        
                        <?php while ($row = $appointments->fetch_assoc()): 
                            $gender = $row['other_gender'] ?? null;
                            $dob = $row['other_dob'] ?? null;
                            
                            if ($row['is_self'] == 1) {
                                $pat_result = mysqli_query($conn, "SELECT pgender, pdob FROM patients WHERE pid = {$row['pid']}");
                                $pat_data = mysqli_fetch_assoc($pat_result);
                                $gender = $pat_data['pgender'] ?? null;
                                $dob = $pat_data['pdob'] ?? null;
                            }
                            
                            $did = $row['did'] ?? null;
                            $did_qry = "SELECT * FROM departments WHERE did = '$did'";
                            $did_result = mysqli_query($conn, $did_qry);
                            $did_row = mysqli_fetch_assoc($did_result);
                            $department = $did_row['dname'] ?? null;

                            $aid = $row['aid'] ?? null;
                            $payment_qry = "SELECT * FROM payments WHERE appointment_id = '$aid'";
                            $payment_result = mysqli_query($conn, $payment_qry);
                            $payment = mysqli_fetch_assoc($payment_result);

                            ?>

                            <tr class="text-center  hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4">
                                <td class="border border-blue-300 px-4 py-2"><?php echo $sn++; ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo $row['aid']; ?></td>    
                                <td class="border border-blue-300 px-4 py-2"><?php echo $row['token_num']; ?></td>
                                <!-- <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['acreated_at']); ?></td> -->
                                <!-- <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($department); ?></td> -->

                                <!-- <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['drname']); ?></td> -->
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['pname']); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($gender); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($dob); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['pphone']); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($payment['pmstatus']); ?></td>

                                <!-- <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['appointment_date']); ?></td> -->

                                <?php if($row['status'] === 'Pending'): ?>
                                    <td class="text-yellow-500 border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                                <?php elseif($row['status'] === 'Confirmed'): ?>
                                    <td class="text-blue-500 border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                                <?php elseif($row['status'] === 'Completed'): ?>
                                    <td class="text-green-500 border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                                <?php elseif($row['status'] === 'Cancelled'): ?>
                                    <td class="text-red-500 border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                                <?php endif; ?>

                                <td class="border border-blue-300 px-4 py-2">
                                <?php if ($row['status'] === 'Pending'): ?>
                                        <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=confirm&aid=<?php echo $row['aid']; ?>" class="text-green-500 hover:text-green-700">Confirm</a>
                                        <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=cancel&aid=<?php echo $row['aid']; ?>" class="text-red-500 hover:text-red-700 ml-2">Cancel</a>
                                    <?php elseif($row['status'] === 'Confirmed'): ?>
                                        <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=complete&aid=<?php echo $row['aid']; ?>" class="text-green-500 hover:text-green-700">Complete</a>
                                        <!-- <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=confirm&aid=<?php echo $row['aid']; ?>" class="text-green-500 hover:text-green-700">Confirm</a> -->
                                        <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=cancel&aid=<?php echo $row['aid']; ?>" class="text-red-500 hover:text-red-700 ml-2">Cancel</a>
                                    <?php elseif($row['status'] === 'Cancelled'): ?>
                                        <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=confirm&aid=<?php echo $row['aid']; ?>" class="text-green-500 hover:text-green-700">Confirm</a>
                                        <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=cancel&aid=<?php echo $row['aid']; ?>" class="text-red-500 hover:text-red-700 ml-2">Cancel</a>
                                        <!-- <span class="text-gray-500">Already <?php echo $row['status']; ?></span> -->
                                    <?php endif; ?>
                                </td>

                                <td class="border border-blue-300 px-4 py-2">
                                    <?php 
                                    $appointment_id = $row['aid'];
                                    // $report_qry = "SELECT * FROM reports WHERE pid = $patient_id AND aid = $appointment_id";
                                    $report_qry = "SELECT * FROM reports WHERE pid = {$row['pid']} AND aid = {$row['aid']}";

                                    $report_result = mysqli_query($conn, $report_qry);
                                    if ($report_result->num_rows > 0) {
                                        $report = $report_result->fetch_assoc(); ?>
                                        <a href="report.php?aid=<?php echo $row['aid']; ?>" class="text-pink-500 hover:text-pink-600">Update</a>
                                    <?php } else { ?>
                                        <a href="report.php?aid=<?php echo $row['aid']; ?>" class="text-pink-500 hover:text-pink-600">Add</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        
                        <?php else: ?>
                            <p class="text-center text-gray-600 col-span-full">No patient found!</p>
                        <?php endif; ?>
                    </tbody>
                    
                </table>
                
            </div>
            
            
        </div>
        
    </div>
   
</main>


