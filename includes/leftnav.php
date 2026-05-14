<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<div class="splash active">
    <div class="splash-icon"></div>
</div>

<div class="wrapper">
    <nav id="sidebar" class="sidebar">
        <a href="dashboard-analytics.php" class="sidebar-brand">
    <svg>
        <use xlink:href="#ion-ios-pulse-strong"></use>
    </svg>
  Vehicle Management System
</a>

        <div class="sidebar-content">
            <div class="sidebar-user">
                <img src="dist/img/avatars/avatar.jpg" class="img-fluid rounded-circle mb-2" alt="Linda Miller" />
                
                <div class="fw-bold">
                    <?php echo isset($_SESSION["DISPLAYNAME"]) ? $_SESSION['DISPLAYNAME'] : 'Unknown User'; ?>
                </div>

                <div style="color: #000; font-weight: bold;">
    <?php
              echo "Service No: ". $_SESSION["USER"]."<br>"; 
              echo "Designation: ". $_SESSION["designation"]."<br>"; 
              echo "Division: ". $_SESSION["DIVISION"]."<br>"; 
              echo "Role: ". $_SESSION["TYPE"]."<br>"; 
              echo "Role: ". $_SESSION["divId"]."<br>"; 
                    ?>
</div>

            </div>

            <ul class="sidebar-nav">
    <li class="sidebar-header">
        Main
    </li>

    <!-- Dropdown Menu -->
    <li class="sidebar-item">
        <a href="#Menu1"
           data-bs-toggle="collapse"
           class="sidebar-link collapsed">
           
            <i class="align-middle me-2 fas fa-fw fa-home"></i>
            <span class="align-middle">VEHICLE</span>
            <i class="align-middle float-end fas fa-chevron-down"></i>
        </a>

        <ul id="Menu1" class="sidebar-dropdown list-unstyled collapse">

            
            <li class="sidebar-item">
                <a href="register.php"
                   class="sidebar-link"
                   onclick="highlightSection(this)">
                    Vehicle Registration
                </a>
            </li>

            
                <li class="sidebar-item">
                    <a href="approve.php"
                       class="sidebar-link"
                       onclick="highlightSection(this)">
                       Assign Vehicle
                    </a>
                </li>
           
                <li class="sidebar-item">
                    <a href="access.php"
                       class="sidebar-link"
                       onclick="highlightSection(this)">
                        Vehicle Licence
                    </a>
                </li>

                 <li class="sidebar-item">
                    <a href="addnewjob.php"
                       class="sidebar-link"
                       onclick="highlightSection(this)">
                        Add New Job
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="import_insurence.php"
                       class="sidebar-link"
                       onclick="highlightSection(this)">
                       Import Insurence
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="import_revenuelicence.php"
                       class="sidebar-link"
                       onclick="highlightSection(this)">
                       Import Revenue Licenece
                    </a>
                </li>

                



        </ul>
    </li>

    <li class="sidebar-item">
        <a href="#Menu2"
           data-bs-toggle="collapse"
           class="sidebar-link collapsed">
           
            <i class="align-middle me-2 fas fa-fw fa-home"></i>
            <span class="align-middle">SYSTEM SETTINGS</span>
            <i class="align-middle float-end fas fa-chevron-down"></i>
        </a>

        <ul id="Menu2" class="sidebar-dropdown list-unstyled collapse">

        <li class="sidebar-item">
                <a href="register.php"
                   class="sidebar-link"
                   onclick="highlightSection(this)">
                    Vehicle Information
                </a>

                 <ul class="sub-menu-right">
                        <li><a href="/AMS/Views/addvehicle.php">Vehicle class</a></li>
                        <li><a href="#">Taxation class</a></li>
                        <li><a href="#">status</a></li>
                        <li><a href="#">Fuel Type</a></li>
                        <li><a href="#">Make</a></li>
                        <li><a href="#">Model</a></li>
                        <li><a href="#">Country</a></li>
                        <li><a href="#">Colour</a></li>
                        <li><a href="#">Provincial Counsil</a></li>
                    </ul>

            </li>

        <li class="sidebar-item">
                <a href="register.php"
                   class="sidebar-link"
                   onclick="highlightSection(this)">
                    Registartion Information
                </a>
            </li>





</ul>
        </div>
    </nav>

    <!-- Rest of the code (navbar, modal, styles, scripts) remains unchanged -->
    <div class="main">
        <nav class="navbar navbar-expand navbar-theme">
            <a class="sidebar-toggle d-flex me-2">
                <i class="hamburger align-self-center"></i>
            </a>

            <form class="d-none d-sm-inline-block">
                <input class="form-control form-control-lite" type="text" placeholder="Search projects...">
            </form>

            <div class="navbar-collapse collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
                            <i class="align-middle fas fa-envelope-open"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
                            <div class="dropdown-menu-header">
                                <div class="position-relative">
                                    4 New Messages
                                </div>
                            </div>
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <img src="dist/img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Michelle Bilodeau">
                                        </div>
                                        <div class="col-10 ps-2">
                                            <div class="text-dark">Michelle Bilodeau</div>
                                            <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
                                            <div class="text-muted small mt-1">5m ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-menu-footer">
                                <a href="#" class="text-muted">Show all messages</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                            <i class="align-middle fas fa-bell"></i>
                            <span class="indicator"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                            <div class="dropdown-menu-header">
                                4 New Notifications
                            </div>
                            <div class="list-group">
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="ms-1 text-danger fas fa-fw fa-bell"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">Update completed</div>
                                            <div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
                                            <div class="text-muted small mt-1">2h ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="ms-1 text-warning fas fa-fw fa-envelope-open"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">Lorem ipsum</div>
                                            <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
                                            <div class="text-muted small mt-1">6h ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="ms-1 text-primary fas fa-fw fa-building"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">Login from 192.186.1.1</div>
                                            <div class="text-muted small mt-1">8h ago</div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-2">
                                            <i class="ms-1 text-success fas fa-fw fa-bell-slash"></i>
                                        </div>
                                        <div class="col-10">
                                            <div class="text-dark">New connection</div>
                                            <div class="text-muted small mt-1">Anna accepted your request.</div>
                                            <div class="text-muted small mt-1">12h ago</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-menu-footer">
                                <a href="#" class="text-muted">Show all notifications</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="align-middle fas fa-cog"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-user"></i> View Profile</a>
                            <a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-comments"></i> Contacts</a>
                            <a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-chart-pie"></i> Analytics</a>
                            <a class="dropdown-item" href="#"><i class="align-middle me-1 fas fa-fw fa-cogs"></i> Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="confirmLogout()"><i class="align-middle me-1 fas fa-fw fa-arrow-alt-circle-right"></i> Sign out</a>
                        </div>
                    </li>
                    <!-- Additional Logout Button in Navbar -->
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link" href="#" id="btnLogOut" title="Logout">
                            <i class="align-middle fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Logout Confirmation Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to logout?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="btnLogOutConfirm">Logout</button>
                    </div>
                </div>
            </div>
        </div>