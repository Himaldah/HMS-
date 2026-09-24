<?php
include 'configs/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['nsemail']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address!'); window.history.back();</script>";
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (nsemail) VALUES (?)");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "<script>alert('Subscribed successfully!'); window.history.back();</script>";
    } else {
        if ($conn->errno == 1062) { // Duplicate entry
            echo "<script>alert('This email is already subscribed.'); window.history.back();</script>";
        } else {
            echo "<script>alert('Something went wrong.'); window.history.back();</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
