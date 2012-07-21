<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title>Beer Profile - " . $brewery[ 'name' ] . " | " . $beer[ 'beer_name' ] . "</title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php echo "<h1>" . $beer[ 'beer_name' ] . "</h1>" ?>
<p>
    <?php echo "<b>" . "Brewed By:" ."</b>" ?>
    <?php echo "<br>" ?>
    <?php
        $breweryName = $brewery[ 'full_name' ];
        $s1 = base_url( "index.php/beer/info/" . $brewery[ 'brewery_id' ] );
        $nameAnchor = anchor( $s1, $breweryName );
        echo $nameAnchor;
    ?>
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
        $location = $cityAnchor . ", ";

        if( isset( $brewery[ 'region' ] ) ) {
            $rgnBase = "index.php/beer/location/" . $brewery[ 'country' ] . "/" .$brewery[ 'region' ];
            $rgnAnchor = anchor( base_url( $rgnBase ), $brewery[ 'rgn_name' ] );
            $location .= $rgnAnchor . ", ";
        }

        $countryBase = "index.php/beer/location/" . $brewery[ 'country' ];
        $countryAnchor = anchor( base_url( $countryBase ), $brewery[ 'country_name' ] );
        $location .= $countryAnchor;

        echo $location;
    ?>

</p>
<p>
    <?php echo "<b>" . "Style:" ."</b>" ?>
    <?php echo "<br>" ?>
    <?php
        $style = $beer[ 'substyle_name' ];
        $s1 = base_url( "index.php/beer/styles/" . $beer[ 'family_id' ] . "/" . $beer[ 'style_id' ] . "/" . $beer[ 'substyle_id' ] );
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
<?php echo "<h2> Logged Drinks </h2>" ?>
<?php
    if( $drinkLog != false ) {
        $this->table->set_heading( 'Date', 'Person', 'Serving', 'Rating', 'Notes' );
        foreach( $drinkLog as $log ) {
            $this->table->add_row( $log[ 'date' ], $log[ 'display_name' ], $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
        }
        echo $this->table->generate();
    } else {
        echo 'No one has logged this beer yet';
    }
?>
</body>
</html>