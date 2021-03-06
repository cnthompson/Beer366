<?php
    // Authentication result
    $auth = $this->authenticator->check_auth();

    // URLs used later on the page
    $edit_url = base_url( "log/brewery/" . $brewery[ 'brewery_id' ] . '/' );
    $add_url  = base_url( "log/beer/" . $brewery[ 'brewery_id' ] . '/b/'  );
?>
<div class="page-header">
    <h1> <?php echo $brewery[ 'name' ]; ?> <small> <?php echo $brewery['full_name']; ?> </small></h1>
</div>
<?php if( $auth ): ?>
    <div class="btn-group">
        <?php echo anchor( $add_url,  "<i class='icon-plus'></i> Add Beer", array( 'class' => 'btn' ) ); ?>
        <?php echo anchor( $edit_url, "<i class='icon-pencil'></i> Edit Brewery", array( 'class' => 'btn' ) ); ?>
    </div>
<?php endif; ?>
<div class="row">
    <div class="span4">
        <address>
            <?php echo $brewery[ 'street' ] ?>
            <br>
            <?php
                $cityBase = 'beer/location/' . $brewery[ 'continent_id' ] . '/' . $brewery[ 'subcontinent_id' ] . '/' . $brewery[ 'country' ] . '/';
                if( isset( $brewery[ 'region' ] ) ) {
                    $cityBase .= $brewery[ 'region' ] ."/";
                } else {
                    $cityBase .= "0/";
                }
                $cityBase .= rawurlencode( $brewery[ 'city' ] );
                $cityAnchor = anchor( base_url( $cityBase ), $brewery[ 'city' ] );
                $location = $cityAnchor;

                if( isset( $brewery[ 'region' ] ) ) {
                    $rgnBase = 'beer/location/' . $brewery[ 'continent_id' ] . '/' . $brewery[ 'subcontinent_id' ] . '/' . $brewery[ 'country' ] . '/' .$brewery[ 'region' ] . '/';
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
                $base = 'beer/location/' . $brewery[ 'continent_id' ] . '/' . $brewery[ 'subcontinent_id' ] . '/' . $brewery[ 'country' ] . '/';
                $anchor = anchor( base_url( $base ), $brewery[ 'country_name' ] );
                echo $anchor;
            ?>
        </address>
    </div>
    <?php
        $homepage = $brewery[ 'homepage' ];
        if( $homepage != null && strlen( $homepage ) > 0 ) {
            echo '<div class="span4">';
            echo '<i class="icon-home"></i>';
            echo anchor( $brewery[ 'homepage' ], "Website", 'target="_blank" title="' . $brewery[ 'full_name' ] . '"' );
            echo "</div>";
        }
    ?>
</div>
<p>
    <?php
        if( $brewery[ 'notes' ] != null and strlen( $brewery[ 'notes' ] ) > 0 ) {
            echo '<b>Notes:</b> ' . $brewery[ 'notes' ];
        }
    ?>
<section id="beers">
<h2>Beers</h2>
    <?php
        $tmpl = array(
            'table_open' => '<table class="table table-bordered sortable">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Beer', 'Style', 'ABV', 'BA Rating', 'I\'ve Had' );
        foreach( $beers as $beer ) {
            $name  = $beer[ 'beer_name' ];
            $s1 = base_url( "beer/info/" . $brewery[ 'brewery_id' ] . "/" . $beer[ 'beer_id' ] );
            $nameAnchor = anchor( $s1, $name );

            $style = $beer[ 'substyle_name' ];
            $s1 = base_url( "beer/styles/" . $beer[ 'family_id' ] . "/" . $beer[ 'style_id' ] . "/" . $beer[ 'substyle_id' ] );
            $ssAnchor = anchor( $s1, $style );

            $abvF  = (float)$beer[ 'beer_abv' ];
            $abvS  = $abvF == 0.0 ? '<unknown>' : sprintf( '%.2f%%', $abvF );
            $ba    = $beer[ 'beer_ba_rating' ];
            $ba    = $ba == NULL ? '-' : $ba;

            $haveHad = is_numeric( $beer[ 'have_had' ] ) ? 'X' : '';
            $inMyCellar = is_numeric( $beer[ 'in_my_cellar' ] ) ? 'C' : '';

            $this->table->add_row( $nameAnchor, $ssAnchor, $abvS, $ba, $haveHad . $inMyCellar );
        }
        echo $this->table->generate();
    ?>
</section>
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>' ;
?>