<div class="page-header">
    <h1><?php echo $_SESSION[ 'displayname' ] ?>'s Info</h1>
</div>
<h2> User Name </h2>

<ul>
<?php
    echo $_SESSION[ 'displayname' ];
?>
</ul>
<br>

<h2> Email </h2>
<ul>
<?php
    echo $_SESSION[ 'email' ];
?>
</ul>
<br>

<?php
    echo "<h3>" . anchor( base_url( "index.php/authenticate/changepassword/" ),  'Change Password' ) ."</h3>";
?>

</body>
</html>