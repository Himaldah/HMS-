    <header class="text-black text-center py-2 pt-20">
        <h1 class="text-3xl text-center font-bold text-blue-900 ">Appointments </h1>
    </header>

    

<main class="flex-1 p-2 overflow-auto flex justify-center">
    <div class="container mx-auto">
        
        <form method="GET" class="mb-4 flex flex-wrap justify-center gap-4">
            
            <input type="date" name="date" value="<?php echo $_GET['date'] ?? ''; ?>" class="border p-2 rounded-lg" placeholder="Appointment Date">
            
            <select name="status" class="border p-2 rounded-lg">
                <option value="">All Status</option>
                <option value="Pending" <?php if(($_GET['status'] ?? '') === 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Confirmed" <?php if(($_GET['status'] ?? '') === 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="Cancelled" <?php if(($_GET['status'] ?? '') === 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 self-center">Filter</button>
            <a href="view_appointments.php" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 self-center">Clear</a>
        </form>

        <div class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4 bg-white">
            
            <!-- <h2 class="text-xl font-semibold text-blue-700 mb-4 text-center">Appointments List</h2> -->
            <div class="flex justify-center">
                
                <table class="border-collapse border border-gray-300 w-full">
                    <thead>
                        <tr class="bg-pink-500 text-white ">
                            <th class="border border-blue-300 px-4 py-2">AID</th>   
                            <th class="border border-blue-300 px-4 py-2">Token Number</th>
                            <th class="border border-blue-300 px-4 py-2">Booked At</th>
                            <th class="border border-blue-300 px-4 py-2">Department</th>

                            <th class="border border-blue-300 px-4 py-2">Doctor</th>
                            <th class="border border-blue-300 px-4 py-2">Patient</th>
                            <th class="border border-blue-300 px-4 py-2">Gender</th>
                            <th class="border border-blue-300 px-4 py-2">DOB</th>
                            <th class="border border-blue-300 px-4 py-2">Phone</th>

                            <th class="border border-blue-300 px-4 py-2">Appointment Date</th>
                            <th class="border border-blue-300 px-4 py-2">Status</th>
                            <th class="border border-blue-300 px-4 py-2">Action</th>
                            <th class="border border-blue-300 px-4 py-2">Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $appointments->fetch_assoc()): 
                            if ($row['other_gender'] != NULL && $row['other_dob'] != NULL){
                                $gender = $row['other_gender'];
                                $dob = $row['other_dob'];
                            }
                            else{   
                                $gender = $patient['pgender'] ?? null;
                                $dob = $patient['pdob'] ?? null;
                            }
                            

                            $did = $row['did'] ?? null;
                            $did_qry = "SELECT * FROM departments WHERE did = '$did'";
                            $did_result = mysqli_query($conn, $did_qry);
                            $did_row = mysqli_fetch_assoc($did_result);
                            $department = $did_row['dname'] ?? null;

                            ?>



                            <tr class="text-center  hover:shadow-lg hover:shadow-blue-200 transition duration-300 rounded-lg p-4">
                                <td class="border border-blue-300 px-4 py-2"><?php echo $row['aid']; ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo $row['token_num']; ?><a href="api/download_token.php?aid=<?php echo  $row['aid']; ?>" class=" text-green-500 px-2 hover:text-green-700 transition"><i class="fas fa-file-download mr-2"></i></a></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['acreated_at']); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($department); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['drname']); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['pname']); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($gender); ?></td>
                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($dob); ?></td>

                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['pphone']); ?></td>

                                <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['appointment_date']); ?></td>

                                <!-- <td class="border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td> -->
                                <?php if($row['status'] === 'Pending'): ?>
                                    <td class="text-yellow-500 border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                                <?php elseif($row['status'] === 'Confirmed'): ?>
                                    <td class="text-green-500 border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                                <?php elseif($row['status'] === 'Cancelled'): ?>
                                    <td class="text-red-500 border border-blue-300 px-4 py-2"><?php echo htmlspecialchars($row['status']); ?></td>
                                <?php endif; ?>

                                <td class="border border-blue-300 px-4 py-2">
                                <?php if ($row['status'] === 'Pending'): ?>
                                        <a href="view_appointments.php?action=cancel&aid=<?php echo $row['aid']; ?>" class="text-red-500 hover:text-red-700 ml-2">Cancel</a>
                                    <?php elseif($row['status'] === 'Confirmed'): ?>
                                        <!-- <a href="view_appointments.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=complete&aid=<?php echo $row['aid']; ?>" class="text-green-500 hover:text-green-700">Complete</a> -->
                                        <!-- <a href="app_patient_list.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=confirm&aid=<?php echo $row['aid']; ?>" class="text-green-500 hover:text-green-700">Confirm</a> -->
                                        <a href="view_appointments.php?action=cancel&aid=<?php echo $row['aid']; ?>" class="text-red-500 hover:text-red-700 ml-2">Cancel</a>
                                    <?php elseif($row['status'] === 'Cancelled'): ?>
                                        <a href="view_appointments.php?action=continue&aid=<?php echo $row['aid']; ?>" class="text-blue-500 hover:text-blue-700">Continue</a>
                                        <!-- <a href="view_appointments.php?appointment_date=<?php echo urlencode($appointment_date); ?>&action=cancel&aid=<?php echo $row['aid']; ?>" class="text-red-500 hover:text-red-700 ml-2">Cancel</a> -->
                                        <!-- <span class="text-gray-500">Already <?php echo $row['status']; ?></span> -->
                                    <?php endif; ?>
                                </td>
                                <td class="border border-blue-300 px-4 py-2">
                                    <a href="report.php?aid=<?php echo $row['aid']; ?>" class="text-pink-500 hover:text-pink-600">View</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<?php include 'includes/footer.php'; ?>