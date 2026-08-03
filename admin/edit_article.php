<?php
$pageTitle = 'Articles';
include 'includes/sidemenu.php';
include 'includes/header.php';
include '../configs/db.php';

$arid = isset($_GET['arid']) ? $_GET['arid'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Get current image from DB
    $currentImage = '';
    $imageQuery = $conn->query("SELECT arimage FROM articles WHERE arid=$arid");
    if ($imageQuery && $imageQuery->num_rows > 0) {
        $currentImage = $imageQuery->fetch_assoc()['arimage'];
    }

    // Check if new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = 'uploads/articles/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    } else {
        // Keep existing image
        $image = $currentImage;
    }

    $query = "UPDATE articles SET artitle='$title', arcontent='$content', arimage='$image' WHERE arid=$arid";
    if (mysqli_query($conn, $query)) {
        echo '<script type="text/javascript"> alert("Article Updated!"); window.location.assign("articles.php"); </script>';
    } else {
        echo "<script>alert('Error: Could not update article.');</script>";    
    }
}


$result = $conn->query("SELECT * FROM articles WHERE arid=$arid");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = $row['artitle'];
    $content = $row['arcontent'];
    $image = $row['arimage'];
} else {
    $title = '';
    $content = '';
    $image = '';
}


$result = $conn->query("SELECT * FROM articles ORDER BY arcreated_at DESC");


if (isset($_GET["delete"])) {
    $arid = $_GET["delete"];
    $conn->query("DELETE FROM articles WHERE arid=$arid");
    header("Location: articles.php");
}

?>
