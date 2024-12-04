<?php
// Database connection

include('connection.php');

// Variables
$licenseID = isset($_GET['licenseID']) ? intval($_GET['licenseID']) : null;
$isEditMode = $licenseID !== null;

// Fetch categories, manufacturers, suppliers, offices, and statuses
$categories = $conn->query("SELECT categoryID, name FROM Category");
$manufacturers = $conn->query("SELECT manufacturerID, name FROM Manufacturer");
$suppliers = $conn->query("SELECT supplierID, name FROM Supplier");
$offices = $conn->query("SELECT officeID, name FROM Office");
$statuses = $conn->query("SELECT statusID, type FROM Status");

// Initialize variables
$name = $productKey = $seats = $licensedToName = $licensedToEmail = $expDate =
    $purchaseDate = $purchaseCost = $supplierID = $image = $notes = $categoryID = $available = $statusID = $manufacturerID = $officeID = "";

// Edit mode
if ($isEditMode) {
    $stmt = $conn->prepare("SELECT l.licenseID, 
i.name, 
i.image, 
i.notes, 
c.categoryID AS categoryID, 
c.name AS categoryName, 
m.manufacturerID AS manufacturerID, 
m.name AS manufacturerName, 
st.statusID AS statusID,  
st.type AS `type`,  
o.officeID AS officeID, 
o.name AS officeName, 
l.productKey, 
l.seats, 
l.available, 
l.licensedToName, 
l.licensedToEmail, 
l.expDate, 
ord.purchaseDate, 
ord.purchaseCost,
su.supplierID AS supplierID,
su.name AS supplierName
FROM license l
LEFT JOIN item i ON i.itemID = l.itemID
LEFT JOIN `status` st ON i.statusID = st.statusID
LEFT JOIN `order` ord ON i.orderID = ord.orderID
LEFT JOIN `supplier` su ON ord.supplierID = su.supplierID
LEFT JOIN category c ON i.categoryID = c.categoryID
LEFT JOIN manufacturer m ON i.manufacturerID = m.manufacturerID
LEFT JOIN office o ON i.officeID = o.officeID WHERE licenseID = ?");
    $stmt->bind_param("i", $licenseID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $license = $result->fetch_assoc();
        extract($license);
    } else {
        echo "Invalid License ID.";
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
    <link rel="stylesheet" href="stylesheetlast.css">
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
            <h1><?php echo $isEditMode ? "Edit License" : "Add License"; ?></h1>
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="licenseForm" action="db_context.php" method="POST" enctype="multipart/form-data" onsubmit="return validateLicenseForm()">
                    <input type="hidden" name="licenseID" value="<?php echo htmlspecialchars($licenseID); ?>">

                    <div class="form-element">
                        <label for="licenseName">License Name:</label>
                        <input type="text" id="licenseName" name="licenseName" placeholder="License Name" value="<?php echo htmlspecialchars($name); ?>">
                    </div>

                    <div class="form-element">
                        <label for="productKey">Product Key:</label>
                        <input type="text" id="productKey" name="productKey" placeholder="Product Key" value="<?php echo htmlspecialchars($productKey); ?>">
                    </div>

                    <div class="form-element">
                        <label for="seats">Seats:</label>
                        <input type="number" id="seats" name="seats" placeholder="Number of Seats" value="<?php echo htmlspecialchars($seats); ?>">
                    </div>
                    <div class="form-element">
                        <label for="available">Available:</label>
                        <input type="number" id="available" name="available" placeholder="Available Seats" value="<?php echo htmlspecialchars($available); ?>">
                    </div>

                    <div class="form-element">
                        <label for="licensedToName">Licensed To Name:</label>
                        <input type="text" id="licensedToName" name="licensedToName" placeholder="Licensed To Name" value="<?php echo htmlspecialchars($licensedToName); ?>">
                    </div>

                    <div class="form-element">
                        <label for="licensedToEmail">Licensed To Email:</label>
                        <input type="email" id="licensedToEmail" name="licensedToEmail" placeholder="Licensed To Email" value="<?php echo htmlspecialchars($licensedToEmail); ?>">
                    </div>

                    <div class="form-element">
                        <label for="expiryDate">Expiry Date:</label>
                        <input type="date" id="expDate" name="expDate" value="<?php echo htmlspecialchars($expDate); ?>">
                    </div>

                    <div class="form-element">
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image">
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
                        <button id="add-category-btn">Add</button>
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
                        <button id="add-manufacturer-btn">Add</button>
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
                        <button id="add-office-btn">Add</button>
                    </div>

                    <!-- Order Details Section -->
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
                                <button id="add-supplier-btn">Add</button>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px;" id="error-box" class="error-box"></div>

                    <div class="form-footer">
                       
                        <button class="primary-button" type="submit" name="<?php echo $isEditMode ? 'updateLicenseButton' : 'addLicenseButton'; ?>">
                            <?php echo $isEditMode ? "Update License" : "Add License"; ?>
                        </button>
                    </div>


                </form>
            </div>
        </div>
    </main>
    <div class="overlay" id="popup-overlay"> </div>
    <div id="popup-box-category" class="popup-box">
        <div class="popup-header">
            <button class="close-btn" id="close-popup-category">&times;</button>
            <h2>Add Category</h2>
        </div>
        <form action="db_context.php" method="POST" enctype="multipart/form-data">
            <div class="popup-element">
                <label for="categoryName">Category Name:</label>
                <input type="text" id="categoryName" placeholder="Category Name" name="categoryName" required>
            </div>
            <div class="popup-footer">

                <button class="action-btn" name="addCategoryButton" id="confirm-add-category">Confirm</button>
            </div>
        </form>
    </div>

    <div id="popup-box-manufacturer" class="popup-box">
        <div class="popup-header">
            <button class="close-btn" id="close-popup-manufacturer">&times;</button>
            <h2>Add Manufacturer</h2>
        </div>
        <form action="db_context.php" method="POST" enctype="multipart/form-data">
            <div class="popup-element">
                <label for="manufacturerName">Manufacturer Name:</label>
                <input type="text" id="manufacturerName" placeholder="Manufacturer Name" name="manufacturerName" required>
            </div>
            <div class="popup-element">
                <label for="url">URL:</label>
                <input type="text" id="url" placeholder="URL" name="url">
            </div>
            <div class="popup-element">
                <label for="email">Support Email:</label>
                <input type="text" id="email" placeholder="Email" name="email">
            </div>
            <div class="popup-element">
                <label for="phone">Support Phone:</label>
                <input type="text" id="phone" placeholder="Phone" name="phone">
            </div>
            <div class="popup-footer">

                <button class="action-btn" name="addManufacturerButton" id="confirm-add-manufacturer">Confirm</button>
            </div>
        </form>
    </div>


    <div id="popup-box-office" class="popup-box">
        <div class="popup-header">
            <button class="close-btn" id="close-popup-office">&times;</button>
            <h2>Add Office</h2>
        </div>
        <form action="db_context.php" method="POST" enctype="multipart/form-data">
            <div class="popup-element">
                <label for="officeName">Office Name:</label>
                <input type="text" id="officeName" placeholder="Office Name" name="officeName" required>
            </div>
            <div class="popup-element">
                <label for="address">Address:</label>
                <input type="text" id="address" placeholder="Address" name="address">
            </div>
            <div class="popup-element">
                <label for="email">Email:</label>
                <input type="text" id="email" placeholder="Email" name="email">
            </div>
            <div class="popup-element">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" placeholder="Phone" name="phone">
            </div>
            <div class="popup-footer">

                <button class="action-btn" name="addOfficeButton" id="confirm-add-office">Confirm</button>
            </div>
        </form>
    </div>

    <div id="popup-box-supplier" class="popup-box">
        <div class="popup-header">
            <button class="close-btn" id="close-popup-supplier">&times;</button>
            <h2>Add Supplier</h2>
        </div>
        <form action="db_context.php" method="POST" enctype="multipart/form-data">
            <div class="popup-element">
                <label for="supplierName">Supplier Name:</label>
                <input type="text" id="supplierName" placeholder="Supplier Name" name="supplierName" required>
            </div>
            <div class="popup-element">
                <label for="address">Address:</label>
                <input type="text" id="address" placeholder="Address" name="address">
            </div>
            <div class="popup-element">
                <label for="contactName">Contact Name:</label>
                <input type="text" id="contactName" placeholder="Contact Name" name="contactName">
            </div>
            <div class="popup-element">
                <label for="email">Email:</label>
                <input type="text" id="email" placeholder="Email" name="email">
            </div>
            <div class="popup-element">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" placeholder="Phone" name="phone">
            </div>
            <div class="popup-footer">

                <button class="action-btn" name="addSupplierButton" id="confirm-add-supplier">Confirm</button>
            </div>
        </form>
    </div>

    <script>
        // Toggle section visibility
        document.getElementById("orderHeading").addEventListener("click", () => {
            const orderDetails = document.getElementById("orderDetails");
            orderDetails.classList.toggle("hidden");
        });

        function validateLicenseForm() {
            const licenseName = document.getElementById("licenseName").value.trim();
            const status = document.getElementById("status").value.trim();
            const category = document.getElementById("category").value.trim();
            const seats = document.getElementById("seats").value.trim();
            const available = document.getElementById("available").value.trim();
            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!licenseName) {
                errorBox.innerHTML = "License Name is required.";
                return false;
            }

            if (!seats) {
                errorBox.innerHTML = "Seats are required.";
                return false;
            }

            if (isNaN(seats) || seats <= 0) {
                errorBox.innerHTML = "Seats must be a positive number.";
                return false;
            }

            if (!available) {
                errorBox.innerHTML = "Available Seats are required.";
                return false;
            }

            if (isNaN(available) || available < 0) {
                errorBox.innerHTML = "Available Seats must be a non-negative number.";
                return false;
            }

            if (parseInt(available) > parseInt(seats)) {
                errorBox.innerHTML = "Available Seats cannot be greater than Total Seats.";
                return false;
            }

            if (!category) {
                errorBox.innerHTML = "Category is required.";
                return false;
            }

            if (!status) {
                errorBox.innerHTML = "Status is required.";
                return false;
            }

            return true;
        }
    </script>
    <script src="expand.js"></script>
    <script src="popupLast.js"></script>