    <?php
    $pageTitle = 'Book Appointment';
    include 'includes/header.php';
    include 'api/notification.php';
    include 'check_tokens.php';

    $patient_email = $_SESSION['pemail'] ?? null;
    $patient_id = $_SESSION['pid'] ?? null;
    $patient_qry = "SELECT * FROM patients WHERE pid = '$patient_id'";
    $patient_exe = mysqli_query($conn, $patient_qry);
    $patient_row = mysqli_fetch_assoc($patient_exe);
    $patient_name = $patient_row['pname'] ?? null;
    $patient_phone = $patient_row['pphone'] ?? null;
    $patient_id = $patient_row['pid'] ?? null;
    $patient_gender = $patient_row['pgender'] ?? null;
    $patient_dob = $patient_row['pdob'] ?? null;

    $doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
    $department_id = isset($_GET['department_id']) ? intval($_GET['department_id']) : 0;

    $department_qry = "SELECT * FROM departments WHERE did = '$department_id'";
    $department_exe = mysqli_query($conn, $department_qry);
    $department_row = mysqli_fetch_assoc($department_exe);
    $department_name = $department_row['dname'] ?? null;

    $fee_qry = "SELECT * FROM appointment_fees WHERE did = '$department_id'";
    $fee_exe = mysqli_query($conn, $fee_qry);
    $fee_row = mysqli_fetch_assoc($fee_exe);
    $fee = $fee_row['famount'] ?? null;

    // Get doctor_id from URL
    if (!isset($_GET["doctor_id"]) || empty($_GET["doctor_id"])) {
        echo "<script>alert('No doctor selected!'); window.location='doctors.php';</script>";
        exit();
    }
    $doctor_id = intval($_GET["doctor_id"]);

    // Fetch Doctor Details
    $stmt = $conn->prepare("SELECT doctors.drid, doctors.drname, doctors.drprofile, departments.dname FROM doctors JOIN departments ON doctors.did = departments.did WHERE doctors.drid = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctor = $result->fetch_assoc();
    $stmt->close();

    if (!$doctor) {
        echo "<script>alert('Doctor not found!'); window.location='doctors.php';</script>";
        exit();
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['appointment'] = [
        'patient_id' => $patient_id,
        'doctor_id' => $doctor_id,
        'did' => $department_id,
        'appointment_date' => $_POST['appointment_date'],
        'fee' => $fee,
        'is_self' => $_POST['is_self'],
        'patient_name' => $_POST['patient_name'],
        'patient_phone' => $_POST['patient_phone'],
        'gender' => $_POST['patient_gender'],
        'dob' => $_POST['patient_dob'],
        // if someone else
        'other_name' => $_POST['other_patient_name'] ?? '',
        'other_phone' => $_POST['other_patient_phone'] ?? '',
        'other_gender' => $_POST['other_gender'] ?? '',
        'other_dob' => $_POST['other_dob'] ?? '',
    ];

    // generate a unique transaction ID
    $_SESSION['transaction_uuid'] = uniqid();

    // redirect to payment form page
    header("Location: api/esewa_payment.php");
    exit();
}


?>


    <main class="flex-1 flex flex-col items-center justify-center p-4 mt-6 pt-20">
        <div class="w-full max-w-lg hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white">
            
            <!-- Doctor Details Section -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-6 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white">
                <!-- <h2 class="text-xl font-semibold text-blue-900 mb-4">Doctor Details </h2> -->
        
                <div class="flex flex-col items-center">
                    <img src="admin/<?php echo htmlspecialchars($doctor['drprofile']); ?>" alt="Doctor Profile" class="w-24 h-24 rounded-full border mb-3 rounded-xl border-2 border-blue-200">
                    <a href="doctor_profile.php?drid=<?php echo $doctor['drid'] ?>"><h2 class="text-xl font-semibold text-blue-900"><?php echo htmlspecialchars($doctor['drname']); ?></h2></a>
                    <p class="text-gray-600"><?php echo htmlspecialchars($department_name); ?></p>
                    <p class="text-gray-600">Appointment Fee: Rs. <?php echo htmlspecialchars($fee); ?></p>
                    <!-- <p class="text-gray-600"><?php echo htmlspecialchars($doctor['dname']); ?></p> -->
                </div>
            </div>


            <!-- Appointment Booking Form -->
            <!-- <div class="bg-white shadow-md rounded-lg p-6 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white"> -->
                <!-- <h2 class="text-xl font-semibold text-blue-900 mb-4">Patient Details</h2> -->
    <form method="POST" class="flex flex-col items-center" onsubmit="return validateForm()" name="booking">

        <!-- 🗓 Step 1: Appointment Date & Time -->
        <!-- <h2 class="text-xl font-semibold text-black mb-4">Appointment Details</h2> -->

        <!-- <input type="date" name="appointment_date" required class="w-full p-2 border border-gray-300 rounded mb-3">
        <input type="time" name="appointment_time" required class="w-full p-2 border border-gray-300 rounded mb-6"> -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white">
        <h2 class="text-xl font-semibold text-black mb-4">Select Appointment Day</h2>
        <!-- <label class="block text-gray-700 mb-1">Select Appointment Day & Time</label> -->
        <select name="appointment_date" id="appointment_date" class="w-full p-2 border border-gray-300 rounded mb-3">
            <option value="" disabled selected>Select</option>
            <?php
            // Fetch available schedules for the selected doctor
            $today = date('Y-m-d');
            $schedule_stmt = $conn->prepare("SELECT * FROM doctor_schedule WHERE drid = ? AND available_date >= ? ORDER BY available_date ASC LIMIT 3");
            $schedule_stmt->bind_param("is", $doctor_id, $today);
            $schedule_stmt->execute();
            $schedules = $schedule_stmt->get_result();

            while ($sched = $schedules->fetch_assoc()): ?>
                <?php
                    // Convert start and end time from 24-hour to 12-hour format with AM/PM
                    $start_time_12hr = date("g:i A", strtotime($sched['start_time']));
                    $end_time_12hr = date("g:i A", strtotime($sched['end_time']));
                ?>
                <option value="<?php echo $sched['available_date']; ?>">
                    
                    <?php echo $sched['day'] . " - " . $sched['available_date'] . " ($start_time_12hr - $end_time_12hr)"; ?>
                </option>
            <?php endwhile; ?>
            
        </select>
        <button type="button" id="checkTokenBtn" class="mt-2 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">
        Check Availability
    </button>

    <p id="token_status" class="text-sm mt-2"></p>
        </div>

        
        <div id="patient-details-section" class="hidden bg-white shadow-md rounded-lg p-6 mb-6 text-center hover:shadow-lg hover:shadow-blue-200 transition duration-300">
    

        <!-- 👤 Step 2: Who is the appointment for? -->
        <h2 class="text-xl font-semibold text-black mb-4"> Who is the appointment for? </h2>

        <!-- Option Selector -->
        <div class="flex justify-center gap-6 mb-4">
            <label class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-300 rounded-lg cursor-pointer transition hover:bg-blue-100">
                <input type="radio" name="is_self" value="1" checked onchange="toggleOtherFields()" class="accent-blue-600">
                <span class="text-blue-900 font-medium">Myself</span>
            </label>

            <label class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-300 rounded-lg cursor-pointer transition hover:bg-blue-100">
                <input type="radio" name="is_self" value="0" onchange="toggleOtherFields()" class="accent-blue-500">
                <span class="text-blue-900 font-medium"> Someone else </span>
            </label>
        </div>  

        <!-- 👨‍⚕️ Patient Info for "Myself" -->
        <div id="my-info" class="w-full px-4">
            <input type="text" id="patient_name_input" name="patient_name" placeholder="Enter Name" required class="w-full p-2 border border-gray-300 rounded mb-3" value="<?php echo htmlspecialchars($patient_name); ?>" readonly>
            <input type="text" id="patient_gender_input" name="patient_gender" placeholder="Enter Gender" required class="w-full p-2 border border-gray-300 rounded mb-3" value="<?php echo htmlspecialchars($patient_gender); ?>" readonly>
            <input type="date" id="patient_dob_input" name="patient_dob" placeholder="Enter DOB" required class="w-full p-2 border border-gray-300 rounded mb-3" value="<?php echo htmlspecialchars($patient_dob); ?>" readonly>
            <input type="text" id="patient_phone_input" name="patient_phone" placeholder="Enter Phone Number" required class="w-full p-2 border border-gray-300 rounded mb-3" value="<?php echo htmlspecialchars($patient_phone); ?>" readonly>
        </div>

        <!-- 👥 Extra Fields for "Someone else" -->
        <div id="other-info" class="hidden w-full">
            
            <input type="text" id="other_patient_name_input" name="other_patient_name" placeholder="Enter Other Name" class="w-full p-2 border border-gray-300 rounded mb-3">
            <span id="name-error" class="text-red-500 text-sm block"></span>

            <input type="number" id="other_patient_phone_input" name="other_patient_phone" placeholder="Enter Other Phone Number" class="w-full p-2 border border-gray-300 rounded mb-3">
            <span id="phone-error" class="text-red-500 text-sm block"></span>

                <!-- <input type="text" name="patient_name" placeholder="Other Person's Full Name" class="w-full p-2 border border-gray-300 rounded mb-3">
                <input type="text" name="patient_phone" placeholder="Other Person's Phone Number" class="w-full p-2 border border-gray-300 rounded mb-3"> -->
                <select id="other_gender" name="other_gender" class="w-full px-3 py-2 border rounded-md mb-3">
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>

                <input type="date" id="other_dob" name="other_dob" placeholder="Date of Birth" class="w-full p-2 border border-gray-300 rounded mb-3">
                <span id="dob-error" class="text-red-500 text-sm mt-1 block"></span>
        </div>

        <!-- 🚀 Final Submit Button -->
        <button type="submit" class="mt-4 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 transition">Proceed To Payment</button>

        

        </div>

        <!-- JavaScript for conditional field toggle -->
        <script>
            function toggleOtherFields() {
                const isSelf = document.querySelector('input[name="is_self"]:checked').value;
                const myselfFields = document.getElementById('my-info');
                const otherFields = document.getElementById('other-info');
                const othernameField = document.getElementById('other_patient_name_input');
                const otherphoneField = document.getElementById('other_patient_phone_input');
                const otherGender = document.getElementById('other_gender');
                const otherDob = document.getElementById('other_dob');

                if (isSelf === "0") {
                    otherFields.classList.remove('hidden');
                    myselfFields.classList.add('hidden');
                    othernameField.required = true;
                    otherphoneField.required = true;
                    otherGender.required = true;
                    otherDob.required = true;
                
                } else {
                    otherFields.classList.add('hidden');
                    myselfFields.classList.remove('hidden');
                    othernameField.required = false;
                    otherphoneField.required = false;
                    otherGender.required = false;
                    otherDob.required = false;
                    
                }
            }

            document.addEventListener("DOMContentLoaded", toggleOtherFields);


            document.getElementById('checkTokenBtn').addEventListener('click', function() {
                const selectedDate = document.getElementById('appointment_date').value;
                const doctorId = <?php echo $doctor_id; ?>;
                const tokenStatus = document.getElementById('token_status');
                const appointmentForm = document.querySelector('#patient-details-section');

                if (!selectedDate) {
                    alert("Please select a date.");
                    return;
                }

                fetch('check_tokens.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `doctor_id=${doctorId}&date=${selectedDate}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        tokenStatus.innerHTML = `✅ Tokens available: ${data.tokens_left}`;
                        tokenStatus.classList.add('text-green-600');
                        tokenStatus.classList.remove('text-red-600');
                        appointmentForm.classList.remove('hidden');
                    } else {
                        tokenStatus.innerHTML = `❌ ${data.message}`;
                        tokenStatus.classList.add('text-red-600');
                        tokenStatus.classList.remove('text-green-600');
                        appointmentForm.classList.add('hidden');
                    }
                })
                .catch(error => {
                    tokenStatus.innerHTML = "Error checking tokens.";
                    tokenStatus.classList.add('text-red-600');
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
                const nameField = document.getElementById("other_patient_name_input");
                const phoneField = document.getElementById("other_patient_phone_input");
                const dobField = document.getElementById("other_dob");

                const nameError = document.getElementById("name-error");
                const phoneError = document.getElementById("phone-error");
                const dobError = document.getElementById("dob-error");

                const form = document.forms["booking"];

                function isSomeoneElseSelected() {
                    const selected = document.querySelector('input[name="is_self"]:checked');
                    return selected && selected.value === "0";
                }

                function validateName() {
                    if (!isSomeoneElseSelected()) return true;
                    const name = nameField.value.trim();
                    if (name.length < 3) {
                        nameError.textContent = "Name must be at least 3 characters.";
                        return false;
                    } else if (!/^[a-zA-Z\s]+$/.test(name)) {
                        nameError.textContent = "Only letters and spaces allowed.";
                        return false;
                    } else {
                        nameError.textContent = "";
                        return true;
                    }
                }

                function validatePhone() {
                    if (!isSomeoneElseSelected()) return true;
                    const phone = phoneField.value.trim();
                    if (!/^(97|98)\d{8}$/.test(phone)) {
                        phoneError.textContent = "Phone must start with 97 or 98 and be 10 digits.";
                        return false;
                    } else {
                        phoneError.textContent = "";
                        return true;
                    }
                }

                function validateDOB() {
                    if (!isSomeoneElseSelected()) return true;

                    const dob = new Date(dobField.value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (dob >= today) {
                        dobError.textContent = "Date of birth must be in the past.";
                        return false;
                    } else {
                        dobError.textContent = "";
                        return true;
                    }
                }


                // Only validate live if "Someone else" is selected
                nameField.addEventListener("input", validateName);
                phoneField.addEventListener("input", validatePhone);
                dobField.addEventListener("input", validateDOB);

                // On form submit
                form.addEventListener("submit", function (e) {
                    if (isSomeoneElseSelected()) {
                        const validName = validateName();
                        const validPhone = validatePhone();
                        const validDOB = validateDOB();

                        if (!(validName && validPhone && validDOB)) {
                            e.preventDefault();
                        }
                    }
                });

                // Clear error messages when switching back to "Myself"
                document.querySelectorAll('input[name="is_self"]').forEach((radio) => {
                    radio.addEventListener("change", () => {
                        if (!isSomeoneElseSelected()) {
                            nameError.textContent = "";
                            phoneError.textContent = "";
                            dobError.textContent = "";
                        }
                    });
                });
            });
            
        </script>

    </form>


        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

