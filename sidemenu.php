

    <div class="sidebar">
        <div class="logo-details">

            <div class="logo_name"></div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list-vertical">

            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="assets.php">
                    <i class='bx bx-laptop'></i>
                    <span class="links_name">Assets</span>
                </a>
                <span class="tooltip">Assets</span>
            </li>
            <li>
                <a href="licenses.php">
                    <i class='bx bx-window-alt'></i>
                    <span class="links_name">Licenses</span>
                </a>
                <span class="tooltip">Licenses</span>
            </li>
            <li>
                <a href="accessories.php">
                    <i class='bx bxs-keyboard'></i>
                    <span class="links_name">Accessories</span>
                </a>
                <span class="tooltip">Accessories</span>
            </li>
            <li>
                <a href="components.php">
                <i class="fa-solid fa-hard-drive"></i>
                    <span class="links_name">Components</span>
                </a>
                <span class="tooltip">Components</span>
            </li>
            <li>
                <a href="consumables.php">
                <i class='bx bxs-droplet'></i>
                    <span class="links_name">Consumable</span>
                </a>
                <span class="tooltip">Consumable</span>
            </li>
            <li>
                <a href="manageUsers.php">
                <i class='bx bxs-user'></i>
                    <span class="links_name">Saved</span>
                </a>
                <span class="tooltip">People</span>
            </li>
            <li>
                <a href="maintenance.php">
                <i class="fa-solid fa-wrench"></i>
                    <span class="links_name">Maintenance</span>
                </a>
                <span class="tooltip">Maintenance</span>
            </li>
            <li>
                <a href="audit.php">
                <i class="fa-regular fa-clipboard"></i>
                    <span class="links_name">Audit</span>
                </a>
                <span class="tooltip">Audit</span>
            </li>
            <li>
                <a href="activityLog.php">
                <i class="fa-solid fa-pen-to-square"></i>
                    <span class="links_name">Activity Log</span>
                </a>
                <span class="tooltip">Activity Log</span>
            </li>
            <li class="profile">
                <div class="profile-details">
                    <!-- <img src="profile.jpg" alt="profileImg"> -->
                    <div class="name_job">
                        <div class="name"><?php echo $_SESSION["user"]["fname"] . " " . $_SESSION["user"]["lname"]?></div>
                        <div class="job"><?php echo $_SESSION["user"]["role"] ?></div>
                    </div>
                </div>
                
                
            </li>
        </ul>
    </div>
    <section class="home-section">

        <div class="header-bar">
            <div class="logo">
                <h1>Snipe IT</h1>
            </div>
            <div class="middle-section">
                <ul class="nav-list-horizontal">

                    
                   
                </ul>




            </div>
            <div class="settings">
                <ul class="nav-list-horizontal">

                    
                    <li>
                        <a href="categories.php">
                        <i class='bx bxs-category-alt'></i>

                        </a>
                        <span class="tooltip">Categories</span>

                    </li>
                    <li>
                        <a href="manufacturers.php">
                        <i class='bx bxs-factory'></i>
                       

                        </a>
                        <span class="tooltip">Manufacturers</span>

                    </li>
                    <li>
                        <a href="offices.php">
                        <i class='bx bx-buildings'></i>

                        </a>
                        <span class="tooltip">Offices</span>

                    </li>
                    <li>
                        <a href="suppliers.php">
                        <i class='bx bxs-truck'></i>

                        </a>
                        <span class="tooltip">Suppliers</span>

                    </li>
                    <li>
                    
                        <a href="account.php">
                        
                        <span style="min-width: 90px;" class="links_name"><?php echo $_SESSION["user"]["fname"] . " " . $_SESSION["user"]["lname"]?></span>
                        
                        </a>

                    </li>
                    <li>
                        <a href="logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>

                        </a>
                        <span class="tooltip">Logout</span>

                    </li>

                    

                </ul>



            </div>
        </div>
    <script src="hamburger.js"></script>
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
</body>

</html>