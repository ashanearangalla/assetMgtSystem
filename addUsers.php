<?php
// Database connection

include('connection.php');


// Variables
$userID = isset($_GET['userID']) ? intval($_GET['userID']) : null;
$isEditMode = $userID !== null;

// Fetch categories and statuses
$categories = $conn->query("SELECT categoryID, name FROM Category");
$manufacturers = $conn->query("SELECT manufacturerID, name FROM Manufacturer");
$suppliers = $conn->query("SELECT supplierID, name FROM Supplier");
$offices = $conn->query("SELECT officeID, name FROM Office");
$departments = $conn->query("SELECT id, name FROM department");
$statuses = $conn->query("SELECT statusID, type FROM Status");

// Initialize variables
$fname = $lname = $email = $password = $image = $loginEnabled = $role = $statusID = $officeID = $departmentID = "";

// Handle Edit Mode
if ($isEditMode) {
    $stmt = $conn->prepare("
        SELECT u.userID, u.fname, u.lname, u.email, u.role, u.loginEnabled, u.image,  
               d.id AS departmentID, 
               st.statusID, 
               o.officeID    
        FROM user u
        LEFT JOIN office o ON u.locationID = o.officeID
        LEFT JOIN status st ON u.statusID = st.statusID
        LEFT JOIN department d ON u.departmentID = d.id
        WHERE u.userID = ?
    ");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Populte variables with existing data
        $fname = $user['fname'];
        $lname = $user['lname'];
        $email = $user['email'];
        $role = $user['role'];
        $loginEnabled = $user['loginEnabled'];
        $statusID = $user['statusID'];
        $officeID = $user['officeID'];
        $departmentID = $user['departmentID'];
    } else {
        echo "Invalid User ID.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title><?php echo $isEditMode ? "Edit User" : "Add New User"; ?></title>
    <link rel="stylesheet" href="stylesheetlast.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('sidemenu.php'); ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1><?php echo $isEditMode ? "Edit User" : "Add New User"; ?></h1>
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="userForm" action="db_context.php" method="POST" enctype="multipart/form-data" onsubmit="return validateUserForm()">
                    <input type="hidden" name="userID" value="<?php echo htmlspecialchars($userID ?? ''); ?>">

                    <div class="form-element">
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname" placeholder="First Name" value="<?php echo htmlspecialchars($fname); ?>">
                    </div>

                    <div class="form-element">
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname" placeholder="Last Name" value="<?php echo htmlspecialchars($lname); ?>">
                    </div>

                    <div class="form-element">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <div class="form-element">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Password" value="" <?php echo isset($isEditMode) && $isEditMode ? '' : 'required'; ?>>
                    </div>

                    <div class="form-element">
                        <label for="loginEnabled">Login Enabled:</label>
                        <input type="checkbox" id="loginEnabled" name="loginEnabled" value="1" <?php echo $loginEnabled == 1 ? 'checked' : ''; ?>>
                    </div>

                    <div class="form-element">
                        <label for="image">Profile Image:</label>
                        <input type="file" id="image" name="image">
                    </div>

                    <div class="form-element">
                        <label for="role">Role:</label>
                        <select id="role" name="role">
                            <option value="" selected hidden>Select Role</option>
                            <option value="Admin" <?php echo $role === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="Employee" <?php echo $role === 'Employee' ? 'selected' : ''; ?>>Employee</option>
                        </select>
                    </div>

                    <div class="form-element">
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="" selected hidden>Select Status</option>
                            <?php while ($status = $statuses->fetch_assoc()) { ?>
                                <option value="<?php echo $status['statusID']; ?>" <?php echo $statusID == $status['statusID'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($status['type']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-element">
                        <label for="office">Office:</label>
                        <select id="office" name="office">
                            <option value="" selected hidden>Select Office</option>
                            <?php while ($office = $offices->fetch_assoc()) { ?>
                                <option value="<?php echo $office['officeID']; ?>" <?php echo $officeID == $office['officeID'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($office['name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-element">
                        <label for="department">Department:</label>
                        <select id="department" name="department">
                            <option value="" selected hidden>Select Department</option>
                            <?php while ($department = $departments->fetch_assoc()) { ?>
                                <option value="<?php echo $department['id']; ?>" <?php echo $departmentID == $department['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($department['name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div style="text-align: center; margin-top: 20px;" id="error-box" class="error-box"></div>

                    <div class="form-footer">
                       
                        <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateUserButton' : 'addUserButton'; ?>">
                            <?php echo $isEditMode ? "Update User" : "Add User"; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function validateUserForm() {
            const fname = document.getElementById("fname").value.trim();
            const lname = document.getElementById("lname").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            const role = document.getElementById("role").value.trim();
            const status = document.getElementById("status").value.trim();
            const office = document.getElementById("office").value.trim();
            const department = document.getElementById("department").value.trim();
            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            // Validate First Name
            if (!fname) {
                errorBox.innerHTML = "First Name is required.";
                return false;
            }

            // Validate Last Name
            if (!lname) {
                errorBox.innerHTML = "Last Name is required.";
                return false;
            }

            // Validate Email
            if (!email) {
                errorBox.innerHTML = "Email is required.";
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errorBox.innerHTML = "Invalid email format.";
                return false;
            }

            // Validate Password (only if creating a new user)
            if (!<?php echo json_encode($isEditMode ? 'false' : 'true'); ?> && !password) {
                errorBox.innerHTML = "Password is required.";
                return false;
            }

            

            // Validate Role
            if (!role) {
                errorBox.innerHTML = "Role is required.";
                return false;
            }

            // Validate Status
            if (!status) {
                errorBox.innerHTML = "Status is required.";
                return false;
            }

            // Validate Office
            if (!office) {
                errorBox.innerHTML = "Office is required.";
                return false;
            }

            // Validate Department
            if (!department) {
                errorBox.innerHTML = "Department is required.";
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
