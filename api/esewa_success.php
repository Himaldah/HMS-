<?php
session_start();
include('../configs/db.php');

if (!isset($_SESSION['appointment'])) {
    echo "No appointment data found.";
    exit();
}

$data = $_SESSION['appointment'];

$patient_id = $data['patient_id'];
$transaction_id = $_SESSION['transaction_uuid'];
$amount = $data['fee'];

$drid = $data['doctor_id'];
$did = $data['did'];
$appointment_date = $data['appointment_date'];
$is_self = $data['is_self'];

if ($is_self == 1) {
    $pname = $conn->real_escape_string($data['patient_name']);
    $pphone = $conn->real_escape_string($data['patient_phone']);
    $gender = $conn->real_escape_string($data['gender']);
    $dob = $conn->real_escape_string($data['dob']);
} else {
    $pname = $conn->real_escape_string($data['other_name']);
    $pphone = $conn->real_escape_string($data['other_phone']);
    $gender = $conn->real_escape_string($data['other_gender']);
    $dob = $conn->real_escape_string($data['other_dob']);
}

$pid = $data['patient_id'];

// count existing appointments for token number
$token_result = $conn->query("SELECT COUNT(*) AS booked FROM appointments WHERE drid = '$drid' AND appointment_date = '$appointment_date'");
$token_data = $token_result->fetch_assoc();
$token_number = $token_data['booked'] + 1;

// insert into appointments table
$insert = "INSERT INTO appointments (pid, drid, did, pname, pphone, other_gender, other_dob, appointment_date, token_num, is_self)
           VALUES ('$pid', '$drid', '$did', '$pname', '$pphone', '$gender', '$dob', '$appointment_date', '$token_number', '$is_self')";

if ($conn->query($insert) === TRUE) {

    $appointment_id = $conn->insert_id;

    $insert_payment = "INSERT INTO payments (patient_id, appointment_id, pmtransaction_id, pmamount, payment_method)
                       VALUES ('$patient_id', '$appointment_id', '$transaction_id', '$amount', 'eSewa')";
    if (!$conn->query($insert_payment)) {
        error_log("Payment insert error: " . $conn->error);
    }

    $_SESSION['appointment_date'] = $appointment_date;
    $_SESSION['token_number'] = $token_number;
    $_SESSION['appointment_id'] = $appointment_id;

    // $payment_id = $conn->insert_id;
    // $_SESSION['payment_id'] = $payment_id;


    echo "<script>alert('Appointment booked successfully. Token number: $token_number'); window.location='../booking_success.php';</script>";
} else {
    echo "Error: " . $conn->error;
}


// clear session
unset($_SESSION['appointment']);
unset($_SESSION['transaction_uuid']);
?>
