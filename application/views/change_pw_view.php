<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Change Password</title>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php echo "<h1>Change Password for " . $_SESSION[ 'displayname' ] . "</h1>" ?>
<?php echo form_open( 'authenticate/changePassword' ); ?>
<p>
    <?php
        echo form_label( 'Current Password:', 'cur_password' );
        echo form_password( 'cur_password', '', 'id="cur_password"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'New Password:', 'new_password' );
        echo form_password( 'new_password', '', 'id="new_password"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'Confirm Password:', 'confirm_password' );
        echo form_password( 'confirm_password', '', 'id="confirm_password"' );
    ?>
</p>
<p>
    <?php echo form_submit( 'submit', 'Change' ) ?>
</p>
<?php echo form_close(); ?>

<div  class="errors"> <?php echo validation_errors(); ?> </div>
</body>
</html>