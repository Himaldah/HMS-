<?php
$pageTitle = 'Doctors';
include 'includes/sidemenu.php';
include 'includes/header.php';

$drid = isset($_GET['drid']) ? intval($_GET['drid']) : 0;
$doctor_query = $conn->prepare("SELECT * FROM doctors WHERE drid = ?");
$doctor_query->bind_param("i", $drid);
$doctor_query->execute();
$doctor_result = $doctor_query->get_result();
$doctor = $doctor_result->fetch_assoc();
$image = $doctor['drprofile'] ?? '';

$did = $doctor['did'] ?? 0;
// $department_query = $conn->prepare("SELECT * FROM departments WHERE did = ?");
// $department_query->bind_param("i", $did);
// $department_query->execute();
// $department_result = $department_query->get_result();
// $department = $department_result->fetch_assoc();

if (isset($_POST['submit'])) {
    $drname = $_POST['drname'];
    $drdepartment = intval($_POST['drdepartment']);
    $drphone = $_POST['drphone'];
    $dremail = $_POST['dremail'];
    // $drpassword = password_hash($_POST['drpassword'], PASSWORD_DEFAULT);
    $drpassword = $_POST['drpassword'];


    // Get current image from DB
    $currentImage = '';
    $imageQuery = $conn->query("SELECT drprofile FROM doctors WHERE drid=$drid");
    if ($imageQuery && $imageQuery->num_rows > 0) {
        $currentImage = $imageQuery->fetch_assoc()['drprofile'];
    }

    // Check if new image is uploaded
    if (!empty($_FILES['drprofile']['name'])) {
        $image = 'uploads/doctors/' . basename($_FILES['drprofile']['name']);
        move_uploaded_file($_FILES['drprofile']['tmp_name'], $image);
    } else {
        // Keep existing image
        $image = $currentImage;
    }


    $query = "UPDATE doctors SET drprofile='$image', drname='$drname', did='$drdepartment', drphone='$drphone', dremail='$dremail', drpassword='$drpassword' WHERE drid=$drid";
    if (mysqli_query($conn, $query)) {
        echo '<script type="text/javascript"> alert("Doctor Updated!"); window.location.assign("doctors.php"); </script>';
    } else {
        echo "<script>alert('Error: Could not update doctor.');</script>";    
    }

} 
                                            

// Fetch departments and doctors
$departments = $conn->query("SELECT * FROM departments");
?>

<main class="flex-1 ml-52 p-2 overflow-auto mt-14">
<div class="container mx-auto">
    <!-- Add Doctor Form -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-blue-700 mb-4"> Add New Doctor </h2>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()" name="doctor">

            <?php if (!empty($image)): ?>
                <img src="<?php echo $image; ?>" alt="Current Image" class="mb-3 w-32 h-32 object-cover rounded">
            <?php endif; ?>

            <input type="file" name="drprofile" class="w-full p-2 border border-gray-300 rounded mb-1" accept="image/*">
            <small id="drprofile-error" class="text-red-500 text-sm mb-3 block"></small>


            <input type="text" name="drname" placeholder="Doctor Name" required class="w-full p-2 border border-gray-300 rounded mb-1" value="<?php echo htmlspecialchars($doctor['drname']); ?>">
            <small id="drname-error" class="text-red-500 text-sm mb-3 block"></small> 
            
            <select name="drdepartment" id="drdepartment" class="w-full p-2 mb-3 border rounded" required>
                <option value="" disabled>Select a department</option>
                <?php while ($row = $departments->fetch_assoc()): ?>
                    <option value="<?php echo $row['did']; ?>" <?php echo ($row['did'] == $did) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['dname']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="number" name="drphone" placeholder="Doctor Phone Number" required class="w-full p-2 border border-gray-300 rounded mb-1" value="<?php echo htmlspecialchars($doctor['drphone']); ?>">
            <small id="drphone-error" class="text-red-500 text-sm mb-3 block"></small>


            <input type="email" name="dremail" placeholder="Doctor Email" required class="w-full p-2 border border-gray-300 rounded mb-1" value="<?php echo htmlspecialchars($doctor['dremail']); ?>">
            <small id="dremail-error" class="text-red-500 text-sm mb-3 block"></small>

            <div class="relative">
                <input type="password" name="drpassword" id="passwordField" placeholder="Doctor Password" required class="w-full p-2 border border-gray-300 rounded mb-1" value="<?php echo htmlspecialchars($doctor['drpassword']); ?>">   
                <span onclick="togglePassword()" class="absolute right-3 top-2 cursor-pointer text-gray-500 hover:text-gray-700">
                    <i id="eyeIcon" class="fas fa-eye"></i>
                </span>
                <small id="drpassword-error" class="text-red-500 text-sm mb-3 block"></small>
            </div>
            
            <button type="submit" name="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600" onclick="return confirm('Are you sure to update?')">
                Update
            </button>
            <a href="doctors.php" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Cancel</a>
        </form>
    </div>

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
