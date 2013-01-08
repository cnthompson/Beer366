<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href='http://fonts.googleapis.com/css?family=Puritan' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url(); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/bootstrap-override.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/doc.css" rel="stylesheet">
    <style>
        body { padding-top: 0; background-color: #fff; color: #d0d1d3; font-family: 'Puritan',Helvetia,sans-serif; }
    </style>
</head>
<body>
    <div class="container">
    <div class="row">
        <div class="span6 offset3 logo" class="centered"><img width="100%" src="<?php echo base_url(''); ?>/img/logo_splash.png" alt="Beer366: the quest to find the best"/></div>
    </div>
    <div class="row">
    <?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
    <?php if ( $error ): ?>
        <div class="alert alert-error"><?php echo $error ?></div>
    <?php endif; ?>
    </div>
    <?php echo form_open( $page ); ?>
    <div class="row">
        <div class="span4 offset4">
            <div class="row">
                <div class="span4">
                <?php
                    $attributes = array(
                        'name'        => 'login',
                        'value'       => set_value( 'login' ),
                        'id'          => 'login',
                        'autofocus'   => 'autofocus',
                        'class'       => 'span4',
                        'placeholder' => 'login or email address'
                    );
                    echo form_input( $attributes );
                ?>
                </div>
            </div>
            <div class="row">
                <div class="span4">
                    <?php
                        $attributes = array(
                            'name'         => 'password',
                            'id'           => 'password',
                            'class'        => 'span4',
                            'placeholder'  => 'password'
                        );
                        echo form_password( $attributes );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="span2 offset1">
                    <?php echo form_submit( array( 'type' => 'submit', 'value' => 'Login', 'class' => 'btn btn-inverse span2' ) ) ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    </div>
</body>
</html>
