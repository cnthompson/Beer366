<?php
    // Authentication result
    $auth = $this->authenticator->check_auth();

    // URLs used later on the page
    $edit_url   = base_url( "log/beer/" . $beer[ 'beer_id' ] );
    $log_url    = base_url( 'log/drink/' . $beer[ 'beer_id' ] . '/d' );
    $fridge_url = base_url( 'log/fridge/' . $beer[ 'beer_id' ] . '/a' );
    $info_url   = base_url( "beer/info/" . $brewery[ 'brewery_id' ] );
?>
<div class="page-header">
    <h1><?php echo $beer[ 'beer_name' ]; ?></h1>
</div>
<?php if( $auth ): ?>
    <div class="btn-group">
        <?php echo anchor( $log_url, "<i class='icon-plus'></i> Log This", array( 'class' => 'btn' ) ); ?>
        <?php echo anchor( $fridge_url, "<i class='icon-calendar'></i> Add to My Fridge", array( 'class' => 'btn' ) ); ?>
        <?php echo anchor( $edit_url, "<i class='icon-pencil'></i> Edit Beer", array( 'class' => 'btn' ) ); ?>
    </div>
<?php endif; ?>
<div class="row">
    <div class="span4">
        <h3>Brewed By</h3>
        <?php echo anchor( $info_url, $brewery[ 'full_name' ] ); ?>
        <address>
        <?php
            $cityBase = "beer/location/" . $brewery[ 'country' ] . "/";
            if( isset( $brewery[ 'region' ] ) ) {
                $cityBase .= $brewery[ 'region' ] ."/";
            } else {
                $cityBase .= "0/";
            }
            $cityBase .= rawurlencode( $brewery[ 'city' ] );
            $cityAnchor = anchor( base_url( $cityBase ), $brewery[ 'city' ] );
            $location = $cityAnchor . ", ";

            if( isset( $brewery[ 'region' ] ) ) {
                $rgnBase = "beer/location/" . $brewery[ 'country' ] . "/" .$brewery[ 'region' ];
                $rgnAnchor = anchor( base_url( $rgnBase ), $brewery[ 'rgn_name' ] );
                $location .= $rgnAnchor . ", ";
            }

            $countryBase = "beer/location/" . $brewery[ 'country' ];
            $countryAnchor = anchor( base_url( $countryBase ), $brewery[ 'country_name' ] );
            $location .= $countryAnchor;

            echo $location;
        ?>
        </address>
    </div>
    <div class="span4">
        <h3>Style</h3>
        <?php
            $style = $beer[ 'substyle_name' ];
            $s1 = base_url( "beer/styles/" . $beer[ 'family_id' ] . "/" . $beer[ 'style_id' ] . "/" . $beer[ 'substyle_id' ] );
            $ssAnchor = anchor( $s1, $style );
            $abvF  = (float)$beer[ 'beer_abv' ];
            $abvS  = $abvF == 0.0 ? '<unknown>' : sprintf( '%.2f%%', $abvF );
            echo $ssAnchor . " (" . $abvS . " ABV)";
        ?>
    </div>
    <div class="span4">
        <h3>BA Rating</h3>
        <?php
            $ba = $beer[ 'beer_ba_rating' ];
            $bapage = $beer[ 'ba_page' ];
            $ba = $ba == NULL ? 'N/A' : $ba;
            $baText = ( $bapage == null or strlen( $bapage ) == 0 ) ? $ba : ( anchor( 'http://beeradvocate.com/beer/profile/' . $bapage, $ba . '<i class="icon-share-alt"></i>', 'target="_blank" title="Beer Advocate Page"' ) );
            echo $baText;
        ?>
    </div>
</div>
<h2> Logged Drinks </h2>
<p>
<?php
    if( $drinkLog != false ) {
        $tmpl = array(
            'table_open' => '<table class="table table-bordered sortable">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Date', 'Person', 'Serving', 'Rating', 'Notes' );
        foreach( $drinkLog as $log ) {
            $this->table->add_row( $log[ 'date' ], $log[ 'display_name' ], $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
        }
        echo $this->table->generate();
    } else {
        echo 'No one has logged this beer yet</br>';
    }
?>
</p>
<p>
<?php
    if( count( $fridgeBeers ) > 0 ) {
        echo '<h2>In Fridges</h2>';
        $tmpl = array(
            'table_open' => '<table class="table table-bordered sortable">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Person', 'Serving', 'Quantity', 'Will Trade' );
        foreach( $fridgeBeers as $fridge ) {
            $u = anchor( base_url( "users/fridge/" . $fridge[ 'user_id' ] . "/" ), $fridge[ 'user_name' ] );
            $this->table->add_row( $u, $fridge[ 'size_name' ], $fridge[ 'quantity' ], $fridge[ 'will_trade' ] );
        }
        echo $this->table->generate();
    }
?>
</p>
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>' ;
?>
</div>