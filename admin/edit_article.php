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

<main class="flex-1 ml-52 p-2 overflow-auto mt-14">
    <div class="container mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Edit Article</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Title" required class="w-full p-2 border border-gray-300 rounded mb-3" value="<?php echo $title; ?>">
                <textarea name="content" placeholder="Content" required class="w-full p-2 border border-gray-300 rounded mb-3"><?php echo $content; ?></textarea>
                <!-- <input type="file" name="image" id="imageInput" accept="image/*" class="w-full p-2 border border-gray-300 rounded mb-3" value="<?php echo $image; ?>">
                <img id="previewImage" src="" alt="Image Preview" style="max-width: 200px;"> -->
                <!-- Show existing image -->

                <?php if (!empty($image)): ?>
                    <img src="<?php echo $image; ?>" alt="Current Image" class="mb-3 w-32 h-32 object-cover rounded">
                <?php endif; ?>

                <!-- File input to upload new image -->
                <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded mb-1" accept="image/*">
                <small id="image-error" class="text-red-500 text-sm mb-3 block"></small>

                <!-- <img id="previewImage" class="hidden mb-3 w-32 h-32 object-cover rounded" alt="New Image Preview"> -->


                <button type="submit" name="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Update</button>
                <a href="articles.php" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Cancel</a>
            </form>
        </div>
    </div>

</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.forms["article"];
    const imageInput = form["image"];
    const imageError = document.getElementById("image-error");

    imageInput.addEventListener("change", () => {
        const file = imageInput.files[0];
        if (file && !/\.(jpg|jpeg|png|gif|webp)$/i.test(file.name)) {
            imageInput.classList.add("border-red-500");
            imageError.textContent = "Only image files (.jpg, .jpeg, .png, .gif, .webp) are allowed.";
        } else {
            imageInput.classList.remove("border-red-500");
            imageError.textContent = "";
        }
    });

    window.validateForm = function () {
        imageInput.dispatchEvent(new Event("change"));
        return !imageError.textContent;
    }
});
</script>

