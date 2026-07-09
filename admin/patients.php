<?php
$pageTitle = 'Patients/Users';
include 'includes/sidemenu.php';
include 'includes/header.php';
include 'api/admin_notification.php';

$result = $conn->query("SELECT * FROM patients ORDER BY pcreated_at DESC"); 

if (isset($_GET["delete"])) {
    $pid = $_GET["delete"];
    $conn->query("DELETE FROM patients WHERE pid=$pid");
    header("Location: patients.php");
    $_SESSION['toast'] = ['message' => 'Patient has been deleted.', 'type' => 'success'];
    exit();
}


?>




    <!-- <div class="container mx-auto p-6">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Add New Patient</h2>
            <form method="POST">
                <input type="text" name="name" placeholder="Full Name" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <input type="email" name="email" placeholder="Email Address" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <input type="text" name="phone" placeholder="Phone Number" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <select name="gender" required class="w-full p-2 border border-gray-300 rounded mb-3">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <input type="number" name="age" placeholder="Age" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <textarea name="address" placeholder="Address" required class="w-full p-2 border border-gray-300 rounded mb-3"></textarea>
                <button type="submit" name="add_patient" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Add Patient</button>
            </form>
        </div> -->

        <!-- Patients Table -->
        <main class="flex-1 ml-52 p-2 overflow-auto mt-14">
        <div class="bg-white shadow-md rounded-lg p-6">

            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-blue-700 mb-4">Patients/Users List</h2>
                <form method="POST" action="api/export_excel.php">
                    <input type="text" name="pagetitle" value="<?php echo $pageTitle; ?>" hidden>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 mb-4 rounded hover:bg-green-600"><i class="fa-solid fa-table"></i> Export to Excel</button>
                </form>
            </div>
            
            <table id="myTable" class="w-full border-collapse border border-gray-300 table-auto">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Created At</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Gender</th>
                        <th class="border border-gray-300 px-4 py-2">DOB</th>
                        <th class="border border-gray-300 px-4 py-2">Phone</th>
                        <th class="border border-gray-300 px-4 py-2">Address</th>

                        <th class="border border-gray-300 px-4 py-2">Email</th>
                        
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pid']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pcreated_at']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pname']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pgender']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pdob']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pphone']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['paddress']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['pemail']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="patients.php?delete=<?php echo $row['pid']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
function exportTableToExcel(tableID, filename = '') {
    const dataType = 'application/vnd.ms-excel';
    const tableSelect = document.getElementById(tableID);
    const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename ? filename + '.xls' : 'excel_data.xls';

    const downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        const blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>


</body>
</html>
