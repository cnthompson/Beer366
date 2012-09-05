<?php
// Compute fridge totals
$total = 0;
$trade = 0;
foreach( $fridge_beers as $fridge ) {
    $total = $total + $fridge[ 'quantity' ];
    $trade = $trade + $fridge[ 'will_trade' ];
}

// Generate page text
if( $this->authenticator->is_current_user( $user[ 'user_id' ] ) ) {
    $myFridge = TRUE;
    $page_header = 'My Fridge';
    $total_text = 'You have ' . $total . ' beer' . ( $total == 1 ? '' : 's' ) . ' in your fridge and are willing to trade ' . $trade . '.';
} else {
    $myFridge = FALSE;
    $page_header = $user[ 'display_name' ] . '\'s Fridge';
    $total_text = $user[ 'display_name' ] . ' has ' . $total . ' beer' . ( $total == 1 ? '' : 's' ) . ' in the fridge and is willing to trade ' . $trade . '.';
}
?>
<div class="page-header">
    <h2><?php echo $page_header ?></h2>
</div>

<?php if( $myFridge ): ?>
<div class="btn-group">
    <?php echo anchor( base_url( "log/fridge/" ), '<i class="icon-plus"></i> Add', array( 'class' => 'btn' ) ) ?>
</div>
<?php endif; ?>

<div class="row">
    <div class="span12"><?php echo $total_text ?></div>
</div>

<div class="row">
    <div class="span12">
<?php
    if( count( $fridge_beers ) > 0 ) {
        $tmpl = array(
            'table_open' => '<table class="table table-bordered sortable">'
        );
        $this->table->set_template( $tmpl );
        if( $myFridge ) {
            $this->table->set_heading( '', '', '', 'Brewery', 'Beer', 'Style', 'Size', 'BA Rating', 'Number', 'Will Trade', 'Notes', 'I\'ve Had' );
        } else {
            $this->table->set_heading( 'Brewery', 'Beer', 'Style', 'Size', 'BA Rating', 'Number', 'Will Trade', 'Notes', 'I\'ve Had' );
        }
        foreach( $fridge_beers as $fridge ) {
            $edit    = anchor( base_url( 'log/fridge/' . $fridge[ 'id' ] ), '<i class="icon-pencil"></i>', array( 'title' => 'Edit' ) );
            $log     = anchor( base_url( 'log/fridge/' . $fridge[ 'id' ] . "/l/" ), '<i class="icon-ok"></i>', array( 'title' => 'Log' ) );
            
            $message   = 'Are you sure you want to delete ' . addslashes( $fridge[ 'brewery_name' ] ) . ' ' . addslashes( $fridge[ 'beer_name' ] ) . ' from your fridge?';
            $deleteUrl = base_url( 'log/fridge/' . $fridge[ 'id' ] . '/x/' );
            $delete    = anchor( '#', '<i class="icon-remove"></i>', array( 'title' => 'Delete', 'onClick' => 'confirmMessage( \'Confirm Delete\', \'' . $message . '\', \'' . $deleteUrl .'\', \'Delete\'); return false;'));
            
            $brewery = anchor( base_url( 'beer/info/'   . $fridge[ 'brewery_id' ]                                                                ), $fridge[ 'brewery_name'   ] );
            $beer    = anchor( base_url( 'beer/info/'   . $fridge[ 'brewery_id' ] . '/' . $fridge[ 'beer_id'  ]                                  ), $fridge[ 'beer_name'      ] );
            $sstyle  = anchor( base_url( "beer/styles/" . $fridge[ 'family_id'  ] . "/" . $fridge[ 'style_id' ] . "/" . $fridge[ 'substyle_id' ] ), $fridge[ 'substyle_name'  ] );
            $size    = $fridge[ 'size_name' ];
            $ba      = $fridge[ 'beer_ba_rating' ];
            $number  = (int)$fridge[ 'quantity' ];
            $trade   = (int)$fridge[ 'will_trade' ];
            $notes   = $fridge[ 'notes' ];
            $haveHad = is_numeric( $fridge[ 'have_had' ] ) ? 'X' : '';
            $inMyFridge = is_numeric( $fridge[ 'in_my_fridge' ] ) ? 'F' : '';
            if( $myFridge ) {
                $this->table->add_row( $edit, $log, $delete, $brewery, $beer, $sstyle, $size, $ba, $number, $trade, $notes, $haveHad );
            } else {
                $this->table->add_row( $brewery, $beer, $sstyle, $size, $ba, $number, $trade, $notes, $haveHad . $inMyFridge );
            }
        }
        echo $this->table->generate();
    } else {
        if( $myFridge ) {
            echo "<div class='row'><div class='span12'>You have no beers listed in your fridge. Surely you have some at home for drinking or trading? Why not list them here?</div></div>";
        } else {
            echo "<div class='row'><div class='span12'>No beers here!</div></div>";
        }
    }
?>
    </div>
</div>
<div id="confirmationModal" class="modal hide fade">
    <div class="modal-header"><h3>Test</h3></div>
    <div class="modal-body">blah</div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Cancel</a>
        <a class="btn btn-primary" id="modal-link" href="#">Confirm</a>
    </div>
</div>
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>';
?>
    <script type="text/javascript" src="<?php echo $source . '/jquery.js' ?>"></script>
    <script type="text/javascript" src="<?php echo $source . '/bootstrap.min.js' ?>"></script>
    <script type="text/javascript" src="<?php echo $source . '/confirmation.js' ?>"></script>