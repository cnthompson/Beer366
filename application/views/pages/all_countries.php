<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>All Countries</title>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php echo "<h1>All Countries</h1>" ?>
<p>
    <?php
        //echo $this->table->generate( $breweries );
        $this->table->set_heading( 'Country' );
        foreach( $countries as $country ) {
            $s1 = base_url( "index.php/beer/location/" . $country[ '3166_1_id' ] );
            $s2 = anchor( $s1, $country[ 'name' ] );
            $numBreweries = " (" . $country[ 'num_brewers' ] . " brewer";
            if( $country[ 'num_brewers' ] == 1 ) {
                $numBreweries .= ")";
            } else {
                $numBreweries .= "s)";
            }
            $this->table->add_row( $s2 . $numBreweries );
        }
        echo $this->table->generate();
    ?>
</p>

</body>
</html>