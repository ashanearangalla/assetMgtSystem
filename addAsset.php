<?php
// Database connection
include('connection.php');

// Variables
$assetID = isset($_GET['assetID']) ? intval($_GET['assetID']) : null;
$isEditMode = $assetID !== null;

// Fetch categories and statuses
$categories = $conn->query("SELECT categoryID, name FROM Category");
$manufacturers = $conn->query("SELECT manufacturerID, name FROM Manufacturer");
$suppliers = $conn->query("SELECT supplierID, name FROM Supplier");
$offices = $conn->query("SELECT officeID, name FROM Office");
$statuses = $conn->query("SELECT statusID, type FROM Status");

// Initialize variables
$assetName = $assetTag = $serialNumber = $modelName = $image = $notes = $categoryID = $statusID = $location =
    $purchaseDate = $warrantyExpiry = $purchaseCost = $checkinCheckout = $manufacturerID = $officeID = $supplierID = $warrantyStartDate = $warrantyEndDate = $name = $serial =  "";

if ($isEditMode) {
    $stmt = $conn->prepare("SELECT a.assetID, 
        i.name, 
        i.image, 
        i.notes, 
        cat.categoryID AS categoryID, 
        cat.name AS categoryName, 
        m.manufacturerID AS manufacturerID, 
        m.name AS manufacturerName, 
        o.officeID AS officeID, 
        o.name AS officeName, 
        a.assetTag, 
        a.serial, 
        a.modelName, 
        a.nextAuditDate, 
        w.startDate AS warrantyStartDate, 
        w.endDate AS warrantyEndDate, 
        ord.purchaseDate, 
        ord.purchaseCost, 
        su.supplierID AS supplierID, 
        su.name AS supplierName, 
        st.type AS `type`,
        st.statusID AS statusID
        FROM asset a
        INNER JOIN item i ON i.itemID = a.itemID
        LEFT JOIN `status` st ON i.statusID = st.statusID
        LEFT JOIN `order` ord ON i.orderID = ord.orderID
        LEFT JOIN `supplier` su ON ord.supplierID = su.supplierID
        LEFT JOIN category cat ON i.categoryID = cat.categoryID
        LEFT JOIN manufacturer m ON i.manufacturerID = m.manufacturerID
        LEFT JOIN office o ON i.officeID = o.officeID
        LEFT JOIN warranty w ON a.warrantyID = w.warrantyID WHERE assetID = ?");
    $stmt->bind_param("i", $assetID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $asset = $result->fetch_assoc();
        // Populate variables with existing data
        extract($asset);
    } else {
        echo "Invalid Asset ID.";
        exit;
    }
}

?>

<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Assets</title>
    <link rel="stylesheet" href="stylesheetassetnew.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <?php

    include('sidemenu.php');
    ?>

    <main class="main-content-create">
        <div class="main-header-create">
            <h1>Manage Assets</h1>
            <button>Create New</button>
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="assetForm" action="db_context.php" method="POST" enctype="multipart/form-data" onsubmit="return validateAssetForm()">
                    <input type="hidden" name="assetID" value="<?php echo htmlspecialchars($assetID); ?>">

                    <div class="form-element">
                        <label for="assetName">Asset Name:</label>
                        <input type="text" id="assetName" name="assetName" placeholder="Asset Name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <div class="form-element">
                        <label for="assetTag">Asset Tag:</label>
                        <input type="text" id="assetTag" name="assetTag" placeholder="Asset Tag" value="<?php echo htmlspecialchars($assetTag); ?>">
                    </div>

                    <div class="form-element">
                        <label for="serialNumber">Serial Number:</label>
                        <input type="text" id="serialNumber" name="serialNumber" placeholder="Serial Number" value="<?php echo htmlspecialchars($serial); ?>">
                    </div>

                    <div class="form-element">
                        <label for="modelName">Model Name:</label>
                        <input type="text" id="modelName" name="modelName" placeholder="Model Name" value="<?php echo htmlspecialchars($modelName); ?>">
                    </div>

                    <div class="form-element">
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>">
                    </div>

                    <div class="form-element">
                        <label for="notes">Notes:</label>
                        <textarea id="notes" name="notes" placeholder="Notes"><?php echo htmlspecialchars($notes); ?></textarea>
                    </div>

                    <div class="form-element">
                        <label for="category">Category:</label>
                        <select id="category" name="category">
                        <option value="" selected hidden>Select Category</option>
                            <?php while ($category = $categories->fetch_assoc()) { ?>
                                <option value="<?php echo $category['categoryID']; ?>" <?php echo $categoryID == $category['categoryID'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button id="add-btn">Add</button>
                    </div>

                    <div class="form-element">
                        <label for="manufacturer">Manufacturer:</label>
                        <select id="manufacturer" name="manufacturer">
                        <option value="" selected hidden>Select Manufacturer</option>
                            <?php while ($manufacturer = $manufacturers->fetch_assoc()) { ?>
                                <option value="<?php echo $manufacturer['manufacturerID']; ?>" <?php echo $manufacturerID == $manufacturer['manufacturerID'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($manufacturer['name']); ?>
                                </option>
                            <?php } ?>
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



                    <div class="section">
                        <h3 id="orderHeading">Order Details</h3>
                        <div id="orderDetails" class="hidden">
                            <div class="form-element">
                                <label for="purchaseDate">Purchase Date:</label>
                                <input type="date" id="purchaseDate" name="purchaseDate" value="<?php echo htmlspecialchars($purchaseDate); ?>">
                            </div>
                            <div class="form-element">
                                <label for="purchaseCost">Purchase Cost:</label>
                                <input type="number" id="purchaseCost" name="purchaseCost" step="0.01" placeholder="Purchase Cost" value="<?php echo htmlspecialchars($purchaseCost); ?>">
                            </div>
                            <div class="form-element">
                                <label for="supplier">Supplier:</label>
                                <select id="supplier" name="supplier">
                                <option value="" selected hidden>Select Supplier</option>
                                    <?php while ($supplier = $suppliers->fetch_assoc()) { ?>
                                        <option value="<?php echo $supplier['supplierID']; ?>" <?php echo $supplierID == $supplier['supplierID'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($supplier['name']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="section">
                        <h3 id="warrantyHeading">Warranty Details</h3>
                        <div id="warrantyDetails" class="hidden">
                            <div class="form-element">
                                <label for="warrantyStartDate">Start Date:</label>
                                <input type="date" id="warrantyStartDate" name="warrantyStartDate" value="<?php echo htmlspecialchars($warrantyStartDate); ?>">
                            </div>
                            <div class="form-element">
                                <label for="warrantyEndDate">End Date:</label>
                                <input type="date" id="warrantyEndDate" name="warrantyEndDate" value="<?php echo htmlspecialchars($warrantyEndDate); ?>">
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 20px;" id="error-box" class="error-box"></div>

                    <div class="form-footer">
                        <button class="secondary-button" type="reset">Cancel</button>
                        <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateAssetButton' : 'addAssetButton'; ?>">
                            <?php echo $isEditMode ? "Update Asset" : "Add Asset"; ?>
                        </button>
                    </div>

                    
                </form>



            </div>
        </div>
    </main>
    <div class="overlay" id="popup-overlay"> </div>
    <div id="popup-box" class="popup-box">
        <div class="popup-header">
            <button class="close-btn" id="close-popup">&times;</button>
            <h2>Add Item</h2>

        </div>

        <div class="popup-element">
            <label for="assetName">Asset Name:</label>
            <input type="text" id="assetName" name="assetName">
        </div>
        <div class="popup-element">
            <label for="assetName">Asset Name:</label>
            <input type="text" id="assetName" name="assetName">
        </div>
        <div class="popup-element">
            <label for="assetName">Asset Name:</label>
            <input type="text" id="assetName" name="assetName">
        </div>
        <div class="popup-footer">
            <button class="secondary-button" type="reset">Cancel</button>
            <button class="action-btn" id="confirm-add">Confirm</button>

        </div>

    </div>

    <script>
        function validateAssetForm() {
            const assetName = document.getElementById("assetName").value.trim();
            const assetTag = document.getElementById("assetTag").value.trim();
            const status = document.getElementById("status").value.trim();
            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!assetName) {
                errorBox.innerHTML = "Asset Name is required.";
                return false;
            }

            if (!status) {
                errorBox.innerHTML = "Status is required.";
                return false;
            }

            if (!assetTag) {
                errorBox.innerHTML = "Asset Tag is required.";
                return false;
            }

            return true;
        }
    </script>
    <script src="expand.js"></script>
    <script src="popup.js"></script>
</body>

</html>