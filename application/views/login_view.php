<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Beer366 Login</title>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<h1> Login </h1>
<?php echo form_open('authenticate'); ?>
<p>
    <?php
        echo form_label( 'Email Address:', 'email_address' );
        echo form_input( 'email_address', set_value( 'email_address' ), 'id="email_address"', 'size="300"' );
    ?>
</p>
<p>
    <?php
        $data = array(
            'name'        => 'username',
            'id'          => 'username',
            'value'       => 'johndoe',
            'size'        => '50',
            'style'       => 'width:50%',
        );
        echo form_label( 'Password:', 'password' );
        //echo form_password( 'password', '', 'id="password"' );
        echo form_password( 'password', '', 'id="password"', 'size="300"', 'style="width:50%"' );
    ?>
</p>
<p>
    <?php echo form_submit( 'submit', 'login' ) ?>
</p>
<?php echo form_close(); ?>

<div  class="errors"> <?php echo validation_errors(); ?> </div>
</body>
</html>