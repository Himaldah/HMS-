<?php
require '../dompdf-3.1.0\dompdf\vendor\autoload.php'; // Adjust path as needed
use Dompdf\Dompdf;

include '../configs/db.php';
session_start();

$appointment_id = $_GET['aid'] ?? null;

$patient_email = $_SESSION['pemail'] ?? null;
$patient_qry = "SELECT * FROM patients WHERE pemail = '$patient_email'";
$patient_result = mysqli_query($conn, $patient_qry);
$patient = mysqli_fetch_assoc($patient_result);
$patient_id = $patient['pid'] ?? null;
$patient_name = $patient['pname'] ?? null;

$appointment_qry = mysqli_query($conn, "SELECT * FROM appointments WHERE aid = $appointment_id");
$appointment = mysqli_fetch_assoc($appointment_qry);
$appointment_date = $appointment['appointment_date'] ?? null;

$report_qry = mysqli_query($conn, "SELECT * FROM reports WHERE pid = $patient_id AND aid = $appointment_id");
$report = mysqli_fetch_assoc($report_qry);

// Doctor and Department Info
$doctor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM doctors WHERE drid = {$appointment['drid']}"));
$department = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM departments WHERE did = {$doctor['did']}"));

// Create HTML for PDF
ob_start();

$age = date_diff(date_create($patient['pdob']), date_create('now'))->y;
?>

<style>
    body { font-family: DejaVu Sans, sans-serif; }
    .section { margin-bottom: 20px; }
    .title { font-size: 22px; font-weight: bold; text-align: center; margin-bottom: 10px; }
    .subtitle { font-size: 16px; font-weight: bold; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 6px 4px; vertical-align: top; }
</style>

<div class="title">HMS - Medical Report</div>


<div class="section">
    <!-- <div class="subtitle">Appointment Info</div> -->
    <table>
    <tr><td><strong>Patient ID:</strong> <?= $patient['pid'] ?></td><td><strong>Appointment ID:</strong> <?= $appointment['aid'] ?></td><td><strong>Appointment Date:</strong> <?= $appointment_date ?></td></tr>
        <!-- <tr><td><strong>Doctor:</strong> <?= $doctor['drname'] ?></td><td><strong>Department:</strong> <?= $department['dname'] ?></td></tr> -->
    </table>
</div>

<hr class="my-4"></hr>


<div class="section">
    <!-- <div class="subtitle">Patient Details</div> -->
    <table>
        <tr><td><strong>Name:</strong> <?= $patient['pname'] ?></td><td><strong>Gender:</strong> <?= $patient['pgender'] ?></td></tr>
        <tr><td><strong>DOB:</strong> <?= $patient['pdob'] ?></td><td><strong>Age:</strong> <?= $age; ?></td></tr>
        <tr><td><strong>Address:</strong> <?= $patient['paddress'] ?></td><td><strong>phone:</strong> <?= $patient['pphone'] ?></td></tr>
        <tr><td><strong>Email:</strong> <?= $patient['pemail'] ?></td></tr>
    </table>
</div>

<hr class="my-4"></hr>  

<div class="section">
    <table>
        <tr><td><strong>Doctor:</strong> <?= $doctor['drname'] ?></td><td><strong>Department:</strong> <?= $department['dname'] ?></td></tr> 
    </table>
</div>

<hr class="my-4"></hr>  

<div class="section">
    <!-- <div class="subtitle">Report</div> -->
    <table>
        <tr><td><strong>Report Date:</strong> <?= $report['report_date'] ?></td></tr>
        <tr><td colspan="2"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($report['report_description'])) ?></td></tr>
    </table>
</div>
