<?php
include('connection.php');
?>

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
                    <i class='bx bx-folder'></i>
                    <span class="links_name">Components</span>
                </a>
                <span class="tooltip">Components</span>
            </li>
            <li>
                <a href="consumables.php">
                    <i class='bx bx-cart-alt'></i>
                    <span class="links_name">Consumable</span>
                </a>
                <span class="tooltip">Consumable</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-heart'></i>
                    <span class="links_name">Saved</span>
                </a>
                <span class="tooltip">Saved</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-cog'></i>
                    <span class="links_name">Setting</span>
                </a>
                <span class="tooltip">Setting</span>
            </li>
            <li class="profile">
                <div class="profile-details">
                    <!-- <img src="profile.jpg" alt="profileImg"> -->
                    <div class="name_job">
                        <div class="name">Saman Perera</div>
                        <div class="job">Web designer</div>
                    </div>
                </div>
                <i class='bx bx-log-out' id="log_out"></i>
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

                    <li>
                        <a href="#">
                            <i class='bx bx-laptop'></i>
                        </a>
                        <span class="tooltip">Order</span>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bx-window-alt'></i>
                        </a>
                        <span class="tooltip">License</span>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bxs-keyboard'></i>
                        </a>
                        <span class="tooltip">Accessories</span>
                    </li>
                    <li>
                        <div class='search'>
                            <input type="text" placeholder="Insert Asset Tag" />
                            <i class='bx bx-search'></i>
                        </div>
                    </li>
                </ul>




            </div>
            <div class="settings">
                <ul class="nav-list-horizontal">

                    <li>
                        <a href="#">
                            <i id="profilepic" class='bx bx-smile'></i>
                            <span class="links_name">Saved</span>
                        </a>

                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bx-cog'></i>

                        </a>

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