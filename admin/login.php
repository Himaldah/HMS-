<?php
    include('includes/sidemenu.php');
    include '../configs/db.php';
?>

<div class="w-96 mx-auto mt-10 ">
    <div class="bg-white p-8 rounded-lg shadow-md w-96 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>
        
    <?php if(isset($error)): ?>
        <p class="text-red-500 text-sm text-center"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="" name="login" onsubmit="return validateForm()">
        <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="w-full px-3 py-2 border rounded-md">
                <span id="email-error" class="text-red-500 text-sm mt-1 block"></span>
            </div>
        <div class="mb-4 relative">
            <label class="block text-gray-700">Password</label>
            <input type="password" id="passwordField" name="password" required class="w-full px-3 py-2 border rounded-md">
            <span onclick="togglePassword()" class="absolute right-3 top-8 cursor-pointer text-gray-500 hover:text-gray-700">
                <i id="eyeIcon" class="fas fa-eye"></i>
            </span>
        </div>
        
        <button type="submit" class="w-full bg-pink-500 text-white p-2 rounded hover:bg-pink-600">Login</button>
        <!-- <p class="text-center mt-3">Don't have an account? <a href="register.php" class="text-blue-500">Register</a> -->
        </p>
    </form>
    </div>
</div>


<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // $encrypted_u_password = md5($u_password);
        // $encrypted_u_password = $u_password;

        $qry = "SELECT * FROM admin WHERE aemail = '$email' AND apassword = '$password' ";
        $result = mysqli_query($conn, $qry);
        if(mysqli_num_rows($result) == 1)
        {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['aemail'] = $email;
            $admin = mysqli_fetch_assoc($result);
            $_SESSION['aid'] = $admin['aid'];
            echo '<script type="text/javascript"> alert("Logged in Successfully!"); window.location.assign("dashboard.php");</script>';
            exit();
        }
        else
        {
            echo '<script type="text/javascript"> alert("Invalid Credentials!");</script>';
        }
    }
?>

<script>
    function showError(field, message) {
        document.getElementById(field + "-error").textContent = message;
    }

    function clearError(field) {
        document.getElementById(field + "-error").textContent = "";
    }

    document.getElementById("email").addEventListener("input", function () {
        const email = this.value;
        const emailRegex = /^[^\d\s][\w.]+@[a-zA-Z\d.]+\.[a-zA-Z]{2,}$/;
        if (email.includes('-') || !emailRegex.test(email)) {
            showError("email", "Enter a valid email (no hyphens or spaces, and must start with a letter or number).");
        } else {
            clearError("email");
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

