<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>All Drinkers</title>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php echo "<h1>Totals</h1>" ?>
<p>
    <?php
        $this->table->set_heading( 'Drinker', 'Total Beers', 'Unique Beers', 'Remaining', 'Percent', 'Finish?' );
        foreach( $allUsers as $user ) {
            $base = base_url( "index.php/users/totals/" . $user[ 'user_id' ] );
            $anchor = anchor( $base, $user[ 'display_name' ] );

            $totalBeer = $totals[ $user[ 'user_id' ] ][ 'total' ];
            $uniqueBeer = $totals[ $user[ 'user_id' ] ][ 'unique' ];

            $remaining = ( 366 * ( floor( $uniqueBeer / 366 ) + 1 ) ) - $uniqueBeer;
            $percent = sprintf( '%.2f%%', ( 100 * $uniqueBeer / ( 366 * ( FLOOR( $uniqueBeer / 366 ) + 1 ) ) ) );

            $now = time();
            $start = strtotime( "2012-01-01" );
            $datediff = $now - $start;
            $daysSinceStart = floor( $datediff / ( 60 * 60 * 24 ) ) + 1;
            $beersPerDay = $uniqueBeer / $daysSinceStart;
            $daysUntilFinish = ceil( $remaining / $beersPerDay );
            $finishDate = strftime ( "%m/%d/%Y", mktime( 0, 0, 0, date("n"), date("j") + $daysUntilFinish, date("Y") ) ); 
            
            $this->table->add_row( $anchor, $totalBeer, $uniqueBeer, $remaining, $percent, $finishDate );
        }
        echo $this->table->generate();
    ?>
</p>

<?php echo "<h2> Recent Beers </h2>" ?>
<?php
    $this->table->set_heading( 'Date', 'Person', 'Beer', 'Brewery', 'Serving', 'Rating', 'Notes' );
    foreach( $drinkLog as $log ) {
        $beerBase = base_url( "index.php/beer/info/" . $log[ 'brewery_id' ] . "/" . $log[ 'beer_id' ] );
        $beerAnchor = anchor( $beerBase, $log[ 'beer_name' ] );
        $brewerBase = base_url( "index.php/beer/info/" . $log[ 'brewery_id' ] );
        $brewerAnchor = anchor( $brewerBase, $log[ 'brewer_name' ] );
        $this->table->add_row( $log[ 'date' ], $log[ 'display_name' ], $beerAnchor, $brewerAnchor, $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
    }
    echo $this->table->generate();
?>

</body>
</html>