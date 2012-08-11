<div class="page-header">
    <h1><?php echo $user[ 'display_name' ] ?>'s Profile</h1>
</div>
<h2>Fives</h2>
<ul>
<?php
    foreach( $fives as $five ) {
        $beerBase = base_url( "beer/info/" . $five[ 'brewery_id' ] . "/" . $five[ 'beer_id' ] );
        $beerAnchor = anchor( $beerBase, $five[ 'beer_name' ] );
        $brewerBase = base_url( "beer/info/" . $five[ 'brewery_id' ] );
        $brewerAnchor = anchor( $brewerBase, $five[ 'brewery_name' ] );
        echo '<li>' . $beerAnchor . " [ " . $brewerAnchor . " ]</li>";
    }
?>
</ul>
<h2>Strongest</h2>
<ul>
<?php
    $tmpl = array(
        'table_open' => '<table class="table table-bordered">'
    );
    $this->table->set_template( $tmpl );
    $this->table->set_heading( 'Beer', 'Brewery', 'ABV' );
    foreach( $abv as $a ) {
        $beerBase = base_url( "beer/info/" . $a[ 'brewery_id' ] . "/" . $a[ 'beer_id' ] );
        $beerAnchor = anchor( $beerBase, $a[ 'beer_name' ] );
        $brewerBase = base_url( "beer/info/" . $a[ 'brewery_id' ] );
        $brewerAnchor = anchor( $brewerBase, $a[ 'brewer_name' ] );
        $percent = sprintf( '%.2f%%', $a[ 'beer_abv' ] );
        $this->table->add_row( $beerAnchor, $brewerAnchor, $percent );
    }
    echo $this->table->generate();
?>
</ul>
<?php
    if( $fridgeCount > 0 ) {
        echo '<h2>' . $user[ 'display_name' ] . '\'s Fridge </h2>';
        echo '<ul>';
        echo '<li>';
        $fridgeBase = base_url( 'users/fridge/' . ( $user[ 'user_id' ] == $this->authenticator->get_user_id() ? '' : ( $user[ 'user_id' ] . '/' ) ) );
        $fridgeStr = $fridgeCount . ' beer' . ( $fridgeCount == 1 ? '' : 's' ) . ' in the fridge - ' . ( $tradeCount <= 0 ? 'None for trade.' : ( 'Will trade ' . $tradeCount . '.' ) );
        echo anchor( $fridgeBase, $fridgeStr );
        echo '</li>';
        echo '</ul>';
    }
?>
<h2> Recent Beers </h2>
<?php
    $tmpl = array(
        'table_open' => '<table class="table table-bordered">'
    );
    $this->table->set_template( $tmpl );
    if( $user[ 'user_id' ] == $this->authenticator->get_user_id() ) {
        $this->table->set_heading( '', 'Date', 'Beer', 'Brewery', 'Serving', 'Rating', 'Notes' );
    } else {
        $this->table->set_heading( 'Date', 'Beer', 'Brewery', 'Serving', 'Rating', 'Notes' );
    }
    foreach( $drinkLog as $log ) {
        $beerBase = base_url( "beer/info/" . $log[ 'brewery_id' ] . "/" . $log[ 'beer_id' ] );
        $beerAnchor = anchor( $beerBase, $log[ 'beer_name' ] );
        $brewerBase = base_url( "beer/info/" . $log[ 'brewery_id' ] );
        $brewerAnchor = anchor( $brewerBase, $log[ 'brewer_name' ] );
        $edit_props = array(
            'src' => 'img/pencil.png',
            'alt' => 'Edit',
        );
        $editAnchor    = anchor( base_url( 'log/drink/' . $log[ 'log_id' ] ), img( $edit_props ), array( 'title' => 'Edit' ) );
        if( $user[ 'user_id' ] == $this->authenticator->get_user_id() ) {
            $this->table->add_row( $editAnchor, $log[ 'date' ], $beerAnchor, $brewerAnchor, $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
        } else {
            $this->table->add_row( $log[ 'date' ], $beerAnchor, $brewerAnchor, $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
        }
    }
    echo $this->table->generate();
?>