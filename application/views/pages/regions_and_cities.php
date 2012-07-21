<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title>" . $country[ 'name' ] . " | " . ( empty( $regions ) ? "Cities" : "Regions" ) . "</title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php
    if( empty( $regions ) ) {
        $base1 = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] );
        $anchor1 = anchor( $base1, $country[ 'name' ] );
        $header = "<h1>Cities | ";
        if( isset( $region ) ) {
            $base2 = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] ."/" . $region[ '3166_2_id' ] );
            $anchor2 = anchor( $base2, $region[ 'rgn_name' ] );
            $header .= $anchor2 . ", " . $anchor1 . "</h1>";
        } else {
            $header .= $anchor1 . "</h1>";
        }
        echo $header;
        echo "<p>";
        $this->table->set_heading( 'City' );
        foreach( $cities as $city ) {
            $s1 = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] . "/" . ( isset( $region ) ? $region[ '3166_2_id' ] : "0" ) . "/" . rawurlencode( $city[ 'city' ] ) );
            $s2 = anchor( $s1, $city[ 'city' ] );
            $numBreweries = " (" . $city[ 'num_brewers' ] . " brewer";
            if( $city[ 'num_brewers' ] == 1 ) {
                $numBreweries .= ")";
            } else {
                $numBreweries .= "s)";
            }
            $this->table->add_row( $s2 . $numBreweries );
        }
        echo $this->table->generate();
        echo "</p>";
    } else {
        $base = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] );
        $anchor = anchor( $base, $country[ 'name' ] );
        echo "<h1>Regions | " . $anchor . "</h1>";
        echo "<p>";
        $this->table->set_heading( 'Region' );
        foreach( $regions as $region ) {
            $s1 = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] . "/" . $region[ '3166_2_id' ] );
            $s2 = anchor( $s1, $region[ 'rgn_name' ] );
            $numBreweries = " (" . $region[ 'num_brewers' ] . " brewer";
            if( $region[ 'num_brewers' ] == 1 ) {
                $numBreweries .= ")";
            } else {
                $numBreweries .= "s)";
            }
            $this->table->add_row( $s2 . $numBreweries );
        }
        echo $this->table->generate();
        echo "</p>";
    }
    
?>

</body>
</html>