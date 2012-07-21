<div class="page-header">
    <h1><?php echo $user[ 'display_name' ] ?>'s Profile</h1>
</div>
<h2>Fives</h2>
<ul>
<?php
    foreach( $fives as $five ) {
        $beerBase = base_url( "index.php/beer/info/" . $five[ 'brewery_id' ] . "/" . $five[ 'beer_id' ] );
        $beerAnchor = anchor( $beerBase, $five[ 'beer_name' ] );
        $brewerBase = base_url( "index.php/beer/info/" . $five[ 'brewery_id' ] );
        $brewerAnchor = anchor( $brewerBase, $five[ 'brewery_name' ] );
        echo '<li>' . $beerAnchor . " [ " . $brewerAnchor . " ]</li>";
    }
?>
</ul>
<h2> Recent Beers </h2>
<?php
    $tmpl = array(
        'table_open' => '<table class="table table-bordered">'
    );
    $this->table->set_template( $tmpl );
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