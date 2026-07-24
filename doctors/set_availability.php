<div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md pt-2 hover:shadow-lg hover:shadow-blue-200 transition duration-300 mt-20">
    <h2 class="text-3xl font-bold text-blue-900 text-center">Set Your Availability</h2>

    <form method="POST" class="space-y-6" name="schedule" onsubmit="return validateForm()">
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-md shadow-sm mt-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Available Date</label>
            <input type="date" name="available_date" id="available_date" class="p-2 border border-gray-300 rounded-md text-sm" onchange="setDayName(this)" required>
            <small id="date-error" class="text-red-500 text-sm block mt-1"></small>

            <input type="text" name="day" id="dayOutput" class="w-full p-2 border rounded hidden mt-2" readonly>
            

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="flex flex-col">
                    <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-1">Start Time</label>
                    <input type="time" name="start_time" id="start_time" class="p-2 border border-gray-300 rounded-md text-sm" min="10:00" max="16:00" required>
                    <small id="time-error" class="text-red-500 text-sm block mt-1"></small>
                </div>
                <div class="flex flex-col">
                    <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-1">End Time</label>
                    <input type="time" name="end_time" id="end_time" class="p-2 border border-gray-300 rounded-md text-sm" min="10:00" max="16:00" required>
                    <small id="time-error" class="text-red-500 text-sm block mt-1"></small>
                </div>
            </div>

            <label class="block text-sm font-semibold text-gray-700 mt-4">Token Number</label>
            <input type="number" name="token_limit" id="token_limit" class="p-2 border border-gray-300 rounded-md text-sm mt-2" placeholder="Token Number" required>
            <small id="token-error" class="text-red-500 text-sm block mt-1"></small>
        </div>

        <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-medium py-2 rounded-lg">
            Save Schedule
        </button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("available_date");
    const startInput = document.getElementById("start_time");
    const endInput = document.getElementById("end_time");
    const tokenInput = document.getElementById("token_limit");

    // Attach live validation events
    dateInput.addEventListener("change", validateDate);
    startInput.addEventListener("input", validateTime);
    endInput.addEventListener("input", validateTime);
    tokenInput.addEventListener("input", validateToken);

    function setDayName(inputElem) {
        const value = inputElem.value;
        if (value) {
            const date = new Date(value);
            const options = { weekday: 'long' };
            const dayName = new Intl.DateTimeFormat('en-US', options).format(date);
            document.getElementById("dayOutput").value = dayName;
            validateDate();
        }
    }
    window.setDayName = setDayName; // Expose to inline HTML

    function parseTime(timeStr) {
        const [hours, minutes] = timeStr.split(":").map(Number);
        return hours * 60 + minutes;
    }

    function validateDate() {
        const date = new Date(dateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (!dateInput.value) {
            showError("date-error", "Please select a date.");
            return false;
        } else if (date < today) {
            showError("date-error", "Please select a future date.");
            return false;
        } else {
            showError("date-error", "");
            return true;
        }
    }

    function validateTime() {
        const start = startInput.value;
        const end = endInput.value;

        if (!start || !end) {
            showError("time-error", "Both start and end time are required.");
            return false;
        }

        const startMin = parseTime(start);
        const endMin = parseTime(end);
        const minTime = parseTime("10:00");
        const maxTime = parseTime("16:00");

        if (startMin < minTime || endMin > maxTime) {
            showError("time-error", "Time must be between 10:00 AM and 4:00 PM.");
            return false;
        }

        if (startMin >= endMin) {
            showError("time-error", "Start time must be before end time.");
            return false;
        }

        showError("time-error", "");
        return true;
    }

    function validateToken() {
        const val = parseInt(tokenInput.value, 10);
        if (isNaN(val) || val < 1) {
            showError("token-error", "Token number must be a positive number.");
            return false;
        } else {
            showError("token-error", "");
            return true;
        }
    }

    function showError(id, msg) {
        document.getElementById(id).textContent = msg;
    }

    window.validateForm = function () {
        const validDate = validateDate();
        const validTime = validateTime();
        const validToken = validateToken();

        return validDate && validTime && validToken;
    };
});
</script>
 
