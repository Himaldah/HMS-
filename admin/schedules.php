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
            <h2 class="text-xl font-semibold text-blue-700 mb-4"> Schedules List</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">SID</th>
                        <th class="border border-gray-300 px-4 py-2">DRID</th>
                        <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">Day</th>
                        <th class="border border-gray-300 px-4 py-2">Starting Time</th>
                        <th class="border border-gray-300 px-4 py-2">Ending Time</th>
                        <th class="border border-gray-300 px-4 py-2">Tokens</th>
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
