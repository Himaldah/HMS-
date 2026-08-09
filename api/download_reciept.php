<?php
require '../dompdf-3.1.0/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

include '../configs/db.php';
session_start();

$payment_id = $_GET['pmid'] ?? null;
if (!$payment_id) {
    die("Invalid Payment ID.");
}

// Fetch payment + related data
$query = "
    SELECT pay.*, 
           a.appointment_date, a.token_num, 
           p.pname, p.pphone, 
           d.drname 
    FROM payments pay 
    JOIN appointments a ON pay.appointment_id = a.aid 
    JOIN patients p ON a.pid = p.pid 
    JOIN doctors d ON a.drid = d.drid 
    WHERE pay.pmid = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("No receipt found.");
}

ob_start(); // Buffering HTML
?>

<!-- HTML for Receipt -->
<style>
    body {
        font-family: sans-serif;
        padding: 20px;
    }
    .receipt-container {
        border: 1px solid #ccc;
        padding: 20px;
    }
    .receipt-header {
        text-align: center;
        font-size: 20px;
        margin-bottom: 20px;
        color: #0d6efd;
    }
    .info {
        margin: 8px 0;
    }
</style>

<div class="receipt-container">
    <div class="receipt-header">Payment Receipt</div>
    <p class="info"><strong>Receipt ID:</strong> <?php echo $data['pmid']; ?></p>
    <p class="info"><strong>Patient:</strong> <?php echo $data['pname']; ?> (<?php echo $data['pphone']; ?>)</p>
    <p class="info"><strong>Doctor:</strong> <?php echo $data['drname']; ?></p>
    <p class="info"><strong>Appointment Date:</strong> <?php echo $data['appointment_date']; ?></p>
    <p class="info"><strong>Token #:</strong> <?php echo $data['token_num']; ?></p>
    <hr>
    <p class="info"><strong>Amount Paid:</strong> Rs. <?php echo $data['pmamount']; ?></p>
    <p class="info"><strong>Payment Method:</strong> <?php echo $data['payment_method']; ?></p>
    <p class="info"><strong>Status:</strong> <?php echo ucfirst($data['pmstatus']); ?></p>
    <p class="info"><strong>Paid On:</strong> <?php echo $data['pmcreated_at']; ?></p>
</div>


<?php
$html = ob_get_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();

$filename = "Receipt_{$data['pname']}_{$payment_id}.pdf";
$dompdf->stream($filename, ["Attachment" => 1]);
exit;
?>
