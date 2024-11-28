<?php
// Include the database connection file
include('connection.php');

// Fetch asset data from the database
$sql = "SELECT a.assetID, 
i.itemID,
i.name, 
i.image, 
i.notes, 
cat.name AS categoryName, 
m.name AS manufacturerName, 
o.name AS officeName, 
a.assetTag, 
a.serial, 
a.modelName, 
a.nextAuditDate, 
w.startDate AS warrantyStartDate, 
w.endDate AS warrantyEndDate, 
ord.purchaseDate, 
ord.purchaseCost, 
su.name AS supplierName, 
st.type AS `type`
FROM asset a
INNER JOIN item i ON i.itemID = a.itemID
LEFT JOIN `status` st ON i.statusID = st.statusID
LEFT JOIN `order` ord ON i.orderID = ord.orderID
LEFT JOIN `supplier` su ON ord.supplierID = su.supplierID
LEFT JOIN category cat ON i.categoryID = cat.categoryID
LEFT JOIN manufacturer m ON i.manufacturerID = m.manufacturerID
LEFT JOIN office o ON i.officeID = o.officeID
LEFT JOIN warranty w ON a.warrantyID = w.warrantyID;";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets List</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="stylesheetassetnew.css">
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
            <h1>Assets List</h1>
            <a href="addAsset.php"><button class="primary-btn">Create New</button></a>
        </div>
        <!-- Table Container -->
        <div class="table-container">
            <table id="assetTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Notes</th>
                        <th>Category</th>
                        <th>Manufacturer</th>
                        <th>Office</th>
                        <th>Asset Tag</th>
                        <th>Serial</th>
                        <th>Model Name</th>
                        <th>Next Audit Date</th>
                        <th>Warranty Start</th>
                        <th>Warranty End</th>
                        <th>Purchase Date</th>
                        <th>Purchase Cost</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $index = 1; // Initialize index for row numbering
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $index++ . "</td>"; // Row number
                            echo "<td>" . $row["assetID"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td><img src='images/" . $row["image"] . "' alt='" . $row["name"] . "' width='50' height='50'></td>";
                            echo "<td>" . $row["notes"] . "</td>";
                            echo "<td>" . $row["categoryName"] . "</td>";
                            echo "<td>" . $row["manufacturerName"] . "</td>";
                            echo "<td>" . $row["officeName"] . "</td>";
                            echo "<td>" . $row["assetTag"] . "</td>";
                            echo "<td>" . $row["serial"] . "</td>";
                            echo "<td>" . $row["modelName"] . "</td>";
                            echo "<td>" . $row["nextAuditDate"] . "</td>";
                            echo "<td>" . $row["warrantyStartDate"] . "</td>";
                            echo "<td>" . $row["warrantyEndDate"] . "</td>";
                            echo "<td>" . $row["purchaseDate"] . "</td>";
                            echo "<td>" . $row["purchaseCost"] . "</td>";
                            echo "<td>" . $row["supplierName"] . "</td>";
                            echo "<td>" . $row["type"] . "</td>";
                            echo "<td>
                                <a href='addAsset.php?assetID=" . $row['assetID'] . "' class='btn btn-primary'>Update</a>
                               <form action='db_context.php' method='post' style='display:inline-block;'>
                              <input type='hidden' name='itemID' value='" . $row['itemID'] . "'>
                              <button type='submit' name='deleteAssetButton' 
                              class='btn btn-danger' style='background-color:red;' 
                              onclick='return confirm(\"Are you sure you want to delete this asset?\")'>
                              Delete</button>
                            </form>
                               
                              </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='19'>No records found</td></tr>";
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
                        <th>Office</th>
                        <th>Asset Tag</th>
                        <th>Serial</th>
                        <th>Model Name</th>
                        <th>Next Audit Date</th>
                        <th>Warranty Start</th>
                        <th>Warranty End</th>
                        <th>Purchase Date</th>
                        <th>Purchase Cost</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Action</th>
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
            $('#assetTable').DataTable({
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
