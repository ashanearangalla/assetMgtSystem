<?php
// Include the database connection file
include('connection.php');

// Fetch asset assignment data with joined tables
$sql = "SELECT 
    a.assignmentID,
    a.assignmentDate,
    a.returnDate,
    a.expCheckinDate,
    a.status,
    a.checkinNotes,
    a.checkoutNotes,
    a.quantity,
    a.checkedOutDate,
    i.name AS itemName,
    CONCAT(u.fname, ' ', u.lname) AS userName,
    o.name
FROM 
    AssetAssignment a
LEFT JOIN 
    Item i ON a.itemID = i.itemID
LEFT JOIN 
    User u ON a.userID = u.userID
LEFT JOIN 
    Office o ON a.officeID = o.officeID;";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log</title>
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
            <h1>Activity Log</h1>
        </div>
        <!-- Table Container -->
        <div class="table-container">
            <table id="activityLogTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Assignment ID</th>
                        <th>Item Name</th>
                        <th>Checked Out To</th>
                        <th>Office</th>
                        <th>Assignment Date</th>
                        <th>Expected Check-in Date</th>
                        
                        <th>Return Date</th>
                        <th>Quantity</th>
                        
                        <th>Check-in Notes</th>
                        <th>Check-out Notes</th>
                        <th>Checked-out Date Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $index = 1; // Initialize index for row numbering
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $index++ . "</td>"; // Row number
                            echo "<td>" . $row["assignmentID"] . "</td>";
                            echo "<td>" . htmlspecialchars($row["itemName"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["userName"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["assignmentDate"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["expCheckinDate"]) . "</td>";
                            
                            echo "<td>" . htmlspecialchars($row["returnDate"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                            
                            echo "<td>" . htmlspecialchars($row["checkinNotes"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["checkoutNotes"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["checkedOutDate"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='13'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Assignment ID</th>
                        <th>Item Name</th>
                        <th>Checked Out To</th>
                        <th>Office</th>
                        <th>Assignment Date</th>
                        <th>Expected Check-in Date</th>
                        
                        <th>Return Date</th>
                        <th>Quantity</th>
                        
                        <th>Check-in Notes</th>
                        <th>Check-out Notes</th>
                        <th>Checked-out Date Time</th>
                        <th>Status</th>
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
            $('#activityLogTable').DataTable({
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
