<?php
$pageTitle = 'Dashboard';
include 'includes/sidemenu.php';
include 'includes/header.php';


$query = "SELECT * FROM departments";
$query2 = "SELECT * FROM doctors";
$query3 = "SELECT * FROM patients";
$query4 = "SELECT * FROM appointments";
$query5 = "SELECT * FROM doctor_schedule";
$query6 = "SELECT * FROM reports";
$query7 = "SELECT * FROM articles";

$departments = $conn->query($query);
$doctors = $conn->query($query2);
$patients = $conn->query($query3);
$appointments = $conn->query($query4);
$schedules = $conn->query($query5);
$reports = $conn->query($query6);
$articles = $conn->query($query7);

$rows_count_departments = mysqli_num_rows($departments);
$rows_count_doctors = mysqli_num_rows($doctors);
$rows_count_patients = mysqli_num_rows($patients);
$rows_count_appointments = mysqli_num_rows($appointments);
$rows_count_schedules = mysqli_num_rows($schedules);
$rows_count_reports = mysqli_num_rows($reports);
$rows_count_articles = mysqli_num_rows($articles);

$queryMale = "SELECT COUNT(*) AS total FROM patients WHERE pgender = 'Male'";
$queryFemale = "SELECT COUNT(*) AS total FROM patients WHERE pgender = 'Female'";
$queryOther = "SELECT COUNT(*) AS total FROM patients WHERE pgender NOT IN ('Male', 'Female')";

$maleResult = $conn->query($queryMale);
$femaleResult = $conn->query($queryFemale);
$otherResult = $conn->query($queryOther);

$maleCount = $maleResult->fetch_assoc()['total'];
$femaleCount = $femaleResult->fetch_assoc()['total'];
$otherCount = $otherResult->fetch_assoc()['total'];


?>

            <!-- Dashboard Content -->
            <main class="flex-1 ml-52 p-6 overflow-auto mt-14">
                <div class="grid grid-cols-3 gap-6 ">
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-900">Total Patients</h3>
                        <p class="text-2xl font-bold">
                            <?php echo $rows_count_patients;?>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-900">Total Doctors</h3>
                        <p class="text-2xl font-bold">
                            <?php echo $rows_count_doctors;?>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-900">Total Departments</h3>
                        <p class="text-2xl font-bold">
                            <?php echo $rows_count_departments;?>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-900">Total Appointments</h3>
                        <p class="text-2xl font-bold">
                            <?php echo $rows_count_appointments;?>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-900">Total Schedules</h3>
                        <p class="text-2xl font-bold">
                            <?php echo $rows_count_schedules;?>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-900">Total Reports</h3>
                        <p class="text-2xl font-bold">
                            <?php echo $rows_count_reports;?>
                        </p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-900">Total Articles</h3>
                        <p class="text-2xl font-bold">
                            <?php echo $rows_count_articles;?>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">

                    <div class="bg-white border border-blue-200 rounded-lg shadow p-4 mt-10 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">System Overview</h2>
                        <canvas id="dashboardChart" height="100"></canvas>
                    </div>

                    <div class="bg-white border border-blue-200 rounded-lg shadow p-4 mt-10 hover:shadow-lg hover:shadow-blue-200 transition duration-300">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Patients by Gender</h2>
                        <div class="w-48 h-48 mx-auto">
                            <canvas id="genderChart"></canvas>
                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>


<script>
const dashboardChart = new Chart(document.getElementById('dashboardChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: [
            'Patients',
            'Doctors',
            'Departments',
            'Appointments',
            'Schedules',
            'Reports',
            'Articles'
        ],
        datasets: [{
            label: 'Count',
            data: [
                <?php echo $rows_count_patients; ?>,
                <?php echo $rows_count_doctors; ?>,
                <?php echo $rows_count_departments; ?>,
                <?php echo $rows_count_appointments; ?>,
                <?php echo $rows_count_schedules; ?>,
                <?php echo $rows_count_reports; ?>,
                <?php echo $rows_count_articles; ?>
            ],
            backgroundColor: [
                'rgba(96, 165, 250, 0.6)', // blue-400
                'rgba(34, 197, 94, 0.6)',  // green-500
                'rgba(249, 115, 22, 0.6)', // orange-500
                'rgba(239, 68, 68, 0.6)',  // red-500
                'rgba(139, 92, 246, 0.6)', // purple-500
                'rgba(20, 184, 166, 0.6)', // teal-500
                'rgba(250, 204, 21, 0.6)'  // yellow-400
            ],
            borderColor: [
                'rgba(96, 165, 250, 1)',
                'rgba(34, 197, 94, 1)',
                'rgba(249, 115, 22, 1)',
                'rgba(239, 68, 68, 1)',
                'rgba(139, 92, 246, 1)',
                'rgba(20, 184, 166, 1)',
                'rgba(250, 204, 21, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 20
                }
            }
        }
    }
});

const genderChart = new Chart(document.getElementById('genderChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female', 'Other'],
        datasets: [{
            data: [
                <?php echo $maleCount; ?>,
                <?php echo $femaleCount; ?>,
                <?php echo $otherCount; ?>
            ],
            backgroundColor: [
                'rgba(59, 130, 246, 0.6)',   // blue
                'rgba(236, 72, 153, 0.6)',   // pink
                'rgba(107, 114, 128, 0.6)'   // gray
            ],
            borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(236, 72, 153, 1)',
                'rgba(107, 114, 128, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        
        cutout: '80%',
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});


</script>


</body>
</html>
