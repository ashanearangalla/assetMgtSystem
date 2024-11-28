<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Assets</title>
    <link rel="stylesheet" href="stylesheetasset.css">
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