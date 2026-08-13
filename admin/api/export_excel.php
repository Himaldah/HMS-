{

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