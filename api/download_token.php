<?php
require '../dompdf-3.1.0\dompdf\vendor\autoload.php'; // Dompdf autoload

use Dompdf\Dompdf;

include '../configs/db.php'; // or your DB connection file
session_start();

$appointment_id = $_GET['aid'] ?? null;

if (!$appointment_id) {
    die("Invalid Appointment ID.");
}

// Fetch appointment data
$query = "SELECT a.*, d.drname, d.drprofile, p.pname, p.pgender, p.pphone 
          FROM appointments a 
          JOIN doctors d ON a.drid = d.drid 
          JOIN patients p ON a.pid = p.pid 
          WHERE a.aid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("No appointment found.");
}

ob_start(); // Start output buffering
?>

<!-- HTML for the token PDF -->
<style>
    body {
        font-family: sans-serif;
    }
    .token-container {
        border: 2px dashed #999;
        padding: 20px;
        text-align: center;
    }
    .token-number {
        font-size: 36px;
        color: #d63384;
        margin-top: 10px;
    }
    .info {
        margin: 10px 0;
    }
</style>

<div class="token-container">
    <h2>Appointment Token</h2>
    <hr>
    <p class="info"><strong>Patient Name:</strong> <?php echo htmlspecialchars($data['pname']); ?></p>
    <p class="info"><strong>Phone:</strong> <?php echo htmlspecialchars($data['pphone']); ?></p>
    <p class="info"><strong>Doctor:</strong> <?php echo htmlspecialchars($data['drname']); ?></p>
    <p class="info"><strong>Appointment Date:</strong> <?php echo htmlspecialchars($data['appointment_date']); ?></p>
    <p class="token-number">Token #: <?php echo htmlspecialchars($data['token_num']); ?></p>
</div>

<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Optional: set paper size and orientation
$dompdf->setPaper('A6', 'portrait');

$dompdf->render();
$dompdf->stream("Appointment_Token_{$data['pname']}_{$appointment_id}_{$data['token_num']}.pdf", ["Attachment" => 1]);
exit;
