<?php global $config; ?>
<div class="sticky">
    <nav class="menu bg-gray2">
        <div class="container-fluid itgf-nav">
            <div class="navbar-header">

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar" aria-expanded="false">
                    <span class="glyphicon glyphicon-menu-hamburger"></span>                      
                </button>
                <a class="navbar-brand closed" href="{server_root}">
                    <div><span class="text-center cl-blue">ITG<span class="cl-red">F?</span></span></div>
                </a>
            </div>

            <div class="navbar-collapse collapse" id="myNavbar" aria-expanded="false" style="height: 1px;">
                <ul class="nav navbar-nav nav-pills ">
                    <li class="{main-active}"><a href="{server_root}main"><span class="glyphicon glyphicon-home"></span></a></li>
                    <li class="dropdown {games-active}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="{server_root}games">
                            GAMES <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{server_root}games">TOP VOTED</a></li>
                            <li><a href="{server_root}#">NEW</a></li>
                            <li><a href="{server_root}all">ALL</a></li>        
                        </ul>
                    </li>

                    <li class='{about-active}'><a href='{server_root}#'>SAGAS</a></li>
                    <li class='{about-active}'><a href='{server_root}#'>ABOUT</a></li>
                    <li class='{contact-active}'><a href="{server_root}#">CONTACT</a></li>   
                </ul>
                <ul class="nav navbar-nav nav-pills navbar-right" style="margin-right:15px">
                    <?php
                    //SI estoy logueado
                    if (isset($_SESSION['user_nick'])) {
                        //If its admin
                        if (isAdmin()) {
                            echo "<li class='{admin-active}'><a href='{server_root}admin/games'>CPanel</a>";
                        }
                        ?>
                        <li class="dropdown {user-active}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{server_root}games">
                                <span class='glyphicon glyphicon-user'></span>  <?php echo $_SESSION['user_nick'] ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href='<?php "{server_root}user/profile/" . $_SESSION['user_nick'] ?>'><span class='glyphicon glyphicon-user'></span> Profile</a></li>
                                <li><a href="{server_root}login/logout"><span class='glyphicon glyphicon-log-out'></span>Log out</a></li>                        
                            </ul>
                        </li>
                        <?php
                    } else {
                        ?>
                        <ul class="nav navbar-nav nav-pills navbar-right">
                            <li><a href="{server_root}signin"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                            <li><a href="{server_root}login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        </ul>

                        <?php
                    }
                    ?>
                </ul>
                </nav>
            </div>
            <nav class="placeholder" style="display: none;">
                <ul class="nav nav-pills " role="tablist">
                    <li><a href="#">Hi there!</a></li>
                </ul>
            </nav>