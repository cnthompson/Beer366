<?php
    if( isset( $_SESSION[ 'email' ] ) ) {
        echo '<h1 class="dropdown" id="beerMenu">';
        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-target="#beerMenu" style="text-decoration:none;color:black" >' . $beer[ 'beer_name' ] . '<b class="caret"></b> </a>';
        echo '<ul class="dropdown-menu">';
        $s1 = base_url( "log/beer/" . $beer[ 'beer_id' ] );
        echo '<li><h5>' . anchor( $s1, "Edit Beer Info" ) . '</h5></li>';
        echo '</ul>';
        echo '</h1>';
    } else {
        echo '<h1>' . $beer[ 'beer_name' ] . '</h1>';
    }
?> 
<p>
    <b>Brewed By:</b>
    <p>
    <?php
        $breweryName = $brewery[ 'full_name' ];
        $s1 = base_url( "beer/info/" . $brewery[ 'brewery_id' ] );
        $nameAnchor = anchor( $s1, $breweryName );
        echo $nameAnchor;
    ?>
    </p>
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
</p>
<p>
    <?php echo "<b>" . "Style:" ."</b>" ?>
    <?php echo "<br>" ?>
    <?php
        $style = $beer[ 'substyle_name' ];
        $s1 = base_url( "beer/styles/" . $beer[ 'family_id' ] . "/" . $beer[ 'style_id' ] . "/" . $beer[ 'substyle_id' ] );
        $ssAnchor = anchor( $s1, $style );
        $abvF  = (float)$beer[ 'beer_abv' ];
        $abvS  = $abvF == 0.0 ? '<unknown>' : sprintf( '%.2f%%', $abvF );
        echo $ssAnchor . " (" . $abvS . " ABV)";
    ?>
</p>
<p>
    <?php echo "<b>" . "BA Rating:" ."</b>" ?>
    <?php echo "<br>" ?>
    <?php
        $ba = $beer[ 'beer_ba_rating' ];
        $ba = $ba == NULL ? '-' : $ba;
        echo $ba;
    ?>
</p>
<h2> Logged Drinks </h2>
<?php
    if( $drinkLog != false ) {
        $tmpl = array(
            'table_open' => '<table class="table table-bordered">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Date', 'Person', 'Serving', 'Rating', 'Notes' );
        foreach( $drinkLog as $log ) {
            $this->table->add_row( $log[ 'date' ], $log[ 'display_name' ], $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
        }
        echo $this->table->generate();
    } else {
        echo 'No one has logged this beer yet';
    }
?>