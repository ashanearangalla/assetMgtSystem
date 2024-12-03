<?php
// Database connection

include('connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["addOfficeButton"])) {

        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if (!empty($name) && !empty($address)) {
            $conn->query("INSERT INTO office (name, address, email, phone) VALUES ('$name', '$address', '$email', '$phone')");

            if ($conn->affected_rows > 0) {
                header("Location: offices.php");
                exit;
            } else {
                echo "Error adding office: " . $conn->error;
            }
        } else {
            echo "Name and Address are required fields.";
        }
    }

    if (isset($_POST["updateOfficeButton"])) {
        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $officeID = $_POST['officeID'] ?? '';

        if (!empty($name) && !empty($address)) {
            $conn->query("UPDATE office SET name = '$name', address = '$address', email = '$email', phone = '$phone' WHERE officeID = $officeID");

            if ($conn->affected_rows > 0) {
                header("Location: offices.php");
                exit;
            } else {
                echo "Error updating office: " . $conn->error;
            }
        } else {
            echo "Name and Address are required fields.";
        }
    }
}

// Variables
$officeID = isset($_GET['officeID']) ? intval($_GET['officeID']) : null;
$isEditMode = $officeID !== null;

// Initialize variables
$name = "";
$address = "";
$email = "";
$phone = "";

if ($isEditMode) {
    $stmt = $conn->prepare("SELECT officeID, name, address, email, phone FROM office WHERE officeID = ?");
    $stmt->bind_param("i", $officeID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $office = $result->fetch_assoc();
        // Populate variables with existing data
        extract($office);
    } else {
        echo "Invalid Office ID.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title><?php echo $isEditMode ? "Edit Office" : "Add Office"; ?></title>
    <link rel="stylesheet" href="stylesheetlast.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <?php include('sidemenu.php'); ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1><?php echo $isEditMode ? "Edit Office" : "Add Office"; ?></h1>
            <button>Create New</button>
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="officeForm" action="addOffice.php" method="POST" enctype="multipart/form-data" onsubmit="return validateOfficeForm()">
                    <input type="hidden" name="officeID" value="<?php echo htmlspecialchars($officeID ?? ''); ?>">

                    <!-- Name -->
                    <div class="form-element">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Office Name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <!-- Address -->
                    <div class="form-element">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" placeholder="Office Address" value="<?php echo htmlspecialchars($address); ?>">
                    </div>

                    <!-- Email -->
                    <div class="form-element">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Office Email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <!-- Phone -->
                    <div class="form-element">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" placeholder="Office Phone" value="<?php echo htmlspecialchars($phone); ?>">
                    </div>

                    <div style="text-align: center; margin-top: 20px;" id="error-box" class="error-box"></div>

                    <!-- Form Footer -->
                    <div class="form-footer">
                        <button class="secondary-button" type="reset">Cancel</button>
                        <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateOfficeButton' : 'addOfficeButton'; ?>">
                            <?php echo $isEditMode ? "Update Office" : "Add Office"; ?>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        function validateOfficeForm() {
            const name = document.getElementById("name").value.trim();
            const address = document.getElementById("address").value.trim();
            const email = document.getElementById("email").value.trim();
            const phone = document.getElementById("phone").value.trim();

            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!name || !address) {
                errorBox.innerHTML = "Name and Address are required fields.";
                return false;
            }

            return true;
        }
    </script>

    <script src="expand.js"></script>
    <script src="popupLast.js"></script>
</body>

</html>
