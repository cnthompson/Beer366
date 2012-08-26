<div class="page-header">
    <h1>
        <?php echo ( $editScratch == null ? "Add Scratch" : "Edit Scratch" ) ; ?>
    </h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>


<?php echo form_open( 'log/scratch' . ( $editScratch == null ? '' : ( '/' . $editScratch[ 'id' ] ) ) ); ?>
<?php echo form_hidden( 'scratch_id', $editScratch == null ? -1 : $editScratch[ 'id' ] ); ?>
<p>
    <?php
        echo form_label( 'Date:', 'date' );
        echo form_input( 'date', set_value( 'date', $editScratch == null ? null : $editScratch[ 'date' ] ), 'id="date"' );

        echo form_label( 'Brewery', 'brewery' );
        echo form_input( 'brewery', set_value( 'brewery', $editScratch == null ? null : $editScratch[ 'brewery' ] ), 'id="brewery"' );

        echo form_label( 'Beer Name:', 'beer' );
        echo form_input( 'beer', set_value( 'beer', $editScratch == null ? null : $editScratch[ 'beer' ] ), 'id="beer"' );

        echo form_label( 'Sub-Style', 'substyle' );
        echo form_input( 'substyle', set_value( 'substyle', $editScratch == null ? null : $editScratch[ 'sstyle' ] ), 'id="substyle"' );

        echo form_label( 'Size', 'size' );
        echo form_input( 'size', set_value( 'size', $editScratch == null ? null : $editScratch[ 'size' ] ), 'id="size"' );

        echo form_label( 'Rating', 'rating' );
        echo form_input( 'rating', set_value( 'rating', $editScratch == null ? null : $editScratch[ 'rating' ] ), 'id="rating"' );
        
        echo form_label( 'Notes', 'notes' );
        echo form_textarea( 'notes', set_value( 'notes', $editScratch == null ? null : $editScratch[ 'notes' ] ), 'id="notes"' );
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => $editScratch == null ? 'Add Scratch' : 'Update', 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>

<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/jquery.js"></script>' ;
    echo '<br>' ;
    echo '<script type="text/javascript" src="' . $source . '/jquery-ui-1.8.22.custom.min.js"></script>' ;
?>

<script type="text/javascript">
    $("#date").datepicker( { dateFormat: "yy-mm-dd" } );
</script>
