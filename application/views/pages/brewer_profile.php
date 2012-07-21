<h1><?php echo $brewery[ 'full_name' ] ?></h1>
<address>
    <?php echo $brewery[ 'street' ] ?>
    <br>
    <?php
        $cityBase = "index.php/beer/location/" . $brewery[ 'country' ] . "/";
        if( isset( $brewery[ 'region' ] ) ) {
            $cityBase .= $brewery[ 'region' ] ."/";
        } else {
            $cityBase .= "0/";
        }
        $cityBase .= rawurlencode( $brewery[ 'city' ] );
        $cityAnchor = anchor( base_url( $cityBase ), $brewery[ 'city' ] );
        $location = $cityAnchor;

        if( isset( $brewery[ 'region' ] ) ) {
            $rgnBase = "index.php/beer/location/" . $brewery[ 'country' ] . "/" .$brewery[ 'region' ];
            $rgnAnchor = anchor( base_url( $rgnBase ), $brewery[ 'rgn_name' ] );
            $location .= ", " . $rgnAnchor;
        }

        if( isset( $brewery[ 'postal_code' ] ) ) {
            $location .= " " . $brewery[ 'postal_code' ];
        }

        echo $location;
    ?>
    <br>
    <?php
        $base = "beer/location/" . $brewery[ 'country' ] . "/";
        $anchor = anchor( base_url( $base ), $brewery[ 'country_name' ] );
        echo $anchor;
    ?>
</address>
<p>
    <?php
        echo anchor( $brewery[ 'homepage' ], "Website", 'target="_blank" title="' . $brewery[ 'full_name' ] . '"' );
    ?>
</p>
<section id="beers">
<h2>Beers</h2>
    <?php
        $tmpl = array(
            'table_open' => '<table class="table table-bordered">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Beer', 'Style', 'ABV', 'BA Rating' );
        foreach( $beers as $beer ) {
            $name  = $beer[ 'beer_name' ];
            $s1 = base_url( "index.php/beer/info/" . $brewery[ 'brewery_id' ] . "/" . $beer[ 'beer_id' ] );
            $nameAnchor = anchor( $s1, $name );

            $style = $beer[ 'substyle_name' ];
            $s1 = base_url( "index.php/beer/styles/" . $beer[ 'family_id' ] . "/" . $beer[ 'style_id' ] . "/" . $beer[ 'substyle_id' ] );
            $ssAnchor = anchor( $s1, $style );

            $abvF  = (float)$beer[ 'beer_abv' ];
            $abvS  = $abvF == 0.0 ? '<unknown>' : sprintf( '%.2f%%', $abvF );
            $ba    = $beer[ 'beer_ba_rating' ];
            $ba    = $ba == NULL ? '-' : $ba;

            $this->table->add_row( $nameAnchor, $ssAnchor, $abvS, $ba );
        }
        echo $this->table->generate();
    ?>
</section>