<?php
// Database connection
include('connection.php');

// Query to count the items by status
$query = "SELECT s.type, COUNT(i.itemID) as count
FROM item i
JOIN status s ON i.statusID = s.statusID
GROUP BY s.type;
";
$result = mysqli_query($conn, $query);

$statuses = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $statuses[] = $row['type']; // Store statuses (labels)
    $counts[] = $row['count'];    // Store the count of each status
}

// Convert PHP arrays into JSON format for Chart.js
$statuses_json = json_encode($statuses);
$counts_json = json_encode($counts);



// Fetch counts from each table
$assets_count = $conn->query("SELECT COUNT(*) as count FROM asset")->fetch_assoc()['count'];
$licenses_count = $conn->query("SELECT COUNT(*) as count FROM license")->fetch_assoc()['count'];
$accessories_count = $conn->query("SELECT COUNT(*) as count FROM accessory")->fetch_assoc()['count'];
$consumables_count = $conn->query("SELECT COUNT(*) as count FROM consumable")->fetch_assoc()['count'];
$components_count = $conn->query("SELECT COUNT(*) as count FROM component")->fetch_assoc()['count'];


//asset assignment
$query = "SELECT o.name, i.itemID, aa.assignmentID, u.fname AS assignedTo, aa.assignmentDate
          FROM assetassignment aa
          LEFT JOIN item i ON aa.itemID = i.itemID
          LEFT JOIN office o ON i.officeID = o.officeID
          LEFT JOIN user u ON aa.userID = u.userID
          ORDER BY o.name";

$result = $conn->query($query);

?>



<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Assets</title>
    <link rel="stylesheet" href="mystylenew.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
<?php

include('sidemenu.php');
?>

    
    <section class="home-section">

    

        <main class="main-content">
            <div class="main-header">
                <h1>Dashboard</h1>
                
            </div>

            <div class="dashboard-summary">
    <!-- Assets Card -->
    <div class="card" style="background-color: #4CAF50;">  <!-- Green for Assets -->
        <div class="card-body">
            <div>
                <h1><?php echo $assets_count; ?></h1>  <!-- Display the asset count -->
                <h3>Assets</h3>
            </div>
            <div>
                <a href="assets.php">
                    <i class='bx bx-laptop'></i>
                </a>
            </div>
        </div>
        <div class="card-footer">
            <a href="assets.php" style="color: white; text-decoration: none;">
                View All <i class='bx bx-right-arrow-alt'></i>  <!-- Arrow icon -->
            </a>
        </div>
    </div>
    
    <!-- Licenses Card -->
    <div class="card" style="background-color: #FF5722;">  <!-- Orange for Licenses -->
        <div class="card-body">
            <div>
                <h1><?php echo $licenses_count; ?></h1>  <!-- Display the licenses count -->
                <h3>Licenses</h3>
            </div>
            <div>
                <a href="licenses.php">
                    <i class='bx bx-window-alt'></i>
                </a>
            </div>
        </div>
        <div class="card-footer">
            <a href="licenses.php" style="color: white; text-decoration: none;">
                View All <i class='bx bx-right-arrow-alt'></i>  <!-- Arrow icon -->
            </a>
        </div>
    </div>
    
    <!-- Accessories Card -->
    <div class="card" style="background-color: #2196F3;">  <!-- Blue for Accessories -->
        <div class="card-body">
            <div>
                <h1><?php echo $accessories_count; ?></h1>  <!-- Display the accessories count -->
                <h3>Accessories</h3>
            </div>
            <div>
                <a href="accessories.php">
                    <i class='bx bxs-keyboard'></i>
                </a>
            </div>
        </div>
        <div class="card-footer">
            <a href="accessories.php" style="color: white; text-decoration: none;">
                View All <i class='bx bx-right-arrow-alt'></i>  <!-- Arrow icon -->
            </a>
        </div>
    </div>

    <!-- Consumables Card -->
    <div class="card" style="background-color: #9C27B0;">  <!-- Purple for Consumables -->
        <div class="card-body">
            <div>
                <h1><?php echo $consumables_count; ?></h1>  <!-- Display the consumables count -->
                <h3>Consumables</h3>
            </div>
            <div>
                <a href="consumables.php">
                    <i class='bx bx-cart-alt'></i>
                </a>
            </div>
        </div>
        <div class="card-footer">
            <a href="consumables.php" style="color: white; text-decoration: none;">
                View All <i class='bx bx-right-arrow-alt'></i>  <!-- Arrow icon -->
            </a>
        </div>
    </div>

    <!-- Components Card -->
    <div class="card" style="background-color: #FFC107;">  <!-- Yellow for Components -->
        <div class="card-body">
            <div>
                <h1><?php echo $components_count; ?></h1>  <!-- Display the components count -->
                <h3>Components</h3>
            </div>
            <div>
                <a href="components.php">
                    <i class='bx bx-folder'></i>
                </a>
            </div>
        </div>
        <div class="card-footer">
            <a href="components.php" style="color: white; text-decoration: none;">
                View All <i class='bx bx-right-arrow-alt'></i>  <!-- Arrow icon -->
            </a>
        </div>
    </div>
</div>

            <div class="body1">
                <div class="recent-activity">
                    <div class="main-header">
                        
                        
                    </div>
                    <div class="table-container">
                        <div class="table-header-dashboard">
                        <h3 class="table-heading-dashboard">Recent Activity</h3>
                        </div>
                        <div class="table-summary">
                            <div class="summary-text">Showing 1 to 20 of 35 rows</div>
                            <div class="pagination">
                                <button class="page-btn">Previous</button>
                                <button class="page-btn">1</button>
                                <button class="page-btn">2</button>
                                <button class="page-btn">Next</button>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Asset Name</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Purchase Date</th>
                                    <th>Warranty Expiry</th>
                                    <th>Cost</th>
                                    

                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example Data -->
                                <tr>
                                    <td>1</td>
                                    <td>Office Chair</td>
                                    <td>Ergonomic Chair</td>
                                    <td>Furniture</td>
                                    <td>Main Office</td>
                                    <td>2023-03-01</td>
                                    <td>2025-03-01</td>
                                    <td>$150</td>
                                    
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Laptop</td>
                                    <td>Dell XPS 13</td>
                                    <td>Electronics</td>
                                    <td>IT Room</td>
                                    <td>2022-11-15</td>
                                    <td>2024-11-15</td>
                                    <td>$1200</td>

                                </tr>
                                <!-- More rows can be added dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="asset-type">
                    <h2>Asset by Status Type</h2>
                    <canvas id="statusPieChart" width="300" height="300"></canvas>

                </div>
            </div>
            

        </main>

        <div class="footer">
            <p>This is the footer</p>
        </div>
        </div>

    </section>
    <script>
        let sidebar = document.querySelector(".sidebar");
        let closeBtn = document.querySelector("#btn");


        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            menuBtnChange(); //calling the function(optional)
        });



        // following are the code to change sidebar button(optional)
        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
            } else {
                closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Retrieve the data passed from PHP
    const statuses = <?php echo $statuses_json; ?>;  // Labels
    const counts = <?php echo $counts_json; ?>;      // Data

    // Create the chart
    const ctx = document.getElementById('statusPieChart').getContext('2d');
    const statusPieChart = new Chart(ctx, {
        type: 'pie', // Specifies the type of chart
        data: {
            labels: statuses, // Labels (statuses)
            datasets: [{
                label: 'Item Status',
                data: counts, // Data (counts of each status)
                backgroundColor: [
                    'rgba(0, 0, 139, 0.6)', // Dark blue
                    'rgba(255, 0, 0, 0.6)', // Red
                    'rgba(0, 0, 139, 0.6)', // Dark blue (repeated)
                    'rgba(255, 0, 0, 0.6)'  // Red (repeated)
                ],
                borderColor: [
                    'rgba(0, 0, 139, 1)', // Dark blue
                    'rgba(255, 0, 0, 1)', // Red
                    'rgba(0, 0, 139, 1)', // Dark blue (repeated)
                    'rgba(255, 0, 0, 1)'  // Red (repeated)
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Ensures the chart scales with window size
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
</script>



</body>

</html>