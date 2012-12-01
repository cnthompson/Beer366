<?php
// Compute cellar totals
$total = 0;
$trade = 0;
foreach( $cellar_beers as $cellar ) {
    $total = $total + $cellar[ 'quantity' ];
    $trade = $trade + $cellar[ 'will_trade' ];
}

// Generate page text
if( $this->authenticator->is_current_user( $user[ 'user_id' ] ) ) {
    $myCellar = TRUE;
    $page_header = 'My Cellar';
    $total_text = 'You have ' . $total . ' beer' . ( $total == 1 ? '' : 's' ) . ' in your cellar and are willing to trade ' . $trade . '.';
} else {
    $myCellar = FALSE;
    $page_header = $user[ 'display_name' ] . '\'s Cellar';
    $total_text = $user[ 'display_name' ] . ' has ' . $total . ' beer' . ( $total == 1 ? '' : 's' ) . ' in the cellar and is willing to trade ' . $trade . '.';
}
?>
<div class="page-header">
    <h2><?php echo $page_header ?></h2>
</div>

<?php if( $myCellar ): ?>
<div class="btn-group">
    <?php echo anchor( base_url( "log/cellar/" ), '<i class="icon-plus"></i> Add', array( 'class' => 'btn' ) ) ?>
</div>
<?php endif; ?>

<div class="row">
    <div class="span12"><?php echo $total_text ?></div>
</div>

<div class="row">
    <div class="span12">
<?php
    if( count( $cellar_beers ) > 0 ) {
        $tmpl = array(
            'table_open' => '<table class="table table-bordered sortable">'
        );
        $this->table->set_template( $tmpl );
        if( $myCellar ) {
            $this->table->set_heading( '', '', '', 'Brewery', 'Beer', 'Style', 'Size', 'BA Rating', 'Number', 'Will Trade', 'Notes', 'I\'ve Had' );
        } else {
            $this->table->set_heading( 'Brewery', 'Beer', 'Style', 'Size', 'BA Rating', 'Number', 'Will Trade', 'Notes', 'I\'ve Had' );
        }
        foreach( $cellar_beers as $cellar ) {
            $edit    = anchor( base_url( 'log/cellar/' . $cellar[ 'id' ] ), '<i class="icon-pencil"></i>', array( 'title' => 'Edit' ) );
            $log     = anchor( base_url( 'log/cellar/' . $cellar[ 'id' ] . "/l/" ), '<i class="icon-ok"></i>', array( 'title' => 'Log' ) );
            
            $message   = 'Are you sure you want to delete ' . addslashes( $cellar[ 'brewery_name' ] ) . ' ' . addslashes( $cellar[ 'beer_name' ] ) . ' from your cellar?';
            $deleteUrl = base_url( 'log/cellar/' . $cellar[ 'id' ] . '/x/' );
            $delete    = anchor( '#', '<i class="icon-remove"></i>', array( 'title' => 'Delete', 'onClick' => 'confirmMessage( \'Confirm Delete\', \'' . $message . '\', \'' . $deleteUrl .'\', \'Delete\'); return false;'));
            
            $brewery = anchor( base_url( 'beer/info/'   . $cellar[ 'brewery_id' ]                                                                ), $cellar[ 'brewery_name'   ] );
            $beer    = anchor( base_url( 'beer/info/'   . $cellar[ 'brewery_id' ] . '/' . $cellar[ 'beer_id'  ]                                  ), $cellar[ 'beer_name'      ] );
            $sstyle  = anchor( base_url( "beer/styles/" . $cellar[ 'family_id'  ] . "/" . $cellar[ 'style_id' ] . "/" . $cellar[ 'substyle_id' ] ), $cellar[ 'substyle_name'  ] );
            $size    = $cellar[ 'size_name' ];
            $ba      = $cellar[ 'beer_ba_rating' ];
            $number  = (int)$cellar[ 'quantity' ];
            $trade   = (int)$cellar[ 'will_trade' ];
            $notes   = $cellar[ 'notes' ];
            $haveHad = is_numeric( $cellar[ 'have_had' ] ) ? 'X' : '';
            $inMyCellar = is_numeric( $cellar[ 'in_my_cellar' ] ) ? 'C' : '';
            if( $myCellar ) {
                $this->table->add_row( $edit, $log, $delete, $brewery, $beer, $sstyle, $size, $ba, $number, $trade, $notes, $haveHad );
            } else {
                $this->table->add_row( $brewery, $beer, $sstyle, $size, $ba, $number, $trade, $notes, $haveHad . $inMyCellar );
            }
        }
        echo $this->table->generate();
    } else {
        if( $myCellar ) {
            echo "<div class='row'><div class='span12'>You have no beers listed in your cellar. Surely you have some at home for drinking or trading? Why not list them here?</div></div>";
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
?>
<script type="text/javascript" src="<?php echo $source . '/sorttable.js'    ?>"></script>
<script type="text/javascript" src="<?php echo $source . '/confirmation.js' ?>"></script>
