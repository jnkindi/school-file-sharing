        <div class="header">
            <div class="header-left">
                <a href="home.php" class="logo">
                    <img src="assets/img/logo.png" width="40" height="40" alt="">
                </a>
            </div>
            <a id="toggle_btn" href="javascript:void(0);"><i class="la la-bars"></i></a>
            <div class="page-title-box pull-left">
                <h3>File Sharing</h3>
            </div>
            <a id="mobile_btn" class="mobile_btn pull-left" href="#sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
            <ul class="nav navbar-nav navbar-right user-menu pull-right">
                <li class="dropdown">
                    <a href="edit-profile.php" class="dropdown-toggle user-link" data-toggle="dropdown" title="<?php echo $names; ?>">
                        <span class="user-img"><img class="img-circle avatar" src="<?php echo $user_photo; ?>" width="40" alt="<?php echo $names; ?>">
                            <span class="status online"></span></span>
                        <span><?php echo $names; ?></span>
                        <i class="caret"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="edit-profile.php">Edit Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li>
                            <a href="home.php"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                        </li>
                        <li>
                            <a href="shared-files.php"><i class="la la-files-o"></i> <span>Share with me</span></a>
                        </li>
                        <li>
                            <a href="shared-files.php?byme"><i class="la la-files-o"></i> <span>Share by me</span></a>
                        </li>
                        <?php
                        if ($user_role != 'Student') {
                            ?>
                            <li>
                                <a href="users.php"><i class="la la-user-plus"></i> <span>Users</span></a>
                            </li>
                        <?php
                        }
                        ?>
                        <li>
                            <a href="report.php"><i class="la la-file-pdf-o"></i> <span>My sharing report</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>