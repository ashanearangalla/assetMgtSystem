<?php
// Database connection

include('connection.php');

// Get itemID from GET parameter
$itemID = isset($_GET['itemID']) ? intval($_GET['itemID']) : null;
if (!$itemID) {
    echo "Invalid Item ID.";
    exit;
}

// Fetch item name based on itemID
$stmt = $conn->prepare("SELECT name FROM item WHERE itemID = ?");
$stmt->bind_param("i", $itemID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Item not found.";
    exit;
}
$item = $result->fetch_assoc();
$itemName = $item['name'];

// Fetch users, statuses, and offices for dropdowns
$users = $conn->query("SELECT userID, fname, lname FROM User");
$statuses = $conn->query("SELECT statusID, type FROM Status");
$offices = $conn->query("SELECT officeID, name FROM Office");
?>


<!DOCTYPE html>
<!-- Created by CodingLab |www.yuube.com/CodingLabYT-->
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
            <h1>Checkin Asset</h1>
 
        </div>

        <div class="form-container">
            <div class="form-body">
                <form id="checkoutForm" action="db_context.php" method="POST" onsubmit="return validateCheckoutForm()">
                    <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($itemID); ?>">

                    <div class="form-element">
                        <label for="itemName">Item Name:</label>
                        <input type="text" id="itemName" name="itemName" value="<?php echo htmlspecialchars($itemName); ?>" readonly>
                    </div>

                    <div class="form-element">
                        <label for="returnDate">Return Date:</label>
                        <input type="date" id="returnDate" name="returnDate">
                    </div>

                    

                    

                    <div class="form-element">
                        <label for="office">Office:</label>
                        <select id="office" name="office" >
                            <option value="" selected hidden>Select Office</option>
                            <?php while ($office = $offices->fetch_assoc()) { ?>
                                <option value="<?php echo $office['officeID']; ?>"><?php echo htmlspecialchars($office['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-element">
                        <label for="checkinNotes">Checkin Notes:</label>
                        <textarea id="checkinNotes" name="checkinNotes" placeholder="Enter any notes"></textarea>
                    </div>

                    <div style="text-align: center;" id="error-box" class="error-box"></div>

                    <div class="form-footer">
                        
                        <button class="primary-button" type="submit" name="addCheckinButton">Checkin Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function validateCheckoutForm() {
            const returnDate = document.getElementById("returnDate").value.trim();
            
            
            const errorBox = document.getElementById("error-box");

            errorBox.innerHTML = "";

            if (!returnDate) {
                errorBox.innerHTML = "Return Date is required.";
                return false;
            }


            

            return true;
        }
    </script>
</body>

</html>