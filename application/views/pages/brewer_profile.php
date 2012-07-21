<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title>Brewery Profile - " . $brewery[ 'name' ] . "</title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php echo "<h1>" . $brewery[ 'full_name' ] . "</h1>" ?>
<p>
    <?php echo $brewery[ 'street' ] ?>
    <?php echo "<br>" ?>
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
    <?php echo "<br>" ?>
    <?php
        $base = "index.php/beer/location/" . $brewery[ 'country' ] . "/";
        $anchor = anchor( base_url( $base ), $brewery[ 'country_name' ] );
        echo $anchor;
    ?>
    <?php echo "<br>" ?>
</p>
<p>
    <?php
        echo anchor( $brewery[ 'homepage' ], "Website", 'target="_blank" title="' . $brewery[ 'full_name' ] . '"' );
    ?>
</p>
    <?php echo "<h2> Beers </h2>" ?>
    <?php
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
</p>

</body>
</html>