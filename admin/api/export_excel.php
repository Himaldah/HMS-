<?php

$page_title = $_POST['pagetitle'];

$host = "localhost";  // Change if using a remote server
$username = "root";   // Change to your MySQL username
$password = "";       // Change to your MySQL password
$database = "hsm_db"; // Change to your MySQL database name


// Connect to database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($page_title == "Patients/Users"){

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=patient_exported_data.xls");

    echo "<table border='1'>";
    echo "<tr><td colspan='8' style='text-align: center; font-weight: bold;'>$page_title</td></tr>";
    echo "<tr><th>ID</th><th>Created At</th><th>Name</th><th>Gender</th><th>DOB</th><th>Phone</th><th>Address</th><th>Email</th></tr>";

    $qry = mysqli_query($conn, "SELECT * FROM patients");
    while($row = mysqli_fetch_assoc($qry)) {
        echo "<tr>";
        echo "<td>" . $row['pid'] . "</td>";
        echo "<td>" . $row['pcreated_at'] . "</td>";
        echo "<td>" . $row['pname'] . "</td>";
        echo "<td>" . $row['pgender'] . "</td>";
        echo "<td>" . $row['pdob'] . "</td>";
        echo "<td>" . $row['pphone'] . "</td>";
        echo "<td>" . $row['paddress'] . "</td>";
        echo "<td>" . $row['pemail'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} elseif($page_title == "Doctors") {

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=appointments_exported_data.xls");

    echo "<table border='1'>";
    echo "<tr><td colspan='14' style='text-align: center; font-weight: bold;'>$page_title</td></tr>";
    echo "<tr><th>ID</th><th>Created At</th><th>PID</th><th>DRID</th><th>DID</th><th>Patient</th><th>Gender</th><th>DOB</th><th>Phone</th><th>Date</th><th>Time</th><th>Token</th><th>Is Self</th><th>Status</th></tr>";

    $qry = mysqli_query($conn, "SELECT * FROM appointments");
    while($row = mysqli_fetch_assoc($qry)) {
        echo "<tr>";
        echo "<td>" . $row['aid'] . "</td>";
        echo "<td>" . $row['acreated_at'] . "</td>";
        echo "<td>" . $row['pid'] . "</td>";
        echo "<td>" . $row['drid'] . "</td>";
        echo "<td>" . $row['did'] . "</td>";
        echo "<td>" . $row['pname'] . "</td>";
        echo "<td>" . $row['other_gender'] . "</td>";
        echo "<td>" . $row['other_dob'] . "</td>";
        echo "<td>" . $row['pphone'] . "</td>";
        echo "<td>" . $row['appointment_date'] . "</td>";
        echo "<td>" . $row['appointment_time'] . "</td>";
        echo "<td>" . $row['token_num'] . "</td>";
        echo "<td>" . $row['is_self'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} 