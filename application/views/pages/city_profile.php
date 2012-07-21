<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title>" . $city[ 'city' ] . " | " . ( $region == NULL ? "" : $region[ 'rgn_name' ] . " | " ) . $country[ 'name' ] . "</title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>
<?php echo "<h1>" . $city[ 'city' ] . "</h1>" ?>
<p>
    <?php
        if( $region != NULL ) {
            $s1 = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] . "/" . $region[ '3166_2_id' ] );
            $s2 = anchor( $s1, $region[ 'rgn_name' ] );
            echo $s2;
            echo "<br>";
        }
        $s1 = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] );
        $s2 = anchor( $s1, $country[ 'name' ] );
        echo $s2;
    ?>
</p>
</p>
    <?php echo "<h2> Breweries </h2>" ?>
    <?php
        $this->table->set_heading( 'Brewery' );
        foreach( $breweries as $brewery ) {
            $name  = $brewery[ 'full_name' ];
            $s1 = base_url( "index.php/beer/info/" . $brewery[ 'brewery_id' ] );
            $nameAnchor = anchor( $s1, $name );
            $this->table->add_row( $nameAnchor );
        }
        echo $this->table->generate();
    ?>
</p>

</body>
</html>