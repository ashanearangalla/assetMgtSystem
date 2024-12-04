<?php
// Database connection
include('connection.php');

// Variables
$auditID = isset($_GET['auditID']) ? intval($_GET['auditID']) : null;
$isEditMode = $auditID !== null;

// Fetch items by joining the Asset table with the Item table
$itemsQuery = $conn->query("SELECT a.assetID, i.name, i.itemID FROM Asset a LEFT JOIN Item i ON a.itemID = i.itemID");

// Initialize variables
$auditDate = $auditor = $findings = $itemID = "";

// Edit mode: fetch existing audit data if editing
if ($isEditMode) {
    $stmt = $conn->prepare("SELECT a.auditDate, a.auditor, a.findings, a.itemID FROM Audit a WHERE a.auditID = ?");
    $stmt->bind_param("i", $auditID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $audit = $result->fetch_assoc();
        extract($audit); // Populate variables
    } else {
        echo "Invalid Audit ID.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditMode ? "Edit Audit" : "Add Audit"; ?></title>
    <link rel="stylesheet" href="stylesheetlast.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>

<body>
    <?php include('sidemenu.php'); ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1><?php echo $isEditMode ? "Edit Audit" : "Add Audit"; ?></h1>
        </div>

        <div class="form-container">
            <form id="auditForm" action="db_context.php" method="POST" onsubmit="return validateAuditForm()">
                <input type="hidden" name="auditID" value="<?php echo htmlspecialchars($auditID); ?>">

                <div class="form-element">
                    <label for="auditDate">Audit Date:</label>
                    <input type="date" id="auditDate" name="auditDate" value="<?php echo htmlspecialchars($auditDate); ?>" required>
                </div>

                <div class="form-element">
                    <label for="auditor">Auditor:</label>
                    <input type="text" id="auditor" name="auditor" placeholder="Auditor Name" value="<?php echo htmlspecialchars($auditor); ?>" required>
                </div>

                <div class="form-element">
                    <label for="findings">Findings:</label>
                    <textarea id="findings" name="findings" placeholder="Audit Findings" required><?php echo htmlspecialchars($findings); ?></textarea>
                </div>

                <div class="form-element">
                    <label for="item">Item:</label>
                    <select id="item" name="itemID" required>
                        <option value="" selected hidden>Select Item</option>
                        <?php while ($item = $itemsQuery->fetch_assoc()) { ?>
                            <option value="<?php echo $item['itemID']; ?>" <?php echo $itemID == $item['itemID'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($item['name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div id="error-box" class="error-box"></div>

                <div class="form-footer">
                   
                    <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateAuditButton' : 'addAuditButton'; ?>">
                        <?php echo $isEditMode ? "Update Audit" : "Add Audit"; ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function validateAuditForm() {
            const auditDate = document.getElementById("auditDate").value.trim();
            const auditor = document.getElementById("auditor").value.trim();
            const findings = document.getElementById("findings").value.trim();
            const item = document.getElementById("item").value.trim();
            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!auditDate) {
                errorBox.innerHTML = "Audit date is required.";
                return false;
            }
            if (!auditor) {
                errorBox.innerHTML = "Auditor name is required.";
                return false;
            }
            if (!findings) {
                errorBox.innerHTML = "Findings are required.";
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
