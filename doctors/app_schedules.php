    <?php
    $pageTitle = 'Appointments List';
    include 'includes/header.php';

    $drid = $_SESSION['drid'];
    $drqry = "SELECT * FROM doctors WHERE drid = '$drid'";
    $result = mysqli_query($conn, $drqry);
    $doctor = mysqli_fetch_assoc($result);

    $patient_email = $_SESSION['pemail'] ?? null;
    $patient_qry = "SELECT * FROM patients WHERE pemail = '$patient_email'";
    $patient_result = mysqli_query($conn, $patient_qry);
    $patient = mysqli_fetch_assoc($patient_result);
    $patient_id = $patient['pid'] ?? null;
    $patient_name = $patient['pname'] ?? null;
    $patient_phone = $patient['pphone'] ?? null;

    // Fetch Appointments
    // $appointments = $conn->query("SELECT appointments.*, doctors.drname, doctors.did FROM appointments JOIN doctors ON appointments.drid = doctors.drid WHERE appointments.drid = '$drid' ORDER BY aid DESC, appointment_time DESC");

    $filter_sql = "SELECT * FROM doctor_schedule WHERE drid = '$drid'";
    // $schedule_result = mysqli_query($conn, $schedule_qry);

    // Add filters
    if (!empty($_GET['day'])) {
        $day = mysqli_real_escape_string($conn, $_GET['day']);
        $filter_sql .= " AND day = '$day'";
    }

    if (!empty($_GET['date'])) {
        $date = mysqli_real_escape_string($conn, $_GET['date']);
        $filter_sql .= " AND available_date = '$date'";
    }

    $filter_sql .= " ORDER BY available_date DESC";

    $schedule_result = $conn->query($filter_sql);

    $sn = 1;

    if (isset($_GET['action']) && isset($_GET['sid'])) {
        $action = $_GET['action'];
        $sid = intval($_GET['sid']);

        if ($action === 'delete') {
            mysqli_query($conn, "DELETE FROM doctor_schedule WHERE sid = $sid AND drid = '$drid'");
            // mysqli_query($conn, "UPDATE appointments SET status = 'Completed' WHERE aid = $aid AND drid = '$drid'");
        }
        // } elseif ($action === 'confirm') {
        //     mysqli_query($conn, "UPDATE appointments SET status = 'Confirmed' WHERE aid = $aid AND drid = '$drid'");
        // } elseif ($action === 'cancel') {
        //     mysqli_query($conn, "UPDATE appointments SET status = 'Cancelled' WHERE aid = $aid AND drid = '$drid'");
        // }

        // Optional: redirect back to the same page to avoid resubmission on refresh
        header("Location: app_schedules.php");
        exit;
    }

    ?>

    <header class="text-black text-center py-4 pt-20">
            <h1 class="text-3xl font-bold text-blue-900 text-center">Appointment Schedules</h1>
        </header>

        <form method="GET" class="mb-4 flex flex-wrap justify-center gap-4">
                
                <input type="date" name="date" value="<?php echo $_GET['date'] ?? ''; ?>" class="border p-2 rounded-lg" placeholder="Appointment Date">
                
                <select name="day" class="border p-2 rounded-lg">
                    <option value="">All Days</option>
                    <?php
                        $days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        foreach ($days as $index => $day):
                    ?>
                    <option value="<?php echo $day ?>" <?php if(($_GET['day'] ?? '') === $day) echo 'selected'; ?>><?php echo $day ?></option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 self-center">Filter</button>
                <a href="app_schedules.php" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 self-center">Clear</a>
            </form>

    <main class="flex-1 p-2 overflow-auto flex justify-center">
        <div class="container mx-auto">
            <div class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
                <!-- <h2 class="text-xl font-semibold text-blue-700 mb-4 text-center">Appointments List</h2> -->
                <div class="flex justify-center">
                    <table class="border-collapse border border-gray-300 w-full">
                        <thead>
                            <tr class="bg-pink-500 text-white ">
                                <th class="border border-blue-300 px-4 py-2">S.N</th>  
                                <th class="border border-blue-300 px-4 py-2">SID</th>  
                                <th class="border border-blue-300 px-4 py-2">Date</th>  
                                <th class="border border-blue-300 px-4 py-2">Day</th>  
                                <th class="border border-blue-300 px-4 py-2">Start Time</th>  
                                <th class="border border-blue-300 px-4 py-2">End Time</th>  
                                <th class="border border-blue-300 px-4 py-2">Tokens</th>  
                                <th class="border border-blue-300 px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($schedule = $schedule_result->fetch_assoc()):
                                
                                $bookedTokens = 0;
                                if (!empty($schedule['available_date'])) {
                                    $date = mysqli_real_escape_string($conn, $schedule['available_date']);
                                    $query = "SELECT COUNT(*) as booked FROM appointments WHERE drid = '$drid' AND appointment_date = '$date'";
                                    $result = mysqli_query($conn, $query);
                                    if ($result) {
                                        $row = mysqli_fetch_assoc($result);
                                        $bookedTokens = (int)$row['booked'];
                                    }
                                }
                                ?>

                                <tr class="text-center  hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4">
                                    <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($sn++); ?></td>
                                    <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($schedule['sid']); ?></td>
                                    <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($schedule['available_date']); ?></td>
                                    <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($schedule['day']); ?></td>
                                    <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($schedule['start_time']); ?></td>
                                    <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($schedule['end_time']); ?></td>
                                    <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($bookedTokens); ?>/<?php echo htmlspecialchars($schedule['tokens']); ?></td>
                                    <td class="border border-blue-300 px-4 py-2">
                                        <a href="app_patient_list.php?appointment_date=<?php echo $schedule['available_date']; ?>" class="text-pink-500 hover:text-pink-700">View</a>
                                        <a href="update_schedule.php?sid=<?php echo $schedule['sid'];?>" class="text-green-500 hover:text-green-700" >Update</a>
                                        <a href="app_schedules.php?action=delete&sid=<?php echo $schedule['sid'];?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete this schedule?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

