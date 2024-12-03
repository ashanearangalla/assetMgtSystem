<?php
// Database connection

include('connection.php');

// Get licenseID from GET parameter
$licenseID = isset($_GET['licenseID']) ? intval($_GET['licenseID']) : null;
if (!$licenseID) {
    echo "Invalid License ID.";
    exit;
}

$available='';

// Fetch license details based on licenseID
$stmt = $conn->prepare("
    SELECT l.licenseID, 
           i.name AS licenseName, 
           c.name AS categoryName, 
           l.productKey,
           l.available,
           i.itemID
    FROM license l
    LEFT JOIN item i ON i.itemID = l.itemID
    LEFT JOIN category c ON i.categoryID = c.categoryID
    WHERE l.licenseID = ?");
$stmt->bind_param("i", $licenseID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "License not found.";
    exit;
}
$license = $result->fetch_assoc();

// Fetch users and offices for dropdowns
$users = $conn->query("SELECT userID, fname, lname FROM User");
$offices = $conn->query("SELECT officeID, name FROM Office");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>License Checkout</title>
    <link rel="stylesheet" href="stylesheetlast.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <?php include('sidemenu.php'); ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1>License Checkout</h1>
            <button>Create New</button>
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="checkoutForm" action="db_context.php" method="POST" onsubmit="return validateCheckoutForm()">
                    <input type="hidden" name="licenseID" value="<?php echo htmlspecialchars($licenseID); ?>">
                    <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($license['itemID']); ?>">
                    <input type="hidden" name="available" value="<?php echo htmlspecialchars($license['available']); ?>">

                    <!-- Display License Name -->
                    <div class="form-element">
                        <label for="licenseName">License Name:</label>
                        <input type="text" id="licenseName" name="licenseName" 
                               value="<?php echo htmlspecialchars($license['licenseName']); ?>" readonly>
                    </div>

                    <!-- Display Category -->
                    <div class="form-element">
                        <label for="category">Category:</label>
                        <input type="text" id="category" name="category" 
                               value="<?php echo htmlspecialchars($license['categoryName']); ?>" readonly>
                    </div>

                    <!-- Display Product Key -->
                    <div class="form-element">
                        <label for="productKey">Product Key:</label>
                        <input type="text" id="productKey" name="productKey" 
                               value="<?php echo htmlspecialchars($license['productKey']); ?>" readonly>
                    </div>

                    <!-- Assignment Date -->
                    <div class="form-element">
                        <label for="assignmentDate">Assignment Date:</label>
                        <input type="date" id="assignmentDate" name="assignmentDate" required>
                    </div>

                    <!-- User Dropdown -->
                    <div class="form-element">
                        <label for="user">User:</label>
                        <select id="user" name="user" required>
                            <option value="" selected hidden>Select User</option>
                            <?php while ($user = $users->fetch_assoc()) { ?>
                                <option value="<?php echo $user['userID']; ?>">
                                    <?php echo htmlspecialchars($user['fname']) . " " . htmlspecialchars($user['lname']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    

                    <!-- Checkout Notes -->
                    <div class="form-element">
                        <label for="checkoutNotes">Checkout Notes:</label>
                        <textarea id="checkoutNotes" name="checkoutNotes" placeholder="Enter any notes"></textarea>
                    </div>

                    <div style="text-align: center;" id="error-box" class="error-box"></div>

                    <!-- Form Footer -->
                    <div class="form-footer">
                        <button class="secondary-button" type="reset">Cancel</button>
                        <button class="primary-button" type="submit" name="addLicenseCheckoutButton">Checkout License</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function validateCheckoutForm() {
            const assignmentDate = document.getElementById("assignmentDate").value.trim();
            const user = document.getElementById("user").value.trim();
            const office = document.getElementById("office").value.trim();
            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!assignmentDate) {
                errorBox.innerHTML = "Assignment Date is required.";
                return false;
            }

            if (!user) {
                errorBox.innerHTML = "User is required.";
                return false;
            }

            if (!office) {
                errorBox.innerHTML = "Office is required.";
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
