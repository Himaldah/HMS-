<?php
include 'configs/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];  
    $address = $_POST['address'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_BCRYPT);
    
    $otp = rand(100000, 999999);

    $_SESSION['email'] = $email;
    $_SESSION['otp'] = $otp;


    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM patients WHERE pemail = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered!');</script>";
    } else {
        // Insert user
        $insert = mysqli_query($conn, "INSERT INTO patients (pname, pemail, pphone, pgender, pdob, paddress, ppassword, otp_code) VALUES ('$name', '$email', '$phone', '$gender', '$dob', '$address', '$password', '$otp')");
        
        if ($insert) {
            // Send OTP via email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'contact.ohcms@gmail.com';
                $mail->Password   = 'ipkh fvbc wura kkeq';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
        
                $mail->setFrom('contact.ohcms@gmail.com', 'HealthCare System');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Email Verification';
                $mail->Body    = "<p>Hello $name,</p><p>Your OTP is: <strong>$otp</strong></p>";
        
                $mail->send();
                // echo "<script>window.location.href='verify_otp.php';</script>";
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                echo "<script>alert('Registration successful, but OTP email failed: {$mail->ErrorInfo}');</script>";
            }
        } else {
            echo "<script>alert('Registration failed!');</script>";
        }
    }
}
?>

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



<script>
    function showError(field, message) {
        document.getElementById(field + "-error").textContent = message;
    }

    function clearError(field) {
        document.getElementById(field + "-error").textContent = "";
    }

    // Real-time validation
    document.getElementById("name").addEventListener("input", function () {
        const name = this.value;
        if (name.length < 3) {
            showError("name", "Name must be at least 3 characters long.");
        } else if (!/^[a-zA-Z\s]+$/.test(name)) {
            showError("name", "Only letters and spaces are allowed.");
        } else {
            clearError("name");
        }
    });

    document.getElementById("phone").addEventListener("input", function () {
        const phone = this.value;
        if (!/^(97|98)\d{8}$/.test(phone)) {
            showError("phone", "Phone must start with 97 or 98 and be exactly 10 digits.");
        } else {
            clearError("phone");
        }
    });

    document.getElementById("email").addEventListener("input", function () {
        const email = this.value;
        const emailRegex = /^[^\d\s][\w.]+@[a-zA-Z\d.]+\.[a-zA-Z]{2,}$/;
        if (email.includes('-') || !emailRegex.test(email)) {
            showError("email", "Enter a valid email (no hyphens or spaces, and must start with a letter or number).");
        } else {
            clearError("email");
        }
    });

    document.getElementById("passwordField").addEventListener("input", function () {
        const password = this.value;
        const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/;
        if (!passwordRegex.test(password)) {
            showError("password", "Min 8 chars, with uppercase, lowercase, number, and special character.");
        } else {
            clearError("password");
        }
    });

    document.getElementById("dob").addEventListener("change", function () {
        const dob = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (dob > today) {
            showError("dob", "DOB cannot be in the future.");
        } else {
            clearError("dob");
        }
    });

    document.getElementById("gender").addEventListener("change", function () {
        if (this.value === "") {
            showError("gender", "Please select a gender.");
        } else {
            clearError("gender");
        }
    });

    document.getElementById("address").addEventListener("input", function () {
        const address = this.value.trim();
        if (address.length < 3) {
            showError("address", "Address must be at least 3 characters.");
        } else {
            clearError("address");
        }
    });

    // Final check on submit
    document.querySelector("form[name='sign-up']").addEventListener("submit", function (e) {
        const errors = document.querySelectorAll("span[id$='-error']");
        let hasError = false;
        errors.forEach(span => {
            if (span.textContent !== "") {
                hasError = true;
            }
        });
        if (hasError) {
            e.preventDefault();
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

