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