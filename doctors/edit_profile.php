<?php
include 'includes/header.php';

$drid = $_SESSION['drid'];
$qry = "SELECT * FROM doctors WHERE drid = '$drid'";
$res = mysqli_query($conn, $qry);
$doctor = mysqli_fetch_assoc($res);

// Update logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['drname'];
    // $gender = $_POST['pgender'];
    // $dob = $_POST['pdob'];
    $phone = $_POST['drphone'];
    $password = $_POST['drpassword'];
    $email = $_POST['dremail'];

    $update = "UPDATE doctors SET drname = '$name', drphone = '$phone', dremail = '$email', drpassword = '$password' WHERE drid = '$drid'";
    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Profile updated!'); window.location='profile.php';</script>";
    } else {
        echo "<p class='text-red-500'>Error updating profile.</p>";
    }
}
?>

<main class="p-6 max-w-3xl mx-auto pt-20">
    <form method="POST" class="bg-white rounded-lg shadow p-6 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">Edit Profile</h2>

        <div class="mb-4">
            <label class="block text-gray-700">Name</label>
            <input type="text" name="drname" value="<?php echo htmlspecialchars($doctor['drname']); ?>" class="w-full p-2 border rounded">
            <small id="drname-error" class="text-red-500 text-sm mb-3 block"></small>
        </div>

        <!-- <div class="mb-4">
            <label class="block text-gray-700">Gender</label>
            <select name="pgender" class="w-full p-2 border rounded">
                <option value="Male" <?php if($doctor['pgender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($doctor['pgender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if($doctor['pgender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div> -->

        <!-- <div class="mb-4">
            <label class="block text-gray-700">Date of Birth</label>
            <input type="date" name="pdob" value="<?php echo htmlspecialchars($doctor['pdob']); ?>" class="w-full p-2 border rounded">
        </div> -->

        <div class="mb-4">
            <label class="block text-gray-700">Phone</label>
            <input type="text" name="drphone" value="<?php echo htmlspecialchars($doctor['drphone']); ?>" class="w-full p-2 border rounded">
            <small id="drphone-error" class="text-red-500 text-sm mb-3 block"></small>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="dremail" value="<?php echo htmlspecialchars($doctor['dremail']); ?>" class="w-full p-2 border rounded">
            <small id="dremail-error" class="text-red-500 text-sm mb-3 block"></small>
        </div>

        <div class="mb-4 relative">
            <label class="block text-gray-700">Password</label>
            <input type="password" id="passwordField" name="drpassword" value="<?php echo htmlspecialchars($doctor['drpassword']); ?>" class="w-full p-2 border rounded pr-10">
            <span onclick="togglePassword()" class="absolute right-3 top-8 cursor-pointer text-gray-500 hover:text-gray-700">
                <i id="eyeIcon" class="fas fa-eye"></i>
            </span>
            <small id="drpassword-error" class="text-red-500 text-sm mb-3 block"></small>
        </div>

        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">Save Changes</button>
    </form>

    <script>

document.addEventListener("DOMContentLoaded", () => {
    const fields = {
        name: document.querySelector('input[name="drname"]'),
        phone: document.querySelector('input[name="drphone"]'),
        email: document.querySelector('input[name="dremail"]'),
        password: document.querySelector('input[name="drpassword"]'),
    };

    const errors = {
        name: document.getElementById('drname-error'),
        phone: document.getElementById('drphone-error'),
        email: document.getElementById('dremail-error'),
        password: document.getElementById('drpassword-error'),
    };

    fields.name.addEventListener("input", () => {
        const value = fields.name.value.trim();
        if (!/^[a-zA-Z\s]{3,}$/.test(value)) {
            showError('name', "Name must be at least 3 letters, using letters and spaces only.");
        } else {
            clearError('name');
        }
    });

    fields.phone.addEventListener("input", () => {
        const value = fields.phone.value.trim();
        if (!/^(97|98)\d{8}$/.test(value)) {
            showError('phone', "Phone must start with 97 or 98 and be exactly 10 digits.");
        } else {
            clearError('phone');
        }
    });

    fields.email.addEventListener("input", () => {
        const value = fields.email.value.trim();
        const regex = /^[^\d\s][\w.]+@[a-zA-Z\d.]+\.[a-zA-Z]{2,}$/;
        if (value.includes("-") || !regex.test(value)) {
            showError('email', "Enter a valid email (no hyphens or spaces, and must start with a letter or number).");
        } else {
            clearError('email');
        }
    });

    fields.password.addEventListener("input", () => {
        const value = fields.password.value;
        const regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
        if (!regex.test(value)) {
            showError('password', "Password must have 8+ characters, including uppercase, lowercase, number, and special character.");
        } else {
            clearError('password');
        }
    });

    function showError(field, message) {
        fields[field].classList.add("border-red-500");
        errors[field].textContent = message;
    }

    function clearError(field) {
        fields[field].classList.remove("border-red-500");
        errors[field].textContent = "";
    }
});

function togglePassword() {
    const input = document.getElementById("passwordField");
    const icon = document.getElementById("eyeIcon");
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</main>
 