<div class="page-header">
    <?php
        echo "<h1>Change Username for " . $this->authenticator->get_display_name() . "</h1>";
    ?>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>
<?php echo form_open( $page ); ?>
<p>
    <?php
        echo form_label( 'New Username:', 'new_username' );
        echo form_input( 'new_username', set_value( 'new_username' ), 'id="new_username" class="span4"' );
    ?>
</p>
<p>
    <?php echo form_submit( 'submit', 'Change' ) ?>
</p>
<?php echo form_close(); ?>
