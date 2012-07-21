<!DOCTYPE html>
<html lang="en">
<head>
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
                        if( isset($_SESSION['userid']) ) {
                            echo '<li><a href="/users/totals/' . $_SESSION['userid'] . '">My Totals</a></li>';
                        }
                    ?>
                    <li><a href="/beer/info">Breweries</a></li>
                    <li><a href="/beer/location">Locations</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="span12">