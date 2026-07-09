<?php
$pageTitle = 'Payments   ';
include 'includes/sidemenu.php';
include 'includes/header.php';  
include 'api/admin_notification.php';


$payments_query = "SELECT * FROM payments ORDER BY pmcreated_at DESC";
$result = $conn->query($payments_query);

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM payments WHERE pmid=$id");
    header("Location: payments.php");
    $_SESSION['toast'] = ['message' => 'Payment has been deleted.', 'type' => 'success'];
} 
?>

        <main class="flex-1 ml-52 p-2 overflow-auto mt-14">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Payments List</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">PMID</th>
                        <th class="border border-gray-300 px-4 py-2">Created At</th>
                        <th class="border border-gray-300 px-4 py-2">PID</th>
                        <th class="border border-gray-300 px-4 py-2">AID</th>
                        <th class="border border-gray-300 px-4 py-2">Transaction ID</th>
                        <th class="border border-gray-300 px-4 py-2">Amount</th>
                        <th class="border border-gray-300 px-4 py-2">Payment Method</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <!-- <th class="border border-gray-300 px-4 py-2">Tokens</th> -->
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($payment = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['pmid']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['pmcreated_at']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['patient_id']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['appointment_id']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['pmtransaction_id']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['pmamount']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['payment_method']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $payment['pmstatus']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="payments.php?delete=<?php echo $payment['pmid']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
