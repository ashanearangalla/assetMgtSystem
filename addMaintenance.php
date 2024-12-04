<?php
// Database connection
include('connection.php');

// Variables
$maintenanceID = isset($_GET['maintenanceID']) ? intval($_GET['maintenanceID']) : null;
$isEditMode = $maintenanceID !== null;

// Fetch assets by joining the Asset table with the Item table
$assetsQuery = $conn->query("
    SELECT a.assetID, i.name, i.itemID
    FROM Asset a
    LEFT JOIN Item i ON a.itemID = i.itemID
");

// Initialize variables
$startDate = $completionDate = $type = $description = $cost = $itemID = "";

// Edit mode: fetch existing maintenance data if editing
if ($isEditMode) {
    $stmt = $conn->prepare("
        SELECT m.startDate, m.completionDate, m.type, m.description, m.cost, m.itemID
        FROM Maintenance m
        WHERE m.maintenanceID = ?
    ");
    $stmt->bind_param("i", $maintenanceID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $maintenance = $result->fetch_assoc();
        extract($maintenance); // Populate variables
    } else {
        echo "Invalid Maintenance ID.";
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
            <h1><?php echo $isEditMode ? "Edit Maintenance" : "Add Maintenance"; ?></h1>
        </div>

        <div class="form-container">
            <form id="maintenanceForm" action="db_context.php" method="POST" onsubmit="return validateMaintenanceForm()">
                <input type="hidden" name="maintenanceID" value="<?php echo htmlspecialchars($maintenanceID); ?>">

                <div class="form-element">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>" required>
                </div>

                <div class="form-element">
                    <label for="completionDate">Completion Date:</label>
                    <input type="date" id="completionDate" name="completionDate" value="<?php echo htmlspecialchars($completionDate); ?>" required>
                </div>

                <div class="form-element">
                    <label for="type">Type:</label>
                    <input type="text" id="type" name="type" placeholder="Maintenance Type" value="<?php echo htmlspecialchars($type); ?>" required>
                </div>

                <div class="form-element">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Maintenance Description" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="form-element">
                    <label for="cost">Cost:</label>
                    <input type="number" id="cost" name="cost" step="0.01" placeholder="Cost" value="<?php echo htmlspecialchars($cost); ?>" required>
                </div>

                <div class="form-element">
                    <label for="item">Item:</label>
                    <select id="item" name="itemID" required>
                        <option value="" selected hidden>Select Asset</option>
                        <?php while ($asset = $assetsQuery->fetch_assoc()) { ?>
                            <option value="<?php echo $asset['itemID']; ?>" <?php echo $itemID == $asset['itemID'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($asset['name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div id="error-box" class="error-box"></div>

                <div class="form-footer">
                    
                    <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateMaintenanceButton' : 'addMaintenanceButton'; ?>">
                        <?php echo $isEditMode ? "Update Maintenance" : "Add Maintenance"; ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function validateMaintenanceForm() {
            const startDate = document.getElementById("startDate").value.trim();
            const completionDate = document.getElementById("completionDate").value.trim();
            const type = document.getElementById("type").value.trim();
            const description = document.getElementById("description").value.trim();
            const cost = document.getElementById("cost").value.trim();
            const item = document.getElementById("item").value.trim();
            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!startDate) {
                errorBox.innerHTML = "Start date is required.";
                return false;
            }
            if (!completionDate) {
                errorBox.innerHTML = "Completion date is required.";
                return false;
            }
            if (!type) {
                errorBox.innerHTML = "Type is required.";
                return false;
            }
            if (!description) {
                errorBox.innerHTML = "Description is required.";
                return false;
            }
            if (!cost || isNaN(cost) || parseFloat(cost) < 0) {
                errorBox.innerHTML = "Valid cost is required.";
                return false;
            }
            if (!item) {
                errorBox.innerHTML = "Item selection is required.";
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
