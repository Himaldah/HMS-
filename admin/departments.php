<?php
$pageTitle = 'Departments';
include 'includes/sidemenu.php';
include 'includes/header.php';
include '../configs/db.php';
include 'api/admin_notification.php';
// Fetch all departments
$result = $conn->query("SELECT * FROM departments");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta dname="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Departments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">


<main class="flex-1 ml-52 p-2 overflow-auto mt-14">
    <div class="container mx-auto">
        <!-- Add Department Form -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Add New Department</h2>
            <form method="POST" enctype="multipart/form-data" name="department" onsubmit="return validateForm()">
                <input type="text" name="dname" placeholder="Department name" required class="w-full p-2 border border-gray-300 rounded mb-1">
                <small id="dname-error" class="text-red-500 text-sm mb-3 block"></small>

                <textarea name="ddescription" placeholder="Description" required class="w-full p-2 border border-gray-300 rounded mb-1"></textarea>
                <small id="ddescription-error" class="text-red-500 text-sm mb-3 block"></small>

                <input type="file" name="dicon" required class="w-full p-2 border border-gray-300 rounded mb-1" accept="image/*">
                <small id="dicon-error" class="text-red-500 text-sm mb-3 block"></small>

                <button type="submit" name="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Add Department</button>
            </form>
        </div>

        <!-- Department Table -->
         
        <div class="bg-white shadow-md rounded-lg p-6">
            
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-blue-700 mb-4">Department List</h2>
                <form method="POST" action="api/export_excel.php">
                    <input type="text" name="pagetitle" value="<?php echo $pageTitle; ?>" hidden>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 mb-4 rounded hover:bg-green-600"><i class="fa-solid fa-table"></i> Export to Excel</button>
                </form>
            </div>

            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Icon</th>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Description</th>
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['did']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <img src="<?php echo $row['dicon_path']; ?>" class="w-10 h-10 mx-auto">
                            </td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['dname']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['ddescription']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="departments.php?delete=<?php echo $row['did']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.forms["department"];
    const fields = {
        name: form["dname"],
        desc: form["ddescription"],
        icon: form["dicon"]
    };
    const errors = {
        name: document.getElementById("dname-error"),
        desc: document.getElementById("ddescription-error"),
        icon: document.getElementById("dicon-error")
    };

    fields.name.addEventListener("input", () => {
        const value = fields.name.value.trim();
        if (!/^[a-zA-Z\s]+$/.test(value)) {
            showError("name", "Name can only contain letters and spaces.");
        } else if (value.length < 3) {
            showError("name", "Name must be at least 3 characters.");
        } else {
            clearError("name");
        }
    });

    fields.desc.addEventListener("input", () => {
        const value = fields.desc.value.trim();
        if (value.length < 5) {
            showError("desc", "Description must be at least 5 characters.");
        } else {
            clearError("desc");
        }
    });

    fields.icon.addEventListener("change", () => {
        const file = fields.icon.files[0];
        if (!file || !/\.(jpg|jpeg|png|gif|webp)$/i.test(file.name)) {
            showError("icon", "Only image files (.jpg, .jpeg, .png, .gif, .webp) are allowed.");
        } else {
            clearError("icon");
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

    window.validateForm = function () {
        fields.name.dispatchEvent(new Event("input"));
        fields.desc.dispatchEvent(new Event("input"));
        fields.icon.dispatchEvent(new Event("change"));

        return !errors.name.textContent && !errors.desc.textContent && !errors.icon.textContent;
    }
});
</script>


</body>
</html>

<?php

// Handle Department Creation
if(isset($_POST['submit'])) {
    $name = $_POST['dname'];
    $description = $_POST['ddescription'];
    $icon_path = "uploads/departments/" . $_FILES['dicon']['name'];

    move_uploaded_file($_FILES['dicon']['tmp_name'], $icon_path);

    $conn->query("INSERT INTO departments (dname, ddescription, dicon_path) VALUES ('$name', '$description', '$icon_path')");
    header("Location: departments.php");
    $_SESSION['toast'] = ['message' => 'Department has been added.', 'type' => 'success'];
}

// Handle Department Deletion
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM departments WHERE did=$id");
    header("Location: departments.php");
    $_SESSION['toast'] = ['message' => 'Department has been deleted.', 'type' => 'success'];
}

// Fetch all departments
$result = $conn->query("SELECT * FROM departments");
?>