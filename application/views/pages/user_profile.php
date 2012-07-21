<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title>Profile | " . $user[ 'display_name' ] . "</title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>
<?php echo "<h1>" . $user[ 'display_name' ] . "'s Profile</h1>" ?>

<?php echo "<h2> My Fives </h2>" ?>
<?php
    foreach( $fives as $five ) {
        $beerBase = base_url( "index.php/beer/info/" . $five[ 'brewery_id' ] . "/" . $five[ 'beer_id' ] );
        $beerAnchor = anchor( $beerBase, $five[ 'beer_name' ] );
        $brewerBase = base_url( "index.php/beer/info/" . $five[ 'brewery_id' ] );
        $brewerAnchor = anchor( $brewerBase, $five[ 'brewery_name' ] );
        echo $beerAnchor . " [ " . $brewerAnchor . " ]<br>";
    }
?>

<?php echo "<h2> Recent Beers </h2>" ?>
<?php
    $this->table->set_heading( 'Date', 'Beer', 'Brewery', 'Serving', 'Rating', 'Notes' );
    foreach( $drinkLog as $log ) {
        $beerBase = base_url( "index.php/beer/info/" . $log[ 'brewery_id' ] . "/" . $log[ 'beer_id' ] );
        $beerAnchor = anchor( $beerBase, $log[ 'beer_name' ] );
        $brewerBase = base_url( "index.php/beer/info/" . $log[ 'brewery_id' ] );
        $brewerAnchor = anchor( $brewerBase, $log[ 'brewer_name' ] );
        $this->table->add_row( $log[ 'date' ], $beerAnchor, $brewerAnchor, $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
    }
    echo $this->table->generate();
?>

</body>
</html>