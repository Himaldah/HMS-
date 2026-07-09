<?php
$pageTitle = 'Doctors';
include 'includes/sidemenu.php';
include 'includes/header.php';
include '../configs/db.php';
include 'api/admin_notification.php';

// Handle Doctor Addition
if (isset($_POST['submit'])) {
    $drname = $_POST['drname'];
    $drdepartment = intval($_POST['drdepartment']);
    $drphone = $_POST['drphone'];
    $dremail = $_POST['dremail'];
    // $drpassword = password_hash($_POST['drpassword'], PASSWORD_DEFAULT);
    $drpassword = $_POST['drpassword'];

    // File Upload Handling
    $target_dir = "uploads/doctors/";
    $file_name = basename($_FILES["drprofile"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["drprofile"]["tmp_name"], $target_file)) {
        // Save path relative to the project root
        $file_path = "uploads/doctors/" . $file_name;

        $stmt = $conn->prepare("INSERT INTO doctors (did, drname, drprofile, drphone, dremail, drpassword) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $drdepartment, $drname, $file_path, $drphone, $dremail, $drpassword);
        $stmt->execute();

        if($stmt->affected_rows > 0) {
            $_SESSION['toast'] = ['message' => 'Doctor has been added.', 'type' => 'success'];
        } else {
            echo "<script>alert('Error adding doctor. Please try again.');</script>";
        }
        $stmt->close();
    }

    header("Location: doctors.php");
    exit();
}

// Handle Doctor Deletion
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM doctors WHERE drid=$id");
    header("Location: doctors.php");
    $_SESSION['toast'] = ['message' => 'Doctor has been deleted.', 'type' => 'success'];
}

// Fetch departments and doctors
$departments = $conn->query("SELECT * FROM departments");
$result = $conn->query("SELECT doctors.*, departments.dname FROM doctors JOIN departments ON doctors.did = departments.did");
?>

<main class="flex-1 ml-52 p-2 overflow-auto mt-14">
<div class="container mx-auto">
    <!-- Add Doctor Form -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-blue-700 mb-4">Add New Doctor</h2>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()" name="doctor">
            <input type="file" name="drprofile" required class="w-full p-2 border border-gray-300 rounded mb-1" accept="image/*">
            <small id="drprofile-error" class="text-red-500 text-sm mb-3 block"></small>

            <input type="text" name="drname" placeholder="Doctor Name" required class="w-full p-2 border border-gray-300 rounded mb-1">
            <small id="drname-error" class="text-red-500 text-sm mb-3 block"></small>
            
            <select name="drdepartment" class="w-full p-2 mb-1 border rounded" required>
            <option value="" disabled selected>Select Department</option>
            <?php while ($row = $departments->fetch_assoc()): ?>
                <option value="<?php echo $row['did']; ?>"><?php echo htmlspecialchars($row['dname']); ?></option>
            <?php endwhile; ?>
            </select>
            <small id="drdepartment-error" class="text-red-500 text-sm mb-3 block"></small>


            <input type="number" name="drphone" placeholder="Doctor Phone Number" required class="w-full p-2 border border-gray-300 rounded mb-1">
            <small id="drphone-error" class="text-red-500 text-sm mb-3 block"></small>  

            <input type="email" name="dremail" placeholder="Doctor Email" required class="w-full p-2 border border-gray-300 rounded mb-1">
            <small id="dremail-error" class="text-red-500 text-sm mb-3 block"></small>

            <div class="relative">
                <input type="password" name="drpassword" id="passwordField" placeholder="Doctor Password" required class="w-full p-2 border border-gray-300 rounded mb-1">
                <small id="drpassword-error" class="text-red-500 text-sm mb-3 block"></small>   
                <span onclick="togglePassword()" class="absolute right-3 top-2 cursor-pointer text-gray-500 hover:text-gray-700">
                    <i id="eyeIcon" class="fas fa-eye"></i>
                </span>
            </div>

            <button type="submit" name="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600" onclick="return confirm('Are you sure to add?')">
                Add Doctor
            </button>
        </form>
    </div>

    <!-- Doctors Table -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-blue-700 mb-4">Doctor List</h2>
                <form method="POST" action="api/export_excel.php">
                    <input type="text" name="pagetitle" value="<?php echo $pageTitle; ?>" hidden>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 mb-4 rounded hover:bg-green-600"><i class="fa-solid fa-table"></i> Export to Excel</button>
                </form>
            </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Profile</th>
                    <th class="border border-gray-300 px-4 py-2">Name</th>
                    <th class="border border-gray-300 px-4 py-2">Department</th>
                    <th class="border border-gray-300 px-4 py-2">Phone</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Password</th>
                    <th class="border border-gray-300 px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="text-center">
                        <td class="border border-gray-300 px-4 py-2"><?php echo $row['drid']; ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <img src="<?php echo htmlspecialchars($row['drprofile']); ?>" class="w-10 h-10 mx-auto">
                        </td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['drname']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['dname']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['drphone']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['dremail']); ?></td>
                        <?php $passlenghth = strlen($row['drpassword']); ?>
                        <td class="border border-gray-300 px-4 py-2"><?php echo str_repeat('*', $passlenghth); ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_doctors.php?drid=<?php echo $row['drid'] ?>" class="text-green-500 hover:text-green-700">Edit</a>
                            <!-- <a href="doctors.php?delete=<?php echo $row['drid']; ?>" 
                               class="text-red-500 hover:text-red-700" 
                               onclick="return confirm('Are you sure to delete?')">Delete</a> -->
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>

document.addEventListener("DOMContentLoaded", () => {
    const fields = {
        name: document.querySelector('input[name="drname"]'),
        phone: document.querySelector('input[name="drphone"]'),
        email: document.querySelector('input[name="dremail"]'),
        password: document.querySelector('input[name="drpassword"]'),
        image: document.querySelector('input[name="drprofile"]'),
        department: document.querySelector('select[name="drdepartment"]')
    };

    const errors = {
        name: document.getElementById('drname-error'),
        phone: document.getElementById('drphone-error'),
        email: document.getElementById('dremail-error'),
        password: document.getElementById('drpassword-error'),
        image: document.getElementById('drprofile-error'),
        department: document.getElementById('drdepartment-error')
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

    fields.image.addEventListener("change", () => {
        const file = fields.image.files[0];
        if (!file || !/\.(jpg|jpeg|png|gif|webp)$/i.test(file.name)) {
            showError('image', "Only image files (.jpg, .jpeg, .png, .gif, .webp) are allowed.");
        } else {
            clearError('image');
        }
    });

    fields.department.addEventListener("change", () => {
        if (!fields.department.value) {
            showError('department', "Please select a department.");
        } else {
            clearError('department');
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

</body>
</html>


