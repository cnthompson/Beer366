<div class="page-header">
    <h1>My Scratchpad</h1>
</div>
<div class="btn-group">
    <?php echo anchor( base_url( 'log/scratch/' ), '<i class="icon-plus"></i> Add', array( 'class' => 'btn' ) ); ?>
</div>
<p>
<?php
    if( count( $scratches ) > 0 ) {
        $tmpl = array(
            'table_open' => '<table class="table table-bordered sortable">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( '', '', '', 'Date', 'Brewery', 'Beer', 'Size', 'Rating', 'Notes' );
        foreach( $scratches as $scratch ) {
            $date      = $scratch[ 'date' ];
            $brewery   = $scratch[ 'brewer' ];
            $beer      = $scratch[ 'beer' ];
            $sstyle    = $scratch[ 'substyle' ];
            $size      = $scratch[ 'size' ];
            $rating    = $scratch[ 'rating' ];
            $notes     = $scratch[ 'notes' ];
            $message   = 'Are you sure you want to delete ' . $beer . ' from your scratchpad?';
            $deleteUrl = base_url( 'log/scratch/' . $scratch[ 'scratchpad_id' ] . '/x/' );
            $edit      = anchor( base_url( 'log/scratch/' . $scratch[ 'scratchpad_id' ] ), '<i class="icon-pencil"></i>', array( 'title' => 'Edit' ) );
            $convert   = anchor( base_url( 'log/scratch/' . $scratch[ 'scratchpad_id' ] . "/c/" ), '<i class="icon-ok"></i>', array( 'title' => 'Convert' ) );
            $delete    = anchor( '#confirmationModal', '<i class="icon-remove"></i>', array( 'title' => 'Delete', 'onClick' => 'confirmMessage( \'Confirm Delete\', \'' . $message . '\', \'' . $deleteUrl .'\', \'Delete\');', 'data-toggle' => 'modal'));
            $this->table->add_row( $edit, $convert, $delete, $date, $brewery, $beer, $size, $rating, $notes );
        }
        echo $this->table->generate();
    } else {
        echo "<p>You have no beers in your scratchpad space.</p>";
    }
?>
</p>
<div id="confirmationModal" class="modal hide fade">
    <div class="modal-header"><h3>Test</h3></div>
    <div class="modal-body">blah</div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
        <a class="btn btn-primary" id="modal-link" href="#">Confirm</a>
    </div>
</div>
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>';
?>
    <script type="text/javascript" src="<?php echo $source . '/jquery.js' ?>"></script>
    <script type="text/javascript" src="<?php echo $source . '/bootstrap-modal.js' ?>"></script>
    <script type="text/javascript" src="<?php echo $source . '/confirmation.js' ?>"></script></head>