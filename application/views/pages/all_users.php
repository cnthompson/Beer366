<section id="totals">
    <div class="page-header">
        <h1>Totals</h1>
    </div>
    <div>
    <?php
        $tmpl = array (
            'table_open' => '<table class="table table-bordered">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Drinker', 'Total Beers', 'Unique Beers', 'Remaining', 'Percent', 'Finish?' );
        foreach( $allUsers as $user ) {
            $base = base_url( "users/totals/" . $user[ 'user_id' ] );
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
    </div>
</section>
<section id="abv">
    <div class="page-header">
        <h1> Strongest Beers </h1>
    </div>
    <div>
    <?php
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
    </div>
</section>
<section id="uniques">
    <div class="page-header">
        <h1>Unique Beers</h1>
    </div>
    <p>A Globally Unique beer is one that only one user has had.</p>
    <?php
        $this->table->set_heading( 'Person', 'Individual Uniques', 'Global Uniques', 'Percent' );
        foreach( $uniques as $unique ) {
            $userName = $unique[ 'display_name' ];
            $iUnique = $totals[ $unique[ 'user_id' ] ][ 'unique' ];
            $gUnique = $unique[ 'uniques' ];
            $pUnique = $iUnique == 0 ? 0 : ( 100 * $gUnique / $iUnique );
            $this->table->add_row( $userName, $iUnique, $gUnique, sprintf( '%.2f%%', $pUnique ) );
        }
        echo $this->table->generate();
    ?>
</section>
<section id="recent">
    <div class="page-header">
        <h1> Recent Beers </h1>
    </div>
    <div>
    <?php
        $this->table->set_heading( 'Date', 'Person', 'Beer', 'Brewery', 'Serving', 'Rating', 'Notes' );
        foreach( $drinkLog as $log ) {
            $beerBase = base_url( "beer/info/" . $log[ 'brewery_id' ] . "/" . $log[ 'beer_id' ] );
            $beerAnchor = anchor( $beerBase, $log[ 'beer_name' ] );
            $brewerBase = base_url( "beer/info/" . $log[ 'brewery_id' ] );
            $brewerAnchor = anchor( $brewerBase, $log[ 'brewer_name' ] );
            $this->table->add_row( $log[ 'date' ], $log[ 'display_name' ], $beerAnchor, $brewerAnchor, $log[ 'ss_name' ], $log[ 'rating' ], $log[ 'notes' ] );
        }
        echo $this->table->generate();
    ?>
    </div>
</section>