<?php
// Include the database connection file
include('connection.php');

// Fetch license data from the database
$sql = "SELECT l.licenseID, 
i.itemID, 
i.name, 
i.image, 
i.notes, 
c.name AS categoryName, 
m.name AS manufacturerName, 
st.type AS `type`,  
o.name AS officeName, 
l.productKey, 
l.seats, 
l.available,
l.licensedToName, 
l.licensedToEmail, 
l.expDate, 
ord.purchaseDate, 
ord.purchaseCost, 
su.name AS supplierName
FROM license l
LEFT JOIN item i ON i.itemID = l.itemID
LEFT JOIN `status` st ON i.statusID = st.statusID
LEFT JOIN `order` ord ON i.orderID = ord.orderID
LEFT JOIN `supplier` su ON ord.supplierID = su.supplierID
LEFT JOIN category c ON i.categoryID = c.categoryID
LEFT JOIN manufacturer m ON i.manufacturerID = m.manufacturerID
LEFT JOIN office o ON i.officeID = o.officeID;";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License List</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="stylesheetlast.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
</head>

<body>

    <!-- Sidebar -->
    <?php include('sidemenu.php'); ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-header">
            <h1>Licenses List</h1>
            <?php if ($_SESSION["user"]["role"] !== 'Employee') : ?>
                <a href="addLicense.php"><button class="primary-btn">Create New</button></a>
            <?php endif; ?>
            
        </div>
        <!-- Table Container -->
        <div class="table-container">
            <table id="licenseTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Notes</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Status</th>
                        <th>Office</th>
                        <th>Product Key</th>
                        <th>Seats</th>
                        <th>Available</th>
                        <th>Licensed To (Name)</th>
                        <th>Licensed To (Email)</th>
                        <th>Expiry Date</th>
                        <th>Purchase Date</th>
                        <th>Purchase Cost</th>
                        <th>Supplier</th>
                        <th>In/Out</th>

                        <?php if ($_SESSION["user"]["role"] !== 'Employee') : ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $index = 1; // Initialize index for row numbering
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $index++ . "</td>"; // Row number
                            echo "<td>" . $row["licenseID"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td><img src='images/" . $row["image"] . "' alt='" . $row["name"] . "' width='50' height='50'></td>";
                            echo "<td>" . $row["notes"] . "</td>";
                            echo "<td>" . $row["categoryName"] . "</td>";
                            echo "<td>" . $row["manufacturerName"] . "</td>";
                            echo "<td>" . $row["type"] . "</td>";
                            echo "<td>" . $row["officeName"] . "</td>";
                            echo "<td>" . $row["productKey"] . "</td>";
                            echo "<td>" . $row["seats"] . "</td>";
                            echo "<td>" . $row["available"] . "</td>";
                            echo "<td>" . $row["licensedToName"] . "</td>";
                            echo "<td>" . $row["licensedToEmail"] . "</td>";
                            echo "<td>" . $row["expDate"] . "</td>";
                            echo "<td>" . $row["purchaseDate"] . "</td>";
                            echo "<td>" . $row["purchaseCost"] . "</td>";
                            echo "<td>" . $row["supplierName"] . "</td>";
                            echo "<td>";
                            if ($row['type'] === 'Deployed') {
                                echo "<a href='checkinAsset.php?itemID=" . $row['itemID'] . "' class='btn btn-success'>Checkin</a>";
                            } elseif ($row['type'] === 'Ready to Deploy') {
                                if ($row['available'] > 0) {
                                    echo "<a href='checkoutLicense.php?licenseID=" . $row['licenseID'] . "' class='btn btn-primary'>Checkout</a>";
                                } else {
                                    echo "<button class='btn btn-primary' disabled>Checkout</button>";
                                }
                            } else {
                                echo "N/A"; // For other statuses
                            }
                            echo "</td>";
                            if ($_SESSION["user"]["role"] !== 'Employee') :
                            echo "<td>
                                <a href='addLicense.php?licenseID=" . $row['licenseID'] . "' class='btn btn-primary'>Update</a>
                                <form action='db_context.php' method='post' style='display:inline-block;'>
                              <input type='hidden' name='itemID' value='" . $row['itemID'] . "'>
                              <button type='submit' name='deleteLicenseButton' 
                              class='btn btn-danger' style='background-color:red;' 
                              onclick='return confirm(\"Are you sure you want to delete this license?\")'>
                              Delete</button>
                            </form>
                              </td>";
                            endif;
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='18'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                    <th>#</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Notes</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Status</th>
                        <th>Office</th>
                        <th>Product Key</th>
                        <th>Seats</th>
                        <th>Available</th>
                        <th>Licensed To (Name)</th>
                        <th>Licensed To (Email)</th>
                        <th>Expiry Date</th>
                        <th>Purchase Date</th>
                        <th>Purchase Cost</th>
                        <th>Supplier</th>
                        <th>In/Out</th>

                        <?php if ($_SESSION["user"]["role"] !== 'Employee') : ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>

    <script src="hamburger.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#licenseTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "pageLength": 15,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pagingType": "simple_numbers",
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print']
            });
        });
    </script>
</body>

</html>