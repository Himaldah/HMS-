<?php
$pageTitle = 'Appointments';
include 'includes/sidemenu.php';
include 'includes/header.php';
include 'api/admin_notification.php';
$result = $conn->query("SELECT * FROM appointments");

// Fetch patients list
$patients = $conn->query("SELECT * FROM patients");

// Handle Adding an Appointment
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_appointment"])) {
//     $patient_id = $_POST['pid'];
//     $department = $_POST['department'];
//     $doctor = $_POST['doctor'];
//     $appointment_date = $_POST['appointment_date'];
//     $appointment_time = $_POST['appointment_time'];
    
//     $conn->query("INSERT INTO appointments (patient_id, department, doctor, appointment_date, appointment_time) 
//                   VALUES ($patient_id, '$department', '$doctor', '$appointment_date', '$appointment_time')");
//     header("Location: admin_appointments.php");
// }

// // Handle Deleting an Appointment
// if (isset($_GET["delete"])) {
//     $id = $_GET["delete"];
//     $conn->query("DELETE FROM appointments WHERE aid=$id");
//     header("Location: admin_appointments.php");
// }

// Fetch all appointments
$result = $conn->query("SELECT appointments.*, patients.pname AS patient_name FROM appointments 
                        JOIN patients ON appointments.pid = patients.pid ORDER BY appointments.acreated_at DESC");

if (isset($_GET["delete"])) {
    $aid = $_GET["delete"];
    $conn->query("DELETE FROM appointments WHERE aid=$aid");
    header("Location: appointments.php");
    $_SESSION['toast'] = ['message' => 'Appointment has been deleted.', 'type' => 'success'];
}

?>


    <!-- <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Schedule a New Appointment</h2>
            <form method="POST">
                <select name="patient_id" required class="w-full p-2 border border-gray-300 rounded mb-3">
                    <option value="">Select Patient</option>
                    <?php while ($patient = $patients->fetch_assoc()): ?>
                        <option value="<?php echo $patient['id']; ?>"><?php echo $patient['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="text" name="department" placeholder="Department" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <input type="text" name="doctor" placeholder="Doctor's Name" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <input type="date" name="appointment_date" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <input type="time" name="appointment_time" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <button type="submit" name="add_appointment" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Schedule Appointment</button>
            </form>
        </div> -->

        <!-- Appointments Table -->
        <main class="flex-1 ml-52 p-2 overflow-auto mt-14">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-blue-700 mb-4">Appointment List</h2>
                <form method="POST" action="api/export_excel.php">
                    <input type="text" name="pagetitle" value="<?php echo $pageTitle; ?>" hidden>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 mb-4 rounded hover:bg-green-600"><i class="fa-solid fa-table"></i> Export to Excel</button>
                </form>
            </div>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Craeted At</th>
                        <th class="border border-gray-300 px-4 py-2">PID</th>
                        <th class="border border-gray-300 px-4 py-2">DRID</th>
                        <th class="border border-gray-300 px-4 py-2">DID</th>
                        <th class="border border-gray-300 px-4 py-2">Patient</th>
                        <th class="border border-gray-300 px-4 py-2">Gender</th>
                        <th class="border border-gray-300 px-4 py-2">DOB</th>
                        <th class="border border-gray-300 px-4 py-2">Phone</th>
                        <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">Time</th>
                        <th class="border border-gray-300 px-4 py-2">Token</th>
                        <th class="border border-gray-300 px-4 py-2">Is Self</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['aid']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['acreated_at']; ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="patients.php?pid=<?php echo $row['pid']; ?>"><?php echo $row['pid']; ?></a></td>    
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="doctors.php?drid=<?php echo $row['drid']; ?>"><?php echo $row['drid']; ?></a></td>    
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="doctors.php?drid=<?php echo $row['drid']; ?>"><?php echo $row['did']; ?></a></td>    
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pname']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['other_gender']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['other_dob']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pphone']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['appointment_date']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['appointment_time']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['token_num']; ?></td>

                            <?php if ($row['is_self'] == 1) : ?>
                                <td class="border border-gray-300 px-4 py-2">Yes</td>
                            <?php else : ?>
                                <td class="border border-gray-300 px-4 py-2">No</td>
                            <?php endif; ?>
                            <!-- <td class="border border-gray-300 px-4 py-2"><?php echo $row['is_self'] == 1 ? 'Yes' : 'No'; ?></td> -->
                            <!-- <td class="border border-gray-300 px-4 py-2"><?php echo $row['is_self']; ?></td> -->

                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['status']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="appointments.php?delete=<?php echo $row['aid']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
                                                                        