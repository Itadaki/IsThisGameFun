<?php global $config; ?>

<nav class="menu bg-gray2">
    <ul class="nav nav-pills " role="tablist">
        <li><a href="<?php echo $config['server_root'] ?>main">HOME</a></li>
        <li class="dropdown active">
            <a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $config['server_root'] ?>games">
                GAMES <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo $config['server_root'] ?>games">TOP VOTED</a></li>
                <li><a href="<?php echo $config['server_root'] ?>#">NEW</a></li>
                <li><a href="<?php echo $config['server_root'] ?>all">ALL</a></li>                        
            </ul>
        </li>

        <?php
        //SI estoy logueado
        if (isset($_SESSION['user_nick'])) {
            echo "<li><a href='{$config['server_root']}user/profile/{$_SESSION['user_nick']}'>{$_SESSION['user_nick']}</a></li>";
            echo "<li><a href='{$config['server_root']}login/logout'>Logout</a></li>";
            if ($_SESSION['user_level'] == 'admin') {
                echo "<li class='dropdown'><a class='dropdown-toggle' data-toggle='dropdown' href='{$config['server_root']}admin'>ADMIN panel <span class='caret'></span></a>";
                //At some poit this has to be replaced by a template
                echo "<ul class='dropdown-menu' role='menu'>
                    <li><a href='{$config['server_root']}admin/users'>Users</a></li>
                    <li><a href='{$config['server_root']}admin/games'>Games</a></li>
                    <li><a href='{$config['server_root']}admin/platforms'>Platforms</a></li>
                    <li><a href='{$config['server_root']}admin/sagas'>Sagas</a></li>
                  </ul>";
                echo "</li>";
            }
        } else {
            echo "<li><a href='{$config['server_root']}login'>LOGIN</a></li>";
            echo "<li><a href='{$config['server_root']}signin'>REGISTER</a></li>";
            echo "<li><a href='{$config['server_root']}#'>ABOUT</a></li>";
        }
        ?>


        <li><a href="#">ABOUT</a></li>
        <li><a href="contact.html">CONTACT</a></li>       
    </ul>
</nav>

<!--
<nav id="menu-main">
    <ul>
        <li class="menu-button"><a href="<?php echo $config['server_root'] ?>main">Home</a></li>
        <li class="menu-button"><a href="<?php echo $config['server_root'] ?>games">Games</a>
            <ul>
                <li><a href="<?php echo $config['server_root'] ?>games/all">All</a></li>
                <li>2</li>
            </ul>
        </li>
        <?php
        //SI estoy logueado
//        if (isset($_SESSION['user_nick'])) {
//            echo "<li>Hola <a href='{$config['server_root']}user/profile/{$_SESSION['user_nick']}'>{$_SESSION['user_nick']}</a></li>";
//            echo "<li><a href='{$config['server_root']}login/logout'>Logout</a></li>";
//            if ($_SESSION['user_level'] == 'admin') {
//                echo "<li><a href='{$config['server_root']}admin'>ADMIN panel<ul></a>";
//                //At some poit this has to be replaced by a template
//                echo "<ul>
//                    <li><a href='{$config['server_root']}admin/users'>Users</a></li>
//                    <li><a href='{$config['server_root']}admin/games'>Games</a></li>
//                    <li><a href='{$config['server_root']}admin/platforms'>Platforms</a></li>
//                    <li><a href='{$config['server_root']}admin/sagas'>Sagas</a></li>
//                  </ul>";
//                echo "</ul></li>";
//            }
//        } else {
//            echo "<li class='menu-button'><a href='{$config['server_root']}login'>LOGIN</a></li>";
//            echo "<li class='menu-button'><a href='{$config['server_root']}signin'>REGISTER</a></li>";
//            echo "<li class='menu-button'><a href='{$config['server_root']}#'>ABOUT</a></li>";
//        }
        ?>
    </ul>
</nav> -->