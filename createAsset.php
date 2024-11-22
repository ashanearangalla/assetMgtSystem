<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Assets</title>
    <link rel="stylesheet" href="mystyle.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">

            <div class="logo_name"></div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list-vertical">

            <li>
                <a href="#">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-laptop'></i>
                    <span class="links_name">Assets</span>
                </a>
                <span class="tooltip">Assets</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-window-alt'></i>
                    <span class="links_name">Licenses</span>
                </a>
                <span class="tooltip">Licenses</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-keyboard'></i>
                    <span class="links_name">Accessories</span>
                </a>
                <span class="tooltip">Accessories</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-folder'></i>
                    <span class="links_name">File Manager</span>
                </a>
                <span class="tooltip">Files</span>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-cart-alt'></i>
                    <span class="links_name">Order</span>
                </a>
                <span class="tooltip">Order</span>
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
                    <img src="profile.jpg" alt="profileImg">
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



        <main class="main-content-create">
            <div class="main-header-create">
                <h1>Assets List</h1>
                <button>Create New</button>
            </div>

            <div class="form-container">
                <div class="table-header">
                    
                        <button class="primary-button">Save</button>
                        
                    
                    
                </div>
                <div class="form-body">
                    <form action="#" method="POST">
                        <div class="form-element">
                            <label for="assetName">Asset Name:</label>
                            <input type="text" id="assetName" name="assetName">
                        </div>
                        <div class="form-element">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description"></textarea>
                        </div>
                        <div class="form-element">
                            <label for="category">Category:</label>
                            <input type="text" id="category" name="category">
                        </div>
                        <div class="form-element">
                            <label for="location">Location:</label>
                            <input type="text" id="location" name="location">
                        </div>
                        <div class="form-element">
                            <label for="purchaseDate">Purchase Date:</label>
                            <input type="date" id="purchaseDate" name="purchaseDate">
                        </div>
                        <div class="form-element">
                            <label for="warrantyExpiry">Warranty Expiry:</label>
                            <input type="date" id="warrantyExpiry" name="warrantyExpiry">
                        </div>
                        <div class="form-element">
                            <label for="cost">Cost:</label>
                            <input type="number" id="cost" name="cost" step="0.01">
                            <button id="add-btn">Add</button>
                        </div>
                        <div class="form-element">
                            <label for="checkinCheckout">Checkin/Checkout:</label>
                            <select id="checkinCheckout" name="checkinCheckout">
                                <option value="checkin">Checkin</option>
                                <option value="checkout">Checkout</option>
                            </select>
                        </div>
                        <div class="form-footer">
                            <button class="secondary-button" type="reset">Cancel</button>
                            <button class="primary-button" type="submit">Submit</button>
                            
                        </div>
                    </form>
                </div>

            </div>
        </main>
        </div>

    </section>

    <div class="overlay" id="popup-overlay">
        <div class="popup-box">
            <div class="popup-header">
                <button class="close-btn" id="close-popup">&times;</button>
                <h2>Add Item</h2>

            </div>

           <div class="popup-element">
                <label for="assetName">Asset Name:</label>
                <input type="text" id="assetName" name="assetName">
            </div>
           <div class="popup-element">
                <label for="assetName">Asset Name:</label>
                <input type="text" id="assetName" name="assetName">
            </div>
           <div class="popup-element">
                <label for="assetName">Asset Name:</label>
                <input type="text" id="assetName" name="assetName">
            </div>
            <div class="popup-footer">
                <button class="secondary-button" type="reset">Cancel</button>
                <button class="action-btn" id="confirm-add">Confirm</button>
                
            </div>
            
        </div>
    </div>

    
    
</body>
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
    <script src="script.js"></script>
</html>