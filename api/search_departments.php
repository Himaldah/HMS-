<?php
include '../configs/db.php'; // or adjust the path to your db connection

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "SELECT departments.*, COUNT(doctors.drid) AS doctor_count 
          FROM departments 
          LEFT JOIN doctors ON departments.did = doctors.did";

if (!empty($search)) {
    $query .= " WHERE departments.dname LIKE '%$search%' OR departments.ddescription LIKE '%$search%'";
}

$query .= " GROUP BY departments.did";

$result = $conn->query($query);

$html = '';

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '
        <div class="bg-white shadow-md rounded-lg p-3 border border-gray-200 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300">
            <img src="admin/' . htmlspecialchars($row['dicon_path']) . '" alt="' . htmlspecialchars($row['dname']) . '" class="w-16 h-16 mx-auto mb-3 rounded-xl border-2 border-blue-200">
            <h2 class="text-lg font-semibold text-blue-900">' . htmlspecialchars($row['dname']) . '</h2>
            <p class="text-sm text-gray-600">' . htmlspecialchars($row['ddescription']) . '</p>
            <p class="text-gray-800 font-medium mt-2 text-sm">Doctors Available: ' . $row['doctor_count'] . '</p>
            <a href="doctors.php?department_id=' . $row['did'] . '" class="mt-4 inline-block bg-pink-500 text-white px-3 py-2 rounded-lg hover:bg-pink-600 text-sm">
                View Doctors
            </a>
        </div>';
    }
} else {
    $html = '<p class="text-center text-gray-600 col-span-full">No departments found.</p>';
}

echo $html;
?>
