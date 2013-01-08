<div class="page-header">
    <h1>
        <?php echo "Add New User"; ?>
    </h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>

<?php echo form_open( $page ); ?>
<p>
    <?php
        echo form_label( 'First Name:', 'first_name' );
        echo form_input( 'first_name', set_value( 'first_name' ), 'id="first_name"' );

        echo form_label( 'Last Name:', 'last_name' );
        echo form_input( 'last_name', set_value( 'last_name' ), 'id="last_name"' );

        echo form_label( 'Email:', 'email' );
        echo form_input( 'email', set_value( 'email' ), 'id="email"' );

        echo form_label( 'Username:', 'username' );
        echo form_input( 'username', set_value( 'username' ), 'id="username"' );
    ?>
</p>
<p>
    <?php echo form_submit( 'submit', 'Add User' ) ?>
</p>
<?php echo form_close(); ?>

<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/jquery.js"></script>' ;
    echo '<br>' ;
    echo '<script type="text/javascript" src="' . $source . '/jquery-ui-1.8.22.custom.min.js"></script>' ;
?>

