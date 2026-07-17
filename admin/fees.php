<?php
$pageTitle = 'Fees';
include 'includes/sidemenu.php';
include 'includes/header.php';
include 'api/admin_notification.php';


$depaertment_query = "SELECT * FROM departments";
$result = $conn->query($depaertment_query);
$fee_query = "SELECT * FROM appointment_fees";
$result2 = $conn->query($fee_query);

?>

        <main class="flex-1 ml-52 p-2 overflow-auto mt-14">

            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-blue-700 mb-4">Add Fee</h2>
                <form method="POST" enctype="multipart/form-data">
                    <select name="department" class="w-full p-2 mb-3 border rounded" required>
                        <option value="" disabled selected>Selects Department</option>
                        <?php while ($depaertment = $result->fetch_assoc()): ?>
                            <option value="<?php echo $depaertment['did']; ?>"><?php echo htmlspecialchars($depaertment['dname']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" name="amount" placeholder="Enter Amount" required class="w-full p-2 border border-gray-300 rounded mb-3">
                    <button type="submit" name="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Add Fee</button>
                
                </form>
            </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Departments Fee List</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">FID</th>
                        <th class="border border-gray-300 px-4 py-2">DID</th>
                        <th class="border border-gray-300 px-4 py-2">Department Name</th>
                        <th class="border border-gray-300 px-4 py-2">Appointment Fee</th>
                        <!-- <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">Day</th>
                        <th class="border border-gray-300 px-4 py-2">Start Time</th>
                        <th class="border border-gray-300 px-4 py-2">End Time</th>  
                        <th class="border border-gray-300 px-4 py-2">Tokens</th> -->
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>   
                </thead>
                <tbody>
                    <?php while ($fee = $result2->fetch_assoc()):
                        $did = $fee['did'];
                        $depaertment_query = "SELECT * FROM departments WHERE did = $did";
                        $depaertment_result = $conn->query($depaertment_query);
                        $depaertment_result = $depaertment_result->fetch_assoc();
                        ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $fee['fid']; ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="departments.php?did=<?php echo $fee['did']; ?>"><?php echo $fee['did']; ?></a></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $depaertment_result['dname']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $fee['famount']; ?></td>
                            <!-- <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['start_time']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['end_time']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['tokens']; ?></td> -->
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="edit_fee.php?fid=<?php echo $fee['fid']; ?>" class="text-green-500 hover:text-green-700">Update</a>
                                <a href="fees.php?delete=<?php echo $fee['fid']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>


<?php

if(isset($_POST['submit'])) {
    $did = $_POST['department'];
    $amount = $_POST['amount'];

    $conn->query("INSERT INTO appointment_fees (did, famount) VALUES ('$did', '$amount')");
    header("Location: fees.php");
    $_SESSION['toast'] = ['message' => 'Fee has been added.', 'type' => 'success'];
}

// Handle Department Deletion
if (isset($_GET["delete"])) {
    $fid = $_GET["delete"];
    $conn->query("DELETE FROM appointment_fees WHERE fid=$fid");
    header("Location: fees.php");
    $_SESSION['toast'] = ['message' => 'Fee has been deleted.', 'type' => 'success'];
}

?>