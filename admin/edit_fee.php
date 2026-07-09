<?php
$pageTitle = 'Fees';
include 'includes/sidemenu.php';
include 'includes/header.php';
include '../configs/db.php';

$fid = $_GET['fid'] ?? null;

$fee_query = "SELECT * FROM appointment_fees WHERE fid = $fid";     
$result2 = $conn->query($fee_query);
$fee = $result2->fetch_assoc();

$did = $fee['did'] ?? 0;

$departments_qry = $conn->query("SELECT * FROM departments WHERE did = $did");
$department = $departments_qry->fetch_assoc();
$department_name = $department['dname'] ?? '';


if (isset($_POST['submit'])) {
    $amount = $_POST['amount'];


    $query = "UPDATE appointment_fees SET famount='$amount' WHERE fid=$fid";
    if (mysqli_query($conn, $query)) {
        echo '<script type="text/javascript"> alert("Fee Updated!"); window.location.assign("fees.php"); </script>';
    } else {
        echo "<script>alert('Error: Could not update fee.');</script>";    
    }

}

?>

<main class="flex-1 ml-52 p-2 overflow-auto mt-14">
<div class="container mx-auto">
    <!-- Add Doctor Form -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-blue-700 mb-4">Update Fee</h2>
                <form method="POST" enctype="multipart/form-data">

                    <select name="department" class="w-full p-2 mb-3 border rounded" required>
                            <option value="<?php echo $did; ?>" disabled selected><?php echo $department_name; ?></option>
                    </select>

                    <input type="number" name="amount" placeholder="Enter Amount" required class="w-full p-2 border border-gray-300 rounded mb-3" value="<?php echo $fee['famount'] ?>">
                    <button type="submit" name="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Update Fee</button>
                
                </form>
    </div>

</div>

</main>

</body>
</html>


