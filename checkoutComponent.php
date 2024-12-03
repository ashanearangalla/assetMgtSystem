<?php
// Database connection

include('connection.php');

// Get componentID from GET parameter
$componentID = isset($_GET['componentID']) ? intval($_GET['componentID']) : null;
if (!$componentID) {
    echo "Invalid Component ID.";
    exit;
}

// Fetch component details based on componentID
$stmt = $conn->prepare("
    SELECT c.componentID, 
           i.name AS componentName, 
           i.itemID, 
           cat.name AS categoryName, 
           c.quantity AS totalQuantity,
           c.remaining
    FROM component c
    LEFT JOIN item i ON c.itemID = i.itemID
    LEFT JOIN category cat ON i.categoryID = cat.categoryID
    WHERE c.componentID = ?");
$stmt->bind_param("i", $componentID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Component not found.";
    exit;
}
$component = $result->fetch_assoc();

// Fetch users for the dropdown
$users = $conn->query("SELECT userID, fname, lname FROM User");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Component Checkout</title>
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
            <h1>Component Checkout</h1>
            <button>Create New</button>
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="checkoutForm" action="db_context.php" method="POST" onsubmit="return validateCheckoutForm()">
                    <input type="hidden" name="componentID" value="<?php echo htmlspecialchars($componentID); ?>">
                    <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($component['itemID']); ?>">

                    <!-- Display Component Name -->
                    <div class="form-element">
                        <label for="componentName">Component Name:</label>
                        <input type="text" id="componentName" name="componentName" 
                               value="<?php echo htmlspecialchars($component['componentName']); ?>" readonly>
                    </div>

                    <!-- Display Category -->
                    <div class="form-element">
                        <label for="category">Category:</label>
                        <input type="text" id="category" name="category" 
                               value="<?php echo htmlspecialchars($component['categoryName']); ?>" readonly>
                    </div>

                    <!-- Display Total Quantity -->
                    <div class="form-element">
                        <label for="totalQuantity">Total Quantity:</label>
                        <input type="text" id="totalQuantity" name="totalQuantity" 
                               value="<?php echo htmlspecialchars($component['totalQuantity']); ?>" readonly>
                    </div>

                    <!-- Display Remaining Quantity -->
                    <div class="form-element">
                        <label for="remaining">Remaining:</label>
                        <input type="text" id="remaining" name="remaining" 
                               value="<?php echo htmlspecialchars($component['remaining']); ?>" readonly>
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

                    <!-- Quantity User is Taking -->
                    <div class="form-element">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" placeholder="Quantity" name="quantity" min="1" 
                               max="<?php echo htmlspecialchars($component['remaining']); ?>" required>
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
                        <button class="primary-button" type="submit" name="addComponentCheckoutButton">Checkout Component</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function validateCheckoutForm() {
            const assignmentDate = document.getElementById("assignmentDate").value.trim();
            const user = document.getElementById("user").value.trim();
            const quantity = document.getElementById("quantity").value.trim();
            const remaining = parseInt(document.getElementById("remaining").value.trim(), 10);
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

            if (!quantity || quantity <= 0) {
                errorBox.innerHTML = "Please enter a valid quantity.";
                return false;
            }

            if (quantity > remaining) {
                errorBox.innerHTML = "Quantity exceeds the remaining stock.";
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
