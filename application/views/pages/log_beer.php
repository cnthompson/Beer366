<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Brewery</title>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<div class="page-header">
    <h1>Add Beer</h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>


<?php echo form_open( 'log/beer' ); ?>
<p>
    <?php
        echo form_label( 'Beer Name:', 'beername' );
        echo form_input( 'beername', set_value( 'beername' ), 'id="beername"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'Brewery', 'brewery' );
        echo form_dropdown( 'brewery', $breweries,  set_value( 'brewery', null ), 'id="brewery"' );
    ?>
</p>
<p>
    <?php
        //echo form_label( 'Family', 'family' );
        //echo form_dropdown( 'family', array(), set_value( 'family', null ), 'id="family"' );

        //echo form_label( 'Style', 'style' );
        //echo form_dropdown( 'style', array(), set_value( 'style', null ), 'id="style"' );

        echo form_label( 'Sub-Style', 'substyle' );
        echo form_dropdown( 'substyle', $substyles, set_value( 'substyle' ), 'id="substyle"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'ABV (%):', 'abv' );
        echo form_input( 'abv', set_value( 'abv' ), 'id="abv"' );

        echo form_label( 'BA Rating:', 'ba' );
        echo form_input( 'ba', set_value( 'ba' ), 'id="ba"' );
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => 'Add Beer', 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>

</body>
</html>