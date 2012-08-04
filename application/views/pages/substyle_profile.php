<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title> Beer Sub-Style | " . $style[ 'style_name' ] . " | " . $substyle[ 'substyle_name' ] . "</title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php
    $styleBase = base_url( "beer/styles/" . $family[ 'family_id' ] );
    $styleAnchor = anchor( $styleBase, $style[ 'style_name' ] );
    $substyleBase = base_url( "beer/styles/" . $family[ 'family_id' ] . '/' . $style[ 'style_id' ] );
    $substyleAnchor = anchor( $substyleBase, $substyle[ 'substyle_name' ] );
    echo "<h1>" . $substyleAnchor . " | " . $styleAnchor . "</h1>";
?>
</p>
    <?php echo "<h2> Beers </h2>" ?>
    <?php
        $this->table->set_heading( 'Beer', 'Brewer', 'ABV', 'BA Rating' );
        foreach( $beers as $beer ) {
            $name  = $beer[ 'beer_name' ];
            $s1 = base_url( "beer/info/" . $beer[ 'brewery_id' ] . "/" . $beer[ 'beer_id' ] );
            $nameAnchor = anchor( $s1, $name );

            $brewer = $beer[ 'brewer_name' ];
            $s1 = base_url( "beer/info/" . $beer[ 'brewery_id' ] );
            $brewAnchor = anchor( $s1, $brewer );

            $abvF  = (float)$beer[ 'beer_abv' ];
            $abvS  = $abvF == 0.0 ? '<unknown>' : sprintf( '%.2f%%', $abvF );
            $ba    = $beer[ 'beer_ba_rating' ];
            $ba    = $ba == NULL ? '-' : $ba;

            $this->table->add_row( $nameAnchor, $brewAnchor, $abvS, $ba );
        }
        echo $this->table->generate();
    ?>
</p>

</body>
</html>