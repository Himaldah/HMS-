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