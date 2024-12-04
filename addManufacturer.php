<?php
// Database connection

include('connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["addManufacturerButton"])) {

        $name = $_POST['name'] ?? '';
        $url = $_POST['url'] ?? '';
        $supportEmail = $_POST['supportEmail'] ?? '';
        $supportPhone = $_POST['supportPhone'] ?? '';

        if (!empty($name)) {
            $conn->query("INSERT INTO manufacturer (name, url, supportEmail, supportPhone) VALUES ('$name', '$url', '$supportEmail', '$supportPhone')");

            if ($conn->affected_rows > 0) {
                header("Location: manufacturers.php");
                exit;
            } else {
                echo "Error adding manufacturer: " . $conn->error;
            }
        } else {
            echo "All fields are required.";
        }
    }

    if (isset($_POST["updateManufacturerButton"])) {
        $name = $_POST['name'] ?? '';
        $url = $_POST['url'] ?? '';
        $supportEmail = $_POST['supportEmail'] ?? '';
        $supportPhone = $_POST['supportPhone'] ?? '';
        $manufacturerID = $_POST['manufacturerID'] ?? '';

        if (!empty($name) ) {
            $conn->query("UPDATE manufacturer SET name = '$name', url = '$url', supportEmail = '$supportEmail', supportPhone = '$supportPhone' WHERE manufacturerID = $manufacturerID");

            if ($conn->affected_rows > 0) {
                header("Location: manufacturers.php");
                exit;
            } else {
                echo "Error updating manufacturer: " . $conn->error;
            }
        } else {
            echo "All fields are required.";
        }
    }
}

// Variables
$manufacturerID = isset($_GET['manufacturerID']) ? intval($_GET['manufacturerID']) : null;
$isEditMode = $manufacturerID !== null;

// Initialize variables
$name = "";
$url = "";
$supportEmail = "";
$supportPhone = "";

if ($isEditMode) {
    $stmt = $conn->prepare("SELECT manufacturerID, name, url, supportEmail, supportPhone FROM manufacturer WHERE manufacturerID = ?");
    $stmt->bind_param("i", $manufacturerID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $manufacturer = $result->fetch_assoc();
        // Populate variables with existing data
        extract($manufacturer);
    } else {
        echo "Invalid Manufacturer ID.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Add Manufacturer</title>
    <link rel="stylesheet" href="stylesheetlast.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <?php include('sidemenu.php'); ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1><?php echo $isEditMode ? "Edit Manufacturer" : "Add Manufacturer"; ?></h1>
            
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="manufacturerForm" action="addManufacturer.php" method="POST" enctype="multipart/form-data" onsubmit="return validateManufacturerForm()">
                    <input type="hidden" name="manufacturerID" value="<?php echo htmlspecialchars($manufacturerID ?? ''); ?>">

                    <!-- Name -->
                    <div class="form-element">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Manufacturer Name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <!-- URL -->
                    <div class="form-element">
                        <label for="url">URL:</label>
                        <input type="text" id="url" name="url" placeholder="Manufacturer URL" value="<?php echo htmlspecialchars($url); ?>">
                    </div>

                    <!-- Support Email -->
                    <div class="form-element">
                        <label for="supportEmail">Support Email:</label>
                        <input type="email" id="supportEmail" name="supportEmail" placeholder="Support Email" value="<?php echo htmlspecialchars($supportEmail); ?>">
                    </div>

                    <!-- Support Phone -->
                    <div class="form-element">
                        <label for="supportPhone">Support Phone:</label>
                        <input type="text" id="supportPhone" name="supportPhone" placeholder="Support Phone" value="<?php echo htmlspecialchars($supportPhone); ?>">
                    </div>

                    <div style="text-align: center; margin-top: 20px;" id="error-box" class="error-box"></div>

                    <!-- Form Footer -->
                    <div class="form-footer">
                        
                        <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateManufacturerButton' : 'addManufacturerButton'; ?>">
                            <?php echo $isEditMode ? "Update Manufacturer" : "Add Manufacturer"; ?>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        function validateManufacturerForm() {
            const name = document.getElementById("name").value.trim();
            const url = document.getElementById("url").value.trim();
            const supportEmail = document.getElementById("supportEmail").value.trim();
            const supportPhone = document.getElementById("supportPhone").value.trim();

            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!name ) {
                errorBox.innerHTML = "Manufacturer Name required.";
                return false;
            }

            return true;
        }
    </script>

    <script src="expand.js"></script>
    <script src="popupLast.js"></script>
</body>

</html>
