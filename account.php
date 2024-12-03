<?php

// Database connection
include('connection.php');

// Initialize variables
$fname = $lname = $email = $password = $image = "";
$errorMessage = "";


$userID = $_SESSION['user']['userID'];

// Fetch user details
$stmt = $conn->prepare("SELECT fname, lname, email, image FROM user WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $fname = $user['fname'];
    $lname = $user['lname'];
    $email = $user['email'];
    $image = $user['image'];
} else {
    $errorMessage = "User not found.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($fname) || empty($lname) || empty($email)) {
        $errorMessage = "First Name, Last Name, and Email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        // Handle file upload
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ["jpg", "jpeg", "png", "gif"];

            if (in_array($imageFileType, $allowedTypes) && move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image = $targetFile;
            } else {
                $errorMessage = "Error uploading image. Ensure it is a valid image file.";
            }
        }

        if (empty($errorMessage)) {
            // Update user details in the database
            $query = "UPDATE user SET fname = ?, lname = ?, email = ?, image = ?";
            $params = [$fname, $lname, $email, $image];
            $types = "ssss";

            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $query .= ", password = ?";
                $params[] = $hashedPassword;
                $types .= "s";
            }

            $query .= " WHERE userID = ?";
            $params[] = $userID;
            $types .= "i";

            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                $_SESSION['user']['fname'] = $fname;
                $_SESSION['user']['lname'] = $lname;
                $_SESSION['user']['email'] = $email;
                header("Location: account.php?success=1");

                exit;
            } else {
                $errorMessage = "Error updating profile. Please try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accessory List</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="stylesheetlast.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
</head>

<body>
    <?php include('sidemenu.php'); ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1>My Account</h1>
        </div>

        <div class="form-container">
            <?php if (!empty($errorMessage)) { ?>
                <div class="error-box"> <?php echo htmlspecialchars($errorMessage); ?> </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
                <div class="success-box">Profile updated successfully!</div>
            <?php } ?>

            <div class="form-body">
                <form id="accountForm" action="account.php" method="POST" enctype="multipart/form-data">
                    <div class="form-element">
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required>
                    </div>

                    <div class="form-element">
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" required>
                    </div>

                    <div class="form-element">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>

                    <div class="form-element">
                        <label for="password">New Password (optional):</label>
                        <input type="password" id="password" name="password" placeholder="Enter new password">
                    </div>

                    <div class="form-element">
                        <label for="image">Profile Image:</label>
                        <input type="file" id="image" name="image">
                        <?php if (!empty($image)) { ?>
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Profile Image" style="max-width: 100px;">
                        <?php } ?>
                    </div>

                    <div class="form-footer">
                        <button class="secondary-button" type="reset">Cancel</button>
                        <button class="primary-button" type="submit">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
