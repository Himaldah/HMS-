<?php
$pageTitle = 'Reports   ';
include 'includes/sidemenu.php';
include 'includes/header.php';
include 'api/admin_notification.php';

$reports_query = "SELECT * FROM reports ORDER BY created_at DESC";
$result = $conn->query($reports_query);

if (isset($_GET["delete"])) {
    $rid = $_GET["delete"];
    $conn->query("DELETE FROM reports WHERE rid=$rid");
    header("Location: reports.php");
    $_SESSION['toast'] = ['message' => 'Report has been deleted.', 'type' => 'success'];
} 
?>

        <main class="flex-1 ml-52 p-2 overflow-auto mt-14">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Reports List</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">RID</th>
                        <th class="border border-gray-300 px-4 py-2">AID</th>
                        <th class="border border-gray-300 px-4 py-2">PID</th>
                        <th class="border border-gray-300 px-4 py-2">DRID</th>
                        <th class="border border-gray-300 px-4 py-2">Created At</th>
                        <th class="border border-gray-300 px-4 py-2">Title</th>
                        <th class="border border-gray-300 px-4 py-2">Description</th>
                        <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">File</th>
                        <!-- <th class="border border-gray-300 px-4 py-2">Tokens</th> -->
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $report['rid']; ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="appointments.php?aid=<?php echo $report['aid']; ?>"><?php echo $report['aid']; ?></a></td>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="patients.php?pid=<?php echo $report['pid']; ?>"><?php echo $report['pid']; ?></a></td>

                            <?php 
                            $aid = $report['aid'];
                            $query = "SELECT drid FROM appointments WHERE aid = '$aid'";
                            $result1 = $conn->query($query);
                            $row = $result1->fetch_assoc();
                            $drid = $row['drid'];
                            ?>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="doctors.php?drid=<?php echo $drid; ?>"><?php echo $drid; ?></a></td>


                            <td class="border border-gray-300 px-4 py-2"><?php echo $report['created_at']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $report['report_title']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $report['report_description']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $report['report_date']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $report['file_path']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="view_report.php?rid=<?php echo $report['rid']; ?>" class="text-red-500 hover:text-red-700">View</a>
                                <a href="reports.php?delete=<?php echo $report['rid']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
