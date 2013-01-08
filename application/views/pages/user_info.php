<div class="page-header">
    <h1><?php echo $this->authenticator->get_display_name() ?>'s Info</h1>
</div>
<h2> User Name </h2>

<ul>
<?php
    echo "<h3>" . $this->authenticator->get_display_name() . "</h3>";
    echo anchor( base_url( "authenticate/changelogin/" ),  'Change' )
?>
</ul>
<br>

<h2> Email </h2>
<ul>
<?php
    echo "<h3>" . $this->authenticator->get_email() . "</h3>";
?>
</ul>
<br>

<?php
    echo "<h3>" . anchor( base_url( "authenticate/changepassword/" ),  'Change Password' ) ."</h3>";
?>