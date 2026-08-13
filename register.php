<?php
include 'includes/header.php';
?>
<div class="bg-white p-8 rounded-lg shadow-md w-96 mx-auto mt-20 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    <h2 class="text-3xl text-center font-bold text-blue-900 mb-6">Patient Register</h2>
        
        <form method="POST" action="" name="sign-up" onsubmit="return validateForm()">
            <!-- Full Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Full Name</label>
                <input type="text" name="name" id="name" required class="w-full px-3 py-2 border rounded-md" minlength="3">
                <span id="name-error" class="text-red-500 text-sm mt-1 block"></span> 
            </div>

            <!-- Gender -->
            <div class="mb-4">
                <label class="block text-gray-700">Gender</label>
                <select name="gender" id="gender" required class="w-full px-3 py-2 border rounded-md">
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <span id="gender-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>

            <!-- DOB -->
            <div class="mb-4">
                <label class="block text-gray-700">Date of Birth</label>
                <input type="date" name="dob" id="dob" required class="w-full px-3 py-2 border rounded-md">
                <span id="dob-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label class="block text-gray-700">Phone</label>
                <input type="number" name="phone" id="phone" required class="w-full px-3 py-2 border rounded-md">
                <span id="phone-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label class="block text-gray-700">Address</label>
                <input type="text" name="address" id="address" required class="w-full px-3 py-2 border rounded-md">
                <span id="address-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="w-full px-3 py-2 border rounded-md">
                <span id="email-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <label class="block text-gray-700">Password</label>
                <input type="password" id="passwordField" name="password" required class="w-full px-3 py-2 border rounded-md">
                <span onclick="togglePassword()" class="absolute right-3 top-8 cursor-pointer text-gray-500 hover:text-gray-700">
                    <i id="eyeIcon" class="fas fa-eye"></i>
                </span>
                <span id="password-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>

            <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded-md hover:bg-pink-600 transition">Register</button>
        </form>

    </div>
    <?php
    include 'includes/footer.php';
?>

