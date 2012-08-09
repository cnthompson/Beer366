<div class="page-header">
    <?php
        if( $this->authenticator->is_password_expired() ) {
            echo "<h1>" . $this->authenticator->get_display_name() . ", your current password has expired and must be changed.</h1>";
        } else {
            echo "<h1>Change Password for " . $this->authenticator->get_display_name() . "</h1>";
        }
    ?>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>
<?php echo form_open( $page ); ?>
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
