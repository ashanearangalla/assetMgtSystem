<?php
require_once('connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["deleteAssetButton"])) {


        $itemID = $_POST['itemID'];
        $sql = "DELETE FROM item WHERE itemID = $itemID";

        if ($conn->query($sql) === TRUE) {
            header("Location: assets.php");
            exit;
        } else {
            header("Location: assets.php");
            exit;
        }
    }
    if (isset($_POST["deleteConsumableButton"])) {


        $itemID = $_POST['itemID'];
        $sql = "DELETE FROM item WHERE itemID = $itemID";

        if ($conn->query($sql) === TRUE) {
            header("Location: consumables.php");
            exit;
        } else {
            header("Location: consumables.php");
            exit;
        }
    }
    if (isset($_POST["deleteComponentButton"])) {


        $itemID = $_POST['itemID'];
        $sql = "DELETE FROM item WHERE itemID = $itemID";

        if ($conn->query($sql) === TRUE) {
            header("Location: components.php");
            exit;
        } else {
            header("Location: components.php");
            exit;
        }
    }
    if (isset($_POST["deleteAccessoryButton"])) {


        $itemID = $_POST['itemID'];
        $sql = "DELETE FROM item WHERE itemID = $itemID";

        if ($conn->query($sql) === TRUE) {
            header("Location: accessories.php");
            exit;
        } else {
            header("Location: accessories.php");
            exit;
        }
    }
    if (isset($_POST["deleteLicenseButton"])) {


        $itemID = $_POST['itemID'];
        $sql = "DELETE FROM item WHERE itemID = $itemID";

        if ($conn->query($sql) === TRUE) {
            header("Location: licenses.php");
            exit;
        } else {
            header("Location: licenses.php");
            exit;
        }
    }

    // Add Asset
    if (isset($_POST["addAssetButton"])) {
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = $_POST['supplier'] ?? '';
        $warrantyStartDate = $_POST['warrantyStartDate'] ?? '';
        $warrantyEndDate = $_POST['warrantyEndDate'] ?? '';
        $assetName = $_POST['assetName'] ?? '';
        $assetTag = $_POST['assetTag'] ?? '';
        $serialNumber = $_POST['serialNumber'] ?? '';
        $modelName = $_POST['modelName'] ?? '';
        $categoryID = $_POST['category'] ?? '';
        $manufacturerID = $_POST['manufacturer'] ?? '';
        $statusID = $_POST['status'] ?? '';
        $officeID = $_POST['office'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $orderID = '';
        $warrantyID = '';


        // Save image to the folder
        $imagePath = 'images/' . $image;
        if ($image) {
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }

        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';

        // Insert into Order table if all order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("INSERT INTO `order` (purchaseDate, purchaseCost, supplierID) VALUES ('$purchaseDate', '$purchaseCost', $supplierID)");
            $orderID = $conn->insert_id;
        } else {
            echo "Order details are incomplete.";
        }

        // Insert into Warranty table if all warranty details are provided
        if (!empty($warrantyStartDate) && !empty($warrantyEndDate)) {
            $conn->query("INSERT INTO warranty (startDate, endDate) VALUES ('$warrantyStartDate', '$warrantyEndDate')");
            $warrantyID = $conn->insert_id;
        } else {
            echo "Warranty details are incomplete.";
        }

        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $warrantyID = !empty($warrantyID) ? $warrantyID : 'NULL';
        $orderID = !empty($orderID) ? $orderID : 'NULL';



        // Insert into Item table
        $conn->query("INSERT INTO item (`name`, `image`, notes, categoryID, manufacturerID, orderID, statusID, officeID)
                      VALUES ('$assetName', '$image', '$notes', $categoryID, $manufacturerID, $orderID, $statusID, $officeID)");
        $itemID = $conn->insert_id;

        // Insert into Asset table
        $conn->query("INSERT INTO asset (assetTag, `serial`, modelName, itemID, warrantyID)
                      VALUES ('$assetTag', '$serialNumber', '$modelName', $itemID, $warrantyID)");

        if ($conn->affected_rows > 0) {
            header("Location: assets.php");
            exit;
        } else {
            echo "Error adding asset: " . $conn->error;
        }
    }

    // Update Asset
    if (isset($_POST["updateAssetButton"])) {
        $assetID = $_POST['assetID'] ?? '';
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = $_POST['supplier'] ?? '';
        $warrantyStartDate = $_POST['warrantyStartDate'] ?? '';
        $warrantyEndDate = $_POST['warrantyEndDate'] ?? '';
        $assetName = $_POST['assetName'] ?? '';
        $assetTag = $_POST['assetTag'] ?? '';
        $serialNumber = $_POST['serialNumber'] ?? '';
        $modelName = $_POST['modelName'] ?? '';

        $categoryID = $_POST['category'] ?? '';
        $manufacturerID = $_POST['manufacturer'] ?? '';
        $statusID = $_POST['status'] ?? '';
        $officeID = $_POST['office'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $orderID = '';
        $warrantyID = '';

        // Check if a new image was uploaded
        if (!empty($image)) {
            // Save new image to the folder
            $imagePath = 'images/' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            // Fetch current image from the database
            $result = $conn->query("SELECT i.image FROM item i INNER JOIN asset a ON i.itemID = a.itemID WHERE a.assetID = $assetID");
            if ($result && $row = $result->fetch_assoc()) {
                $image = $row['image']; // Keep the existing image
            }
        }

        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';

        // Update Order table if order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("UPDATE `order` o
                          INNER JOIN item i ON i.orderID = o.orderID
                          INNER JOIN asset a ON a.itemID = i.itemID
                          SET o.purchaseDate = '$purchaseDate', o.purchaseCost = '$purchaseCost', o.supplierID = $supplierID
                          WHERE a.assetID = $assetID");
        }

        // Update Warranty table if warranty details are provided
        if (!empty($warrantyStartDate) && !empty($warrantyEndDate)) {
            $conn->query("UPDATE warranty w
                          INNER JOIN asset a ON a.warrantyID = w.warrantyID
                          SET w.startDate = '$warrantyStartDate', w.endDate = '$warrantyEndDate'
                          WHERE a.assetID = $assetID");
        }

        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $warrantyID = !empty($warrantyID) ? $warrantyID : 'NULL';
        $orderID = !empty($orderID) ? $orderID : 'NULL';

        // Update Item table
        $conn->query("UPDATE item i
                      INNER JOIN asset a ON a.itemID = i.itemID
                      SET i.name = '$assetName', i.image = '$image', i.notes = '$notes', i.categoryID = $categoryID,
                          i.manufacturerID = $manufacturerID, i.statusID = $statusID, i.officeID = $officeID
                      WHERE a.assetID = $assetID");

        // Update Asset table
        $conn->query("UPDATE asset SET assetTag = '$assetTag', `serial` = '$serialNumber', modelName = '$modelName' WHERE assetID = $assetID");

        header("Location: assets.php");
        exit;
    }



    // Add License
    if (isset($_POST["addLicenseButton"])) {
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        $licenseName = $_POST['licenseName'] ?? '';
        $notes = $_POST['notes'] ?? '';

        $statusID = $_POST['status'] ?? '';

        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';

        $productKey = $_POST['productKey'] ?? '';
        $seats = $_POST['seats'] ?? '';

        $licensedToName = $_POST['licensedToName'] ?? '';
        $licensedToEmail = $_POST['licensedToEmail'] ?? '';
        $expDate = $_POST['expDate'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $orderID = '';


        // Save image to folder
        $imagePath = 'images/' . $image;
        if ($image) {
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }



        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            // Insert into Order table
            $conn->query("INSERT INTO `order` (purchaseDate, purchaseCost, supplierID) VALUES ('$purchaseDate', '$purchaseCost', $supplierID)");
            $orderID = $conn->insert_id;
        }

        $orderID = !empty($orderID) ? $orderID : 'NULL';

        // Insert into Item table
        $conn->query("INSERT INTO item (`name`, `image`, notes, categoryID, manufacturerID, orderID, statusID, officeID)
                  VALUES ('$licenseName', '$image', '$notes', $categoryID, $manufacturerID, $orderID, $statusID, $officeID)");
        $itemID = $conn->insert_id;

        // Insert into License table
        $conn->query("INSERT INTO license (productKey, seats, licensedToName, licensedToEmail, expDate, itemID)
                  VALUES ('$productKey', $seats, '$licensedToName', '$licensedToEmail', '$expDate', $itemID)");

        if ($conn->affected_rows > 0) {
            header("Location: licenses.php");
            exit;
        } else {
            echo "Error adding license: " . $conn->error;
        }
    }

    // Update License
    if (isset($_POST["updateLicenseButton"])) {
        $licenseID = $_POST['licenseID'] ?? '';
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        $licenseName = $_POST['licenseName'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';

        $statusID = $_POST['status'] ?? 'NULL';
        $productKey = $_POST['productKey'] ?? '';
        $seats = $_POST['seats'] ?? '';

        $licensedToName = $_POST['licensedToName'] ?? '';
        $licensedToEmail = $_POST['licensedToEmail'] ?? '';
        $expDate = $_POST['expDate'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $orderID = '';

        // Check if a new image was uploaded
        if (!empty($image)) {
            // Save new image to folder
            $imagePath = 'images/' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            // Fetch current image from the database
            $result = $conn->query("SELECT i.image FROM item i 
                                INNER JOIN license l ON i.itemID = l.itemID 
                                WHERE l.licenseID = $licenseID");
            if ($result && $row = $result->fetch_assoc()) {
                $image = $row['image']; // Keep existing image
            }
        }

        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            // Update Order table
            $conn->query("UPDATE `order` o
                  INNER JOIN item i ON i.orderID = o.orderID
                  INNER JOIN license l ON l.itemID = i.itemID
                  SET o.purchaseDate = '$purchaseDate', o.purchaseCost = '$purchaseCost', o.supplierID = $supplierID
                  WHERE l.licenseID = $licenseID");
        }

        $orderID = !empty($orderID) ? $orderID : 'NULL';
        // Update Item table
        $conn->query("UPDATE item i
                  INNER JOIN license l ON l.itemID = i.itemID
                  SET i.name = '$licenseName', i.image = '$image', i.notes = '$notes', 
                      i.categoryID = $categoryID, i.manufacturerID = $manufacturerID, 
                      i.statusID = $statusID, i.officeID = $officeID
                  WHERE l.licenseID = $licenseID");

        // Update License table
        $conn->query("UPDATE license SET productKey = '$productKey', seats = $seats, 
                  licensedToName = '$licensedToName', licensedToEmail = '$licensedToEmail', 
                  expDate = '$expDate' WHERE licenseID = $licenseID");

        if ($conn->affected_rows > 0) {
            header("Location: licenses.php");
            exit;
        } else {
            header("Location: licenses.php");
            exit;
        }
    }


    if (isset($_POST["addAccessoryButton"])) {
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        $warrantyStartDate = $_POST['warrantyStartDate'] ?? '';
        $warrantyEndDate = $_POST['warrantyEndDate'] ?? '';
        $accessoryName = $_POST['accessoryName'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $modelNo = $_POST['modelNo'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $statusID = $_POST['status'] ?? '';
        
        $orderID = '';
        $warrantyID = '';


        // Save image to the folder
        $imagePath = 'images/' . $image;
        if ($image) {
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }


        // Insert into Order table if all order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("INSERT INTO `order` (purchaseDate, purchaseCost, supplierID) VALUES ('$purchaseDate', '$purchaseCost', $supplierID)");
            $orderID = $conn->insert_id;
        } else {
            echo "Order details are incomplete.";
        }

        // Insert into Warranty table if all warranty details are provided
        if (!empty($warrantyStartDate) && !empty($warrantyEndDate)) {
            $conn->query("INSERT INTO warranty (startDate, endDate) VALUES ('$warrantyStartDate', '$warrantyEndDate')");
            $warrantyID = $conn->insert_id;
        } else {
            echo "Warranty details are incomplete.";
        }


        $warrantyID = !empty($warrantyID) ? $warrantyID : 'NULL';
        $orderID = !empty($orderID) ? $orderID : 'NULL';


        // Insert into Item table
        $conn->query("INSERT INTO item (`name`, `image`, notes, categoryID, manufacturerID, orderID, statusID, officeID)
                      VALUES ('$accessoryName', '$image', '$notes', $categoryID, $manufacturerID, $orderID, $statusID, $officeID)");
        $itemID = $conn->insert_id;

        // Insert into Asset table
        $conn->query("INSERT INTO accessory (modelNo, quantity, itemID, warrantyID)
                      VALUES ('$modelNo', '$quantity', $itemID, $warrantyID)");

        if ($conn->affected_rows > 0) {
            header("Location: accessories.php");
            exit;
        } else {
            echo "Error adding asset: " . $conn->error;
        }
    }




    if (isset($_POST["updateAccessoryButton"])) {
        $accessoryID = $_POST['accessoryID'] ?? '';
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        $warrantyStartDate = $_POST['warrantyStartDate'] ?? '';
        $warrantyEndDate = $_POST['warrantyEndDate'] ?? '';
        $accessoryName = $_POST['accessoryName'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $modelNo = $_POST['modelNo'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $statusID = $_POST['status'] ?? '';
        
        $orderID = '';
        $warrantyID = '';
        // Check if a new image was uploaded
        if (!empty($image)) {
            // Save new image to the folder
            $imagePath = 'images/' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            // Fetch current image from the database
            $result = $conn->query("SELECT i.image FROM item i INNER JOIN accessory a ON i.itemID = a.itemID WHERE a.accessoryID = $accessoryID");
            if ($result && $row = $result->fetch_assoc()) {
                $image = $row['image']; // Keep the existing image
            }
        }

        

        // Update Order table if order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("UPDATE `order` o
                          INNER JOIN item i ON i.orderID = o.orderID
                          INNER JOIN accessory a ON a.itemID = i.itemID
                          SET o.purchaseDate = '$purchaseDate', o.purchaseCost = '$purchaseCost', o.supplierID = $supplierID
                          WHERE a.accessoryID = $accessoryID");
        }

        // Update Warranty table if warranty details are provided
        if (!empty($warrantyStartDate) && !empty($warrantyEndDate)) {
            $conn->query("UPDATE warranty w
                          INNER JOIN accessory a ON a.warrantyID = w.warrantyID
                          SET w.startDate = '$warrantyStartDate', w.endDate = '$warrantyEndDate'
                          WHERE a.accessoryID = $accessoryID");
        }

        
        $warrantyID = !empty($warrantyID) ? $warrantyID : 'NULL';
        $orderID = !empty($orderID) ? $orderID : 'NULL';

        // Update Item table
        $conn->query("UPDATE item i
                      INNER JOIN accessory a ON a.itemID = i.itemID
                      SET i.name = '$accessoryName', i.image = '$image', i.notes = '$notes', i.categoryID = $categoryID,
                          i.manufacturerID = $manufacturerID, i.statusID = $statusID, i.officeID = $officeID
                      WHERE a.accessoryID = $accessoryID");

        // Update Asset table
        $conn->query("UPDATE accessory SET modelNo = '$modelNo', quantity = $quantity WHERE accessoryID = $accessoryID");

        header("Location: accessories.php");
        exit;
    }

    if (isset($_POST["addComponentButton"])) {
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        $warrantyStartDate = $_POST['warrantyStartDate'] ?? '';
        $warrantyEndDate = $_POST['warrantyEndDate'] ?? '';
        $componentName = $_POST['componentName'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $modelNo = $_POST['modelNo'] ?? '';
        $serial = $_POST['serial'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $statusID = $_POST['status'] ?? '';
        
        $orderID = '';
        $warrantyID = '';


        // Save image to the folder
        $imagePath = 'images/' . $image;
        if ($image) {
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }


        // Insert into Order table if all order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("INSERT INTO `order` (purchaseDate, purchaseCost, supplierID) VALUES ('$purchaseDate', '$purchaseCost', $supplierID)");
            $orderID = $conn->insert_id;
        } else {
            echo "Order details are incomplete.";
        }

        // Insert into Warranty table if all warranty details are provided
        if (!empty($warrantyStartDate) && !empty($warrantyEndDate)) {
            $conn->query("INSERT INTO warranty (startDate, endDate) VALUES ('$warrantyStartDate', '$warrantyEndDate')");
            $warrantyID = $conn->insert_id;
        } else {
            echo "Warranty details are incomplete.";
        }


        $warrantyID = !empty($warrantyID) ? $warrantyID : 'NULL';
        $orderID = !empty($orderID) ? $orderID : 'NULL';


        // Insert into Item table
        $conn->query("INSERT INTO item (`name`, `image`, notes, categoryID, manufacturerID, orderID, statusID, officeID)
                      VALUES ('$componentName', '$image', '$notes', $categoryID, $manufacturerID, $orderID, $statusID, $officeID)");
        $itemID = $conn->insert_id;

        // Insert into Asset table
        $conn->query("INSERT INTO component (`serial`,modelNo, quantity, itemID, warrantyID)
                      VALUES ('$serial', '$modelNo', '$quantity', $itemID, $warrantyID)");

        if ($conn->affected_rows > 0) {
            header("Location: components.php");
            exit;
        } else {
            echo "Error adding component: " . $conn->error;
        }
    }

    if (isset($_POST["updateComponentButton"])) {
        $componentID = $_POST['componentID'] ?? '';
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        $warrantyStartDate = $_POST['warrantyStartDate'] ?? '';
        $warrantyEndDate = $_POST['warrantyEndDate'] ?? '';
        $componentName = $_POST['componentName'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $serial = $_POST['serial'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $modelNo = $_POST['modelNo'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $statusID = $_POST['status'] ?? '';
        
        $orderID = '';
        $warrantyID = '';
        // Check if a new image was uploaded
        if (!empty($image)) {
            // Save new image to the folder
            $imagePath = 'images/' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            // Fetch current image from the database
            $result = $conn->query("SELECT i.image FROM item i INNER JOIN component c ON i.itemID = c.itemID WHERE c.componentID = $componentID");
            if ($result && $row = $result->fetch_assoc()) {
                $image = $row['image']; // Keep the existing image
            }
        }
        

        // Update Order table if order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("UPDATE `order` o
                          INNER JOIN item i ON i.orderID = o.orderID
                          INNER JOIN component c ON c.itemID = i.itemID
                          SET o.purchaseDate = '$purchaseDate', o.purchaseCost = '$purchaseCost', o.supplierID = $supplierID
                          WHERE c.componentID = $componentID");
        }

        // Update Warranty table if warranty details are provided
        if (!empty($warrantyStartDate) && !empty($warrantyEndDate)) {
            $conn->query("UPDATE warranty w
                          INNER JOIN component c ON c.warrantyID = w.warrantyID
                          SET w.startDate = '$warrantyStartDate', w.endDate = '$warrantyEndDate'
                          WHERE c.componentID = $componentID");
        }

        
        $warrantyID = !empty($warrantyID) ? $warrantyID : 'NULL';
        $orderID = !empty($orderID) ? $orderID : 'NULL';

        // Update Item table
        $conn->query("UPDATE item i
                      INNER JOIN component c ON c.itemID = i.itemID
                      SET i.name = '$componentName', i.image = '$image', i.notes = '$notes', i.categoryID = $categoryID,
                          i.manufacturerID = $manufacturerID, i.statusID = $statusID, i.officeID = $officeID
                      WHERE c.componentID = $componentID");

        // Update Asset table
        $conn->query("UPDATE component SET modelNo = '$modelNo',`serial` = '$serial', quantity = $quantity WHERE componentID = $componentID");

        header("Location: components.php");
        exit;
    }

    if (isset($_POST["addConsumableButton"])) {
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        
        $consumableName = $_POST['consumableName'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $modelNo = $_POST['modelNo'] ?? '';
        
        $quantity = $_POST['quantity'] ?? '';
        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $statusID = $_POST['status'] ?? '';
        
        $orderID = '';
        $warrantyID = '';


        // Save image to the folder
        $imagePath = 'images/' . $image;
        if ($image) {
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }


        // Insert into Order table if all order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("INSERT INTO `order` (purchaseDate, purchaseCost, supplierID) VALUES ('$purchaseDate', '$purchaseCost', $supplierID)");
            $orderID = $conn->insert_id;
        } else {
            echo "Order details are incomplete.";
        }

        


        
        $orderID = !empty($orderID) ? $orderID : 'NULL';


        // Insert into Item table
        $conn->query("INSERT INTO item (`name`, `image`, notes, categoryID, manufacturerID, orderID, statusID, officeID)
                      VALUES ('$consumableName', '$image', '$notes', $categoryID, $manufacturerID, $orderID, $statusID, $officeID)");
        $itemID = $conn->insert_id;

        // Insert into Asset table
        $conn->query("INSERT INTO consumable (modelNo, quantity, itemID)
                      VALUES ('$modelNo', '$quantity', $itemID)");

        if ($conn->affected_rows > 0) {
            header("Location: consumables.php");
            exit;
        } else {
            echo "Error adding consumable " . $conn->error;
        }
    }


    if (isset($_POST["updateConsumableButton"])) {
        $consumableID = $_POST['consumableID'] ?? '';
        $purchaseDate = $_POST['purchaseDate'] ?? '';
        $purchaseCost = $_POST['purchaseCost'] ?? '';
        $supplierID = !empty($_POST['supplier']) ? $_POST['supplier'] : 'NULL';
        $consumableName = $_POST['consumableName'] ?? '';
        $image = $_FILES['image']['name'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $modelNo = $_POST['modelNo'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $categoryID = !empty($_POST['category']) ? $_POST['category'] : 'NULL';
        $manufacturerID = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : 'NULL';
        $officeID = !empty($_POST['office']) ? $_POST['office'] : 'NULL';
        $statusID = $_POST['status'] ?? '';
        
        $orderID = '';
        $warrantyID = '';
        // Check if a new image was uploaded
        if (!empty($image)) {
            // Save new image to the folder
            $imagePath = 'images/' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            // Fetch current image from the database
            $result = $conn->query("SELECT i.image FROM item i INNER JOIN consumable c ON i.itemID = c.itemID WHERE c.consumableID = $consumableID");
            if ($result && $row = $result->fetch_assoc()) {
                $image = $row['image']; // Keep the existing image
            }
        }
        

        // Update Order table if order details are provided
        if (!empty($purchaseDate) && !empty($purchaseCost) && !empty($supplierID)) {
            $conn->query("UPDATE `order` o
                          INNER JOIN item i ON i.orderID = o.orderID
                          INNER JOIN consumable c ON c.itemID = i.itemID
                          SET o.purchaseDate = '$purchaseDate', o.purchaseCost = '$purchaseCost', o.supplierID = $supplierID
                          WHERE c.consumableID = $consumableID");
        }

        

        $orderID = !empty($orderID) ? $orderID : 'NULL';

        // Update Item table
        $conn->query("UPDATE item i
                      INNER JOIN consumable c ON c.itemID = i.itemID
                      SET i.name = '$consumableName', i.image = '$image', i.notes = '$notes', i.categoryID = $categoryID,
                          i.manufacturerID = $manufacturerID, i.statusID = $statusID, i.officeID = $officeID
                      WHERE c.consumableID = $consumableID");

        // Update Asset table
        $conn->query("UPDATE consumable SET modelNo = '$modelNo', quantity = $quantity WHERE consumableID = $consumableID");

        header("Location: consumables.php");
        exit;
    }


}
