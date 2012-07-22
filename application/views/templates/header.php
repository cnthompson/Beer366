<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $title ?></title>
    <style>label { display: block; } .errors { color: red;} </style>
    <link href="<?php echo base_url(); ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/doc.css" rel="stylesheet">
</head>
<body data-offset="25">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="/">Beer366</a>
                <ul class="nav">
                    <li><a href="/users/totals">All Totals</a></li>
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
                                    <li><a href="/users/totals/<?php echo $_SESSION['userid'] ?>">My Totals</a></li>
                                    <li><a href="/users/info/">User Info</a></li>
                                    <li class="divider"></li>
                                    <li><a href="/authenticate/logout/">Sign Out</a></li>
                                </ul>
                            </li>                            
                    <?php
                        endif;
                    ?>
                    <li><a href="/beer/info">Breweries</a></li>
                    <li><a href="/beer/location">Locations</a></li>
                    <li><a href="/beer/styles">Styles</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="span12">