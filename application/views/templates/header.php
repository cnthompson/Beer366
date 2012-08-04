<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <style>label { display: block; } .errors { color: red;} </style>
    <link href="<?php echo base_url(); ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/smoothness/jquery-ui-1.8.22.custom.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/doc.css" rel="stylesheet">
</head>
<body data-offset="25">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="<?php echo base_url( "/" ) ?>">Beer366</a>
                <ul class="nav">
                    <li><a href="<?php echo base_url( "/users/totals/" ) ?>">All Totals</a></li>
                    <?php
                        if( isset($_SESSION['userid']) ):
                    ?>
                            <li class="dropdown" id="userMenu">
                                <a href="#"
                                    class="dropdown-toggle"
                                    data-toggle="dropdown"
                                    data-target="#userMenu"><?php echo $_SESSION['displayname']?> <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url( "/users/totals/" . $_SESSION['userid'] . "/" ) ?>">My Totals</a></li>
                                    <li><a href="<?php echo base_url( "/users/info/" ) ?>">User Info</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo base_url( "/authenticate/logout/" ) ?>">Sign Out</a></li>
                                </ul>
                            </li>
                            <li class="dropdown" id="logMenu">
                                <a href="#"
                                    class="dropdown-toggle"
                                    data-toggle="dropdown"
                                    data-target="#logMenu">Log<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url( "/log/drink/" ) ?>">Log Drink</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo base_url( "/log/brewery/" ) ?>">Add Brewery</a></li>
                                    <li><a href="<?php echo base_url( "/log/beer/" ) ?>">Add Beer</a></li>
                                </ul>
                            </li>
                    <?php
                        endif;
                    ?>
                    <li><a href="<?php echo base_url( "/beer/info/" ) ?>">Breweries</a></li>
                    <li><a href="<?php echo base_url( "/beer/location/" ) ?>">Locations</a></li>
                    <li><a href="<?php echo base_url( "/beer/styles/" ) ?>">Styles</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="span12">