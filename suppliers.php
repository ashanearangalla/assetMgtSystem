<?php
// Include the database connection file
include('connection.php');

// Fetch supplier data from the database
$sql = "SELECT supplierID, name, address, contactName, email, phone FROM supplier;";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers List</title>
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
            <h1>Suppliers List</h1>
            <?php if ($_SESSION["user"]["role"] !== 'Employee') : ?>
                <a href="addSupplier.php"><button class="primary-btn">Create New</button></a>
            <?php endif; ?>
            
        </div>
        <!-- Table Container -->
        <div class="table-container">
            <table id="supplierTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Contact Name</th>
                        <th>Email</th>
                        <th>Phone</th>
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
                            echo "<td>" . $row["supplierID"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["address"] . "</td>";
                            echo "<td>" . $row["contactName"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["phone"] . "</td>";
                            if ($_SESSION["user"]["role"] !== 'Employee') :
                            echo "<td>
                                <a href='addSupplier.php?supplierID=" . $row['supplierID'] . "' class='btn btn-primary'>Update</a>
                                <form action='db_context.php' method='post' style='display:inline-block;'>
                                    <input type='hidden' name='supplierID' value='" . $row['supplierID'] . "'>
                                    <button type='submit' name='deleteSupplierButton' class='btn btn-danger' style='background-color:red;' 
                                    onclick='return confirm(\"Are you sure you want to delete this supplier?\")'>Delete</button>
                                </form>
                            </td>";
                            endif;
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Contact Name</th>
                        <th>Email</th>
                        <th>Phone</th>
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
            $('#supplierTable').DataTable({
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
