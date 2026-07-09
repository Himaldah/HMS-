<?php
$pageTitle = 'Articles';
include 'includes/sidemenu.php';
include 'includes/header.php';
include '../configs/db.php';
include 'api/admin_notification.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image = 'uploads/articles/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $query = "INSERT INTO articles (artitle, arcontent, arimage) VALUES ('$title', '$content', '$image')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Article added!');</script>";
    } else {
        echo "<script>alert('Error: Could not add article.');</script>";    
    }
}

$result = $conn->query("SELECT * FROM articles ORDER BY arcreated_at DESC");

if (isset($_GET["delete"])) {
    $arid = $_GET["delete"];
    $conn->query("DELETE FROM articles WHERE arid=$arid");
    header("Location: articles.php");
    $_SESSION['toast'] = ['message' => 'Article has been deleted.', 'type' => 'success'];
}

?>

<main class="flex-1 ml-52 p-2 overflow-auto mt-14">
    <div class="container mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Add New Article</h2>
            <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()" name="article">
                <input type="text" name="title" placeholder="Title" required class="w-full p-2 border border-gray-300 rounded mb-3">
                <textarea name="content" placeholder="Content" required class="w-full p-2 border border-gray-300 rounded mb-3"></textarea>
                <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded mb-1" accept="image/*">
                <small id="image-error" class="text-red-500 text-sm mb-3 block"></small>
                <button type="submit" name="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Publish</button>
            
            </form>
        </div>
    </div>


 <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold text-blue-700 mb-4">Articles List</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Created At</th>
                    <th class="border border-gray-300 px-4 py-2">Title</th>
                    <th class="border border-gray-300 px-4 py-2">Content</th>
                    <th class="border border-gray-300 px-4 py-2">Image</th>
                    <th class="border border-gray-300 px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="text-center">
                        <td class="border border-gray-300 px-4 py-2"><?php echo $row['arid']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $row['arcreated_at']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $row['artitle']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo substr($row['arcontent'], 0, 200) . '...'; ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <img src="<?php echo htmlspecialchars($row['arimage']); ?>" class="w-10 h-10 mx-auto">
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_article.php?arid=<?php echo $row['arid']; ?>" class="text-green-500 hover:text-green-700">Edit</a>
                            <a href="articles.php?delete=<?php echo $row['arid']; ?>" 
                               class="text-red-500 hover:text-red-700" 
                               onclick="return confirm('Are you sure to delete?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
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


</main> 
