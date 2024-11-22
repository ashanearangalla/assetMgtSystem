<?php
// Include the database connection file
include('connection.php');

// Fetch asset data from the database
$sql = "SELECT 
            a.AssetID, 
            a.Name AS AssetName, 
            a.Description, 
            c.CategoryName, 
            l.LocationName, 
            a.PurchaseDate, 
            a.WarrantyExpiryDate, 
            a.Cost 
        FROM asset a
        JOIN category c ON a.CategoryID = c.CategoryID
        JOIN location l ON a.LocationID = l.LocationID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assets List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="stylesheet.css">
  <script src="https://cdn.tailwindcss.com"></script>


</head>
<body class="bg-gray-100">
  <div class="min-h-screen grid grid-cols-12">
    <!-- Sidebar -->
    <aside id="sidemenu" class="col-span-1 bg-gray-800 text-white flex flex-col p-6">
      <div class="flex justify-between items-center mb-6"><h2 id="sidemenu-header" class="text-xl font-bold">Inventory Menu </h2> <i id="hamburger" class="fa-solid fa-bars float-right"></i></div>
      <ul id="sidemenu-list" class="space-y-4">
        <li><a href="#dashboard" class="block py-2 px-3 rounded-md hover:bg-gray-700">Dashboard</a></li>
        <li><a href="#assets" class="block py-2 px-3 rounded-md hover:bg-gray-700">Assets</a></li>
        <li><a href="#suppliers" class="block py-2 px-3 rounded-md hover:bg-gray-700">Suppliers</a></li>
        <li><a href="#locations" class="block py-2 px-3 rounded-md hover:bg-gray-700">Locations</a></li>
        <li><a href="#maintenance" class="block py-2 px-3 rounded-md hover:bg-gray-700">Maintenance</a></li>
        <li><a href="#audit" class="block py-2 px-3 rounded-md hover:bg-gray-700">Audit</a></li>
        <li><a href="#logout" class="block py-2 px-3 rounded-md hover:bg-gray-700">Logout</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="col-span-11 p-6">
      <header class="mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Assets List</h1>
      </header>

      <!-- Table Container -->
      <div class="bg-white shadow-md rounded-lg p-4 overflow-x-auto">
        <table class="min-w-full border-collapse">
          <thead class="bg-gray-800 text-white">
            <tr>
              <th class="py-3 px-4 text-left">#</th>
              <th class="py-3 px-4 text-left">Asset Name</th>
              <th class="py-3 px-4 text-left">Description</th>
              <th class="py-3 px-4 text-left">Category</th>
              <th class="py-3 px-4 text-left">Location</th>
              <th class="py-3 px-4 text-left">Purchase Date</th>
              <th class="py-3 px-4 text-left">Warranty Expiry</th>
              <th class="py-3 px-4 text-left">Cost</th>
            </tr>
          </thead>
          <tbody class="text-gray-700">
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-100">
                  <td class="py-3 px-4"><?php echo $row['AssetID']; ?></td>
                  <td class="py-3 px-4"><?php echo $row['AssetName']; ?></td>
                  <td class="py-3 px-4"><?php echo $row['Description']; ?></td>
                  <td class="py-3 px-4"><?php echo $row['CategoryName']; ?></td>
                  <td class="py-3 px-4"><?php echo $row['LocationName']; ?></td>
                  <td class="py-3 px-4"><?php echo $row['PurchaseDate']; ?></td>
                  <td class="py-3 px-4"><?php echo $row['WarrantyExpiryDate']; ?></td>
                  <td class="py-3 px-4"><?php echo $row['Cost']; ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="py-3 px-4 text-center text-gray-500">No assets found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
  <script src="hamburger.js"></script>
</body>
</html>
