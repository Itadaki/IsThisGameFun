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
                            <li><a href="{server_root}games/top">TOP VOTED</a></li>
                            <li><a href="{server_root}games/newest">NEW</a></li>
                            <li><a href="{server_root}games/all">ALL</a></li>        
                        </ul>
                    </li>

                    <li class='{about-active}'><a href='{server_root}#'>ABOUT</a></li>
                    <li class='{contact-active}'><a href="{server_root}#">CONTACT</a></li>   
                </ul>
                <ul class="nav navbar-nav nav-pills navbar-right" style="margin-right:15px">
                    <?php
                    //SI estoy logueado
                    if (isset($_SESSION['user_nick'])) {
                        //If its admin
                        if (isAdmin()) {
                            echo "<li class='{admin-active}'><a href='{server_root}admin/games'><span class='glyphicon glyphicon-cog'></span> C-PANEL</a>";
                        }
                        ?>
                        <li class="dropdown {user-active}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="">
                                <span class='glyphicon glyphicon-user'></span>  <?php echo $_SESSION['user_nick'] ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href='<?php echo "{server_root}user/profile/" . $_SESSION['user_nick'] ?>'><span class='glyphicon glyphicon-user'></span> PROFILE</a></li>
                                <li><a href="{server_root}login/logout"><span class='glyphicon glyphicon-log-out'></span> LOG OUT</a></li>                        
                            </ul>
                        </li>
                        <?php
                    } else {
                        ?>
                        <ul class="nav navbar-nav nav-pills navbar-right">
                            <li><a href="{server_root}signin"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
<!--                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Sign In <strong class="caret"></strong></a>
                                <div class="dropdown-menu" style="width: 400px;padding: 15px; padding-bottom: 0px;">
                                    <form id="form-signin" method="post" class="form-horizontal" action="{server_root}signin">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Username*</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="user" name="user" value="" />
                                                <p class="help-block">The name you will log in with.</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Email address*</label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" name="email" value="" />
                                                <p class="help-block">Your email for information purposes.</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Password*</label>
                                            <div class="col-lg-8">
                                                <input type="password" class="form-control" name="password" />
                                                <p class="help-block">The password for the log in.</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Retype password*</label>
                                            <div class="col-lg-8">
                                                <input type="password" class="form-control" name="confirmPassword" />
                                                <p class="help-block">Repeat the password.</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Nick*</label>
                                            <div class="col-lg-8">
                                                <input id="nick" type="text" class="form-control" name="nick" value="" />
                                                <p class="help-block">The public name that will be shown to everyone.</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-8 col-lg-offset-2 text-center">
                                                <p>Fields marked with * are mandatory.</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-8 col-lg-offset-2">
                                                <button type="submit" class="btn btn-block btn-info" name="signup" value="Sign up">Sign up</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </li>-->
                            <!--<li><a href="{server_root}login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>-->
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-log-in"></span> Log In <strong class="caret"></strong></a>
                                <div class="dropdown-menu" style="width: 200px;padding: 15px; padding-bottom: 0px;">
                                    <form id="form-login" method="post" action="{server_root}login" accept-charset="UTF-8">
                                        <div class="form-group">
                                            <label for="user">Username</label>
                                            <div class="">
                                                <input type="text" class="form-control" placeholder="Username" id="login-username" name="user" value="" />
                                                <!--<p class="help-block">The name you will log in with.</p>-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <div class="">
                                                <input class="form-control" type="password" placeholder="Password" id="login-password" name="password" />
                                                <!--<p class="help-block">The name you will log in with.</p>-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="string optional" for="keep_logged"> Remember me</label>
                                            <input style="float: left; margin-right: 10px;" type="checkbox" name="keep_logged-me" id="keep_logged" value="1">
                                        </div>
                                        <input style="margin-bottom: 15px;" class="btn btn-info btn-block" type="submit" name="login" id="login" value="Log In">

                                    </form>
                                </div>
                            </li>
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