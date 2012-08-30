<?php
    if( $this->authenticator->is_current_user( $user[ 'user_id' ] ) ) {
        echo '<h2>My Fridge&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . anchor( base_url( "log/fridge/" ), "+ Add" ) . '</h2>';
    } else {
        echo '<h2>' . $user[ 'display_name' ] . '\'s Fridge</h2>';
    }
?>
<p>
<?php
    $myFridge = $this->authenticator->is_current_user( $user[ 'user_id' ] );
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
        $total = 0;
        $trade = 0;
        foreach( $fridge_beers as $fridge ) {
            $total = $total + $fridge[ 'quantity' ];
            $trade = $trade + $fridge[ 'will_trade' ];
        }
        if( $myFridge ) {
            echo '<p>You have ' . $total . ' beer' . ( $total == 1 ? '' : 's' ) . ' in your fridge and are willing to trade ' . $trade . '.</p>';
        } else {
            echo '<p>' . $user[ 'display_name' ] . ' has ' . $total . ' beer' . ( $total == 1 ? '' : 's' ) . ' in the fridge and is willing to trade ' . $trade . '.</p>';
        }
        foreach( $fridge_beers as $fridge ) {
            $edit_props = array(
                'src' => 'img/pencil.png',
                'alt' => 'Edit',
            );
            $edit    = anchor( base_url( 'log/fridge/' . $fridge[ 'id' ] ), img( $edit_props ), array( 'title' => 'Edit' ) );
            $convert_props = array(
                'src' => 'img/checkmark-green.png',
                'alt' => 'Log',
            );
            $log  = anchor( base_url( 'log/fridge/' . $fridge[ 'id' ] . "/l/" ), img( $convert_props ), array( 'title' => 'Log' ) );
            $delete_props = array(
                'src' => 'img/red-x.png',
                'alt' => 'Delete',
            );
            $delete  = anchor( base_url( 'log/fridge/'  . $fridge[ 'id'         ] . "/x/" ), img( $delete_props ), array( 'title' => 'Delete' ) );
            
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
            echo "<p>You have no beers listed in your fridge. Surely you have some at home for drinking or trading? Why not list them here?</p>";
        } else {
            echo "<p>No beers here!</p>";
        }
    }
?>
</p>
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>' ;
?>
