<?php
include 'includes/header.php';
include 'configs/db.php'; // adjust path if needed

$article_id = $_GET['arid'] ?? null;

if (!$article_id) {
    echo "<p class='text-red-500 text-center mt-6'>No article specified.</p>";
    exit;
}

$query = "SELECT * FROM articles WHERE arid = '$article_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    echo "<p class='text-red-500 text-center mt-6'>Article not found.</p>";
    exit;
}

$article = mysqli_fetch_assoc($result);
?>

<main class="max-w-3xl mx-auto p-6 bg-white mt-20 shadow rounded hover:shadow-lg hover:shadow-blue-200 transition duration-300  ">

    <h1 class="text-3xl font-bold text-blue-800 mb-4"><?php echo htmlspecialchars($article['artitle']); ?></h1>

    <?php if (!empty($article['arimage'])): ?>
        <img src="admin/<?php echo htmlspecialchars($article['arimage']); ?>" class="w-full h-64 object-contain rounded mb-4">
    <?php endif; ?>
    
    
    <p class="text-sm text-gray-500 mb-6">Published on <?php echo date('F j, Y', strtotime($article['arcreated_at'])); ?></p>
    
    <div class="text-gray-800 leading-relaxed">
        <?php echo nl2br(htmlspecialchars($article['arcontent'])); ?>
    </div>
    <div class="text-center mt-6">
        <a href="index.php#articles" class="mt-4 inline-block bg-pink-500 text-white px-3 py-2 rounded-lg hover:bg-pink-600 text-sm"><i class="fas fa-circle-arrow-left"></i> Back to Articles</a>
    </div>

</main>

<?php include 'includes/footer.php'; ?>