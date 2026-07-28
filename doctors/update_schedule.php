<?php
include 'includes/header.php';

$drid = $_SESSION['drid'];
$sid = $_GET['sid'] ?? null;

$scheduleqry = "SELECT * FROM doctor_schedule WHERE drid = '$drid' AND sid = '$sid'";
$scheduleres = mysqli_query($conn, $scheduleqry);
$schedule = mysqli_fetch_assoc($scheduleres);


// Update logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['date'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $tokens = $_POST['tokens'];

    $update = "UPDATE doctor_schedule SET available_date = '$date', day = '$day', start_time = '$start_time', end_time = '$end_time', tokens = '$tokens' WHERE drid = '$drid' AND sid = '$sid'";
    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Schedule updated!'); window.location='app_schedules.php';</script>";
    } else {
        echo "<p class='text-red-500'>Error updating profile.</p>";
    }
}
?>

<main class="p-6 max-w-3xl mx-auto pt-20">
    <form method="POST" class="bg-white rounded-lg shadow p-6 hover:shadow-lg hover:shadow-blue-200 transition duration-300" name="schedule" onsubmit="return validateForm()">
        <h2 class="text-3xl font-bold text-blue-900 text-center">Update Schedule</h2>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">Date</label>
            <input type="date" name="date" id="dateInput" class="w-full p-2 border rounded" onchange="showDayName()" value="<?php echo htmlspecialchars($schedule['available_date']); ?>" required>
            <small id="date-error" class="text-red-500 text-sm block mt-1"></small>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Day</label>
            <input type="text" name="day" id="dayOutput" value="<?php echo htmlspecialchars($schedule['day'] ?? ''); ?>" class="w-full p-2 border rounded" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time" value="<?php echo htmlspecialchars($schedule['start_time']); ?>" class="w-full p-2 border rounded" min="10:00" max="16:00" required>
            <small id="time-error" class="text-red-500 text-sm block mt-1"></small>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time" value="<?php echo htmlspecialchars($schedule['end_time']); ?>" class="w-full p-2 border rounded" min="10:00" max="16:00" required>
            <small id="time-error" class="text-red-500 text-sm block mt-1"></small>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Tokens</label>
            <input type="number" name="tokens" id="token_limit" value="<?php echo htmlspecialchars($schedule['tokens']); ?>" class="w-full p-2 border rounded" required>
            <small id="token-error" class="text-red-500 text-sm block mt-1"></small>
        </div>

        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition" onclick="return confirm('Are you sure to update this schedule?')">Save Changes</button>
        <a href="app_schedules.php" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Cancel</a>
    </form>
</main>
