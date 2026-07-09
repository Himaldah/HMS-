<?php
$pageTitle = 'Schedules';
include 'includes/sidemenu.php';
include 'includes/header.php';
include 'api/admin_notification.php';


$schdule_query = "SELECT * FROM doctor_schedule ORDER BY available_date DESC";
$result = $conn->query($schdule_query);

if (isset($_GET["delete"])) {
    $sid = $_GET["delete"];
    $conn->query("DELETE FROM doctor_schedule WHERE sid=$sid ");
    header("Location: schedules.php");
    $_SESSION['toast'] = ['message' => 'Schedule has been deleted.', 'type' => 'success'];
}

?>

        <main class="flex-1 ml-52 p-2 overflow-auto mt-14">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Schedules List</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">SID</th>
                        <th class="border border-gray-300 px-4 py-2">DRID</th>
                        <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">Day</th>
                        <th class="border border-gray-300 px-4 py-2">Start Time</th>
                        <th class="border border-gray-300 px-4 py-2">End Time</th>
                        <th class="border border-gray-300 px-4 py-2">Tokens</th>
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($schedule = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['sid']; ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:text-blue-700"><a href="doctors.php?drid=<?php echo $schedule['drid']; ?>"><?php echo $schedule['drid']; ?></a></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['available_date']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['day']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['start_time']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['end_time']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $schedule['tokens']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="schedules.php?delete=<?php echo $schedule['sid']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
