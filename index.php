<?php
include 'includes/header.php';
?>

<header class="relative bg-gradient-to-r from-blue-300 to-pink-300 px-10 pt-24 pb-16 h-screen overflow-hidden">

    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="w-full md:w-1/2 text-center md:text-left space-y-4">
            <h1 class="text-4xl font-bold text-blue-900">Healthcare at Your Fingertips</h1>
            <p class="text-lg text-gray-700">Book appointments, consult doctors, and manage health records online.</p>
            <a href="departments.php" class="bg-pink-500 text-white px-6 py-3 rounded-lg hover:bg-pink-700 inline-block mt-4">
                Book Appointments
            </a>
        </div>
        <div class="w-full md:w-1/2 flex justify-center mt-10 md:mt-0">
            <img src="images/homepage_cover_3.png" class="max-h-[400px] object-contain" alt="Healthcare">
        </div>
    </div>

    <div class="flex grid md:grid-cols-3 gap-8 mt-14 px-10">
            <div class="p-6 rounded-lg shadow-lg hover:scale-100 shadow-blue-200 hover:shadow-white transition">
                <h3 class="text-xl font-semibold mb-2 ">Appointments</h3>
                <p class="text-gray-700">Book medical consultations with doctors.</p>
            </div>
            <div class="p-6  rounded-lg shadow-lg hover:scale-100 transition shadow-blue-200 hover:shadow-white">
                <h3 class="text-xl font-semibold mb-2">Health Records</h3>
                <p class="text-gray-700">Access your medical records securely.</p>
            </div>
            <div class="p-6  rounded-lg shadow-lg hover:scale-100 transition shadow-blue-200 hover:shadow-white">
                <h3 class="text-xl font-semibold mb-2 ">Telemedicine</h3>
                <p class="text-gray-700">Consult doctors online anytime.</p>
            </div> 
</div>
<!-- <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white to-transparent pointer-events-none"></div> -->
</header>





    <!-- Services Section -->
    <!-- <section id="services" class="py-16 text-center ">
        <h2 class="text-3xl font-bold text-blue-900 mb-6">Our Services</h2>     
        <div class="grid md:grid-cols-3 gap-8 px-10">
            <div class="p-6 bg-pink-200 rounded-lg shadow-lg hover:scale-105 transition">
                <h3 class="text-xl font-semibold mb-2 text-blue-900">Appointments</h3>
                <p class="text-gray-700">Book medical consultations with doctors.</p>
            </div>
            <div class="p-6 bg-pink-200 rounded-lg shadow-lg hover:scale-105 transition">
                <h3 class="text-xl font-semibold mb-2 text-blue-900">Health Records</h3>
                <p class="text-gray-700">Access your medical records securely.</p>
            </div>
            <div class="p-6 bg-pink-200 rounded-lg shadow-lg hover:scale-105 transition">
                <h3 class="text-xl font-semibold mb-2 text-blue-900">Telemedicine</h3>
                <p class="text-gray-700">Consult doctors online anytime.</p>
            </div>
        </div>
    </section> -->

<?php

// Handle search query
$search = $_GET['search'] ?? '';

// Pagination setup
$limit = 3; // articles per page
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

// Count total for pagination
$count_qry = "SELECT COUNT(*) as total FROM articles WHERE artitle LIKE '%$search%'";
$count_result = mysqli_query($conn, $count_qry);
$total_articles = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_articles / $limit);

// Fetch paginated + filtered articles
$query = "SELECT * FROM articles WHERE artitle LIKE '%$search%' ORDER BY arcreated_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>


<section id="articles" class="py-16">
    <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Latest Health Articles And Notices</h2> 

    <div class="text-center my-6">
    <form method="GET" action="index.php#articles" class="flex justify-center gap-2">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search articles..." class="p-2 border rounded-lg w-64">
        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Search</button>
    </form>
    </div>

    <?php
    // Setup
    $limit = 3; // articles per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    // Count total articles
    $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM articles");
    $total_articles = mysqli_fetch_assoc($count_result)['total'];
    $total_pages = ceil($total_articles / $limit);

    // Fetch paginated articles
    $articles = mysqli_query($conn, "SELECT * FROM articles ORDER BY arcreated_at DESC LIMIT $limit OFFSET $offset");
    ?>

    <div class="grid md:grid-cols-3 gap-8 px-10">
        <?php while ($row = mysqli_fetch_assoc($articles)): ?>
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg hover:shadow-blue-200 transition duration-300 border border-grey-200">
                <?php if (!empty($row['arimage'])): ?>
                    <img src="admin/<?php echo htmlspecialchars($row['arimage']); ?>" class="w-full h-48 object-contain rounded mb-3" alt="<?php echo htmlspecialchars($row['artitle']); ?>">
                <?php endif; ?>
                <a href="article.php?arid=<?php echo $row['arid']; ?>"><h3 class="text-lg font-semibold text-blue-900"><?php echo htmlspecialchars($row['artitle']); ?></h3></a>
                <p class="text-sm mt-2"><?php echo substr(strip_tags($row['arcontent']), 0, 100); ?>...</p>
                <a href="article.php?arid=<?php echo $row['arid']; ?>" class="mt-4 inline-block bg-pink-500 text-white px-3 py-2 rounded-lg hover:bg-pink-600 text-sm">Read More</a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="flex justify-center mt-8 gap-2">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>#articles"
               class="px-4 py-2 border rounded-lg text-sm 
               <?php echo ($i == $page) ? 'bg-pink-500 text-white' : 'bg-white text-blue-900 border-blue-300 hover:bg-blue-100'; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</section>


<?php
    include 'includes/footer.php';
?>
