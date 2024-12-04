<?php
// Database connection

include('connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["addSupplierButton"])) {

        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $contactName = $_POST['contactName'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if (!empty($name) && !empty($address)) {
            $conn->query("INSERT INTO supplier (name, address, contactName, email, phone) VALUES ('$name', '$address', '$contactName', '$email', '$phone')");

            if ($conn->affected_rows > 0) {
                header("Location: suppliers.php");
                exit;
            } else {
                echo "Error adding supplier: " . $conn->error;
            }
        } else {
            echo "All fields are required.";
        }
    }

    if (isset($_POST["updateSupplierButton"])) {
        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $contactName = $_POST['contactName'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $supplierID = $_POST['supplierID'] ?? '';

        if (!empty($name) && !empty($address)) {
            $conn->query("UPDATE supplier SET name = '$name', address = '$address', contactName = '$contactName', email = '$email', phone = '$phone' WHERE supplierID = $supplierID");

            if ($conn->affected_rows > 0) {
                header("Location: suppliers.php");
                exit;
            } else {
                echo "Error updating supplier: " . $conn->error;
            }
        } else {
            echo "All fields are required.";
        }
    }
}

// Variables
$supplierID = isset($_GET['supplierID']) ? intval($_GET['supplierID']) : null;
$isEditMode = $supplierID !== null;

// Initialize variables
$name = "";
$address = "";
$contactName = "";
$email = "";
$phone = "";

if ($isEditMode) {
    $stmt = $conn->prepare("SELECT supplierID, name, address, contactName, email, phone FROM supplier WHERE supplierID = ?");
    $stmt->bind_param("i", $supplierID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $supplier = $result->fetch_assoc();
        // Populate variables with existing data
        extract($supplier);
    } else {
        echo "Invalid Supplier ID.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title><?php echo $isEditMode ? "Edit Supplier" : "Add Supplier"; ?></title>
    <link rel="stylesheet" href="stylesheetlast.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <?php include('sidemenu.php'); ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1><?php echo $isEditMode ? "Edit Supplier" : "Add Supplier"; ?></h1>
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="supplierForm" action="addSupplier.php" method="POST" onsubmit="return validateSupplierForm()">
                    <input type="hidden" name="supplierID" value="<?php echo htmlspecialchars($supplierID ?? ''); ?>">

                    <!-- Name -->
                    <div class="form-element">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Supplier Name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <!-- Address -->
                    <div class="form-element">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" placeholder="Supplier Address" value="<?php echo htmlspecialchars($address); ?>">
                    </div>

                    <!-- Contact Name -->
                    <div class="form-element">
                        <label for="contactName">Contact Name:</label>
                        <input type="text" id="contactName" name="contactName" placeholder="Contact Name" value="<?php echo htmlspecialchars($contactName); ?>">
                    </div>

                    <!-- Email -->
                    <div class="form-element">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <!-- Phone -->
                    <div class="form-element">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>

                    <div style="text-align: center; margin-top: 20px;" id="error-box" class="error-box"></div>

                    <!-- Form Footer -->
                    <div class="form-footer">
                        
                        <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateSupplierButton' : 'addSupplierButton'; ?>">
                            <?php echo $isEditMode ? "Update Supplier" : "Add Supplier"; ?>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        function validateSupplierForm() {
            const name = document.getElementById("name").value.trim();
            const address = document.getElementById("address").value.trim();
            const contactName = document.getElementById("contactName").value.trim();
            const email = document.getElementById("email").value.trim();
            const phone = document.getElementById("phone").value.trim();

            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!name || !address) {
                errorBox.innerHTML = "Name and Address are required.";
                return false;
            }

            return true;
        }
    </script>

    <script src="expand.js"></script>
    <script src="popupLast.js"></script>
</body>

</html>