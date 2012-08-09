<div class="page-header">
    <h1>Login</h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>
<?php echo form_open( $page ); ?>
<p>
    <?php
        $attributes = array(
            'name'  => 'email_address',
            'value' => set_value( 'email_address' ),
            'id'    => 'email_address',
            'class' => 'span4'
        );
        echo form_label( 'Email Address:', 'email_address' );
        echo form_input( $attributes );
    ?>
</p>
<p>
    <?php
        $attributes = array(
            'name'        => 'password',
            'id'          => 'password',
            'class'        => 'span4'
        );
        echo form_label( 'Password:', 'password' );
        echo form_password( $attributes );
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => 'Login', 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>
