<div class="page-header">
    <h1><?php echo $this->authenticator->get_display_name() ?>'s Info</h1>
</div>
<h2> User Name </h2>

<ul>
<?php
    echo $this->authenticator->get_display_name();
?>
</ul>
<br>

<h2> Email </h2>
<ul>
<?php
    echo $this->authenticator->get_email();
?>
</ul>
<br>

<?php
    echo "<h3>" . anchor( base_url( "authenticate/changepassword/" ),  'Change Password' ) ."</h3>";
?>