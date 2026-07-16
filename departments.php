<?php
include 'includes/header.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "SELECT departments.*, COUNT(doctors.drid) AS doctor_count 
          FROM departments 
          LEFT JOIN doctors ON departments.did = doctors.did";

if (!empty($search)) {
    $query .= " WHERE departments.dname LIKE '%$search%' OR departments.ddescription LIKE '%$search%'";
}

$query .= " GROUP BY departments.did";

$result = $conn->query($query);
?>

?>
    
<body class="bg-white text-gray-800 min-h-screen flex flex-col">

    <!-- Page Header -->
    <header class="text-black text-center py-6 pt-14">
        <h1 class="text-3xl text-center font-bold text-blue-900"> Departments </h1>
    </header>

    <form method="GET" action="" class="max-w-md mx-auto my-6 text-center">
        <input type="text" name="search" id="searchInput" placeholder="Search depart    ments..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="w-2/3 px-4 py-2 border rounded-lg">
        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Search</button>
    </form>


    <!-- Departments Grid -->
    <div class="container mx-auto p-6 flex-grow">
        <div id="departmentsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"> <!-- Reduced gap and columns -->

        <!-- <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200 text-center">
                    <img src="" alt="" class="w-20 h-20 mx-auto mb-3">
                    <h2 class="text-xl font-semibold text-blue-700">hjhj</h2>
                    <p class="text-gray-600">hjh</p>
                    <p class="text-gray-800 font-medium mt-2">Doctors Available: 2</p>
                    <a href="doctors.php?department_id="
                        class="mt-4 inline-block bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                        View Doctors
                    </a>
                </div> -->

    <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white"> <!-- Reduced padding -->
                        <img src="admin/<?php echo $row['dicon_path']; ?>" alt="<?php echo $row['dname']; ?>" class="w-16 h-16 mx-auto mb-3 rounded-xl border-2 border-blue-200"> <!-- Reduced image size -->
                        <h2 class="text-lg font-semibold text-blue-900"><?php echo $row['dname']; ?></h2> <!-- Reduced font size -->
                        <p class="text-sm text-gray-600"><?php echo $row['ddescription']; ?></p> <!-- Reduced text size -->
                        <p class="text-gray-800 font-medium mt-2 text-sm">Doctors Available: <?php echo $row['doctor_count']; ?></p> <!-- Reduced text size -->
                        <a href="doctors.php?department_id=<?php echo $row['did']; ?>"
                            class="mt-4 inline-block bg-pink-500 text-white px-3 py-2 rounded-lg hover:bg-pink-600 text-sm"> <!-- Reduced button size -->
                            View Doctors 
                        </a>
                    </div>

                <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center text-gray-600 col-span-full">No departments found for "<?php echo htmlspecialchars($search); ?>"</p>
    <?php endif; ?>
        </div>
    </div>

<script>
    document.getElementById("searchInput").addEventListener("input", function () {
        let searchQuery = this.value;

        fetch("api/search_departments.php?search=" + encodeURIComponent(searchQuery))
            .then(response => response.text())
            .then(data => {
                document.getElementById("departmentsGrid").innerHTML = data;
            });
    });
</script>


</body>
</html>

<?php
    include 'includes/footer.php';
?>
 