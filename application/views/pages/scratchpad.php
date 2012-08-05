
<h2>My Scratchpad&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo anchor( base_url( "log/scratch/" ), "+ Add" ); ?></h2>
<p>
<?php
    if( count( $scratches ) > 0 ) {
        $tmpl = array(
            'table_open' => '<table class="table table-bordered">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( '', '', '', 'Date', 'Brewery', 'Beer', 'Size', 'Rating', 'Notes' );
        foreach( $scratches as $scratch ) {
            $edit_props = array(
                'src' => 'img/pencil.png',
                'alt' => 'Edit',
            );
            $edit    = anchor( base_url( 'log/scratch/' . $scratch[ 'scratchpad_id' ] ), img( $edit_props ), array( 'title' => 'Edit' ) );
            $convert_props = array(
                'src' => 'img/checkmark-green.png',
                'alt' => 'Convert',
            );
            $convert  = anchor( base_url( 'log/scratch/' . $scratch[ 'scratchpad_id' ] . "/c/" ), img( $convert_props ), array( 'title' => 'Convert' ) );
            $delete_props = array(
                'src' => 'img/red-x.png',
                'alt' => 'Delete',
            );
            $delete  = anchor( base_url( 'log/scratch/' . $scratch[ 'scratchpad_id' ] . "/x/" ), img( $delete_props ), array( 'title' => 'Delete' ) );
            $date    = $scratch[ 'date' ];
            $brewery = $scratch[ 'brewer' ];
            $beer    = $scratch[ 'beer' ];
            $sstyle  = $scratch[ 'substyle' ];
            $size    = $scratch[ 'size' ];
            $rating  = $scratch[ 'rating' ];
            $notes   = $scratch[ 'notes' ];
            $this->table->add_row( $edit, $convert, $delete, $date, $brewery, $beer, $size, $rating, $notes );
        }
        echo $this->table->generate();
    } else {
        echo "<p>You have no beers in your scratchpad space.</p>";
    }
?>
</p>
