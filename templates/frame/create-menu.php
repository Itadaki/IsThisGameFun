<?php global $config; ?>

<nav class="menu bg-gray2">
    <ul class="nav nav-pills " role="tablist">
        <li class="{main-active}"><a href="<?php echo $config['server_root'] ?>main"><span class="glyphicon glyphicon-home"></span></a></li>
        <li class="dropdown {games-active}">
            <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $config['server_root'] ?>games">
                GAMES <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo $config['server_root'] ?>games">TOP VOTED</a></li>
                <li><a href="<?php echo $config['server_root'] ?>#">NEW</a></li>
                <li><a href="<?php echo $config['server_root'] ?>all">ALL</a></li>                        
            </ul>
        </li>

        <li class='{about-active}'><a href='<?php echo $config['server_root'] ?>#'>ABOUT</a></li>
        <li class='{contact-active}'><a href="<?php echo $config['server_root'] ?>#">CONTACT</a></li>   

        <ul class="nav navbar-nav navbar-right" style="margin-right:15px">
            <?php
            //SI estoy logueado
            if (isset($_SESSION['user_nick'])) {
                //If its admin
                if (isAdmin()) {
                    echo "<li class='{admin-active}'><a href='{$config['server_root']}admin/games'>CPanel</a>";
                }
                ?>
                <li class="dropdown {user-active}">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $config['server_root'] ?>games">
                        <?php echo $_SESSION['user_nick'] ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href='<?php echo $config['server_root'] . "user/profile/" . $_SESSION['user_nick'] ?>'><span class='glyphicon glyphicon-user'></span> Profile</a></li>
                        <li><a href="<?php echo $config['server_root'] ?>login/logout"><span class='glyphicon glyphicon-log-out'></span>Log out</a></li>                        
                    </ul>
                </li>
                <?php
            } else {
                ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="<?php echo $config['server_root'] ?>signin"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <li><a href="<?php echo $config['server_root'] ?>login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                </ul>

                <?php
            }
            ?>
        </ul>
 
    </ul>
</nav>