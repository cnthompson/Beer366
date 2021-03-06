<section id="totals">
    <h1>Drinker Summary</h1>
    <div>
        <table class="table table-bordered table-hover sortable">
            <tr>
                <th>Drinker</th>
                <th>Total Beers</th>
                <th>Unique Beers</th>
                <th class="hidden-phone">Remaining</th>
                <th class="hidden-phone">Percent</th>
                <th>Finish Date</th>
            </tr>
    <?php
        $tmpl = array (
            'table_open' => '<table class="table table-bordered table-hover sortable">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Drinker', 'Total Beers', 'Unique Beers', 'Remaining', 'Percent', 'Finish?' );
        foreach( $allUsers as $user ) {
            if( ( !isset( $totals[ $user[ 'user_id' ] ] ) )
             || ( $totals[ $user[ 'user_id' ] ][ 'total' ] == 0 ) ) {
                continue;
            }

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
            $daysUntilFinish = $beersPerDay == 0 ? '-' : ( ceil( $remaining / $beersPerDay ) );
            $finishDate = strftime ( "%m/%d/%Y", mktime( 0, 0, 0, date("n"), date("j") + $daysUntilFinish, date("Y") ) );
?>
            <tr>
                <td><?php echo $anchor ?></td>
                <td><?php echo $totalBeer ?></td>
                <td><?php echo $uniqueBeer ?></td>
                <td class="hidden-phone"><?php echo $remaining ?></td>
                <td class="hidden-phone"><?php echo $percent ?></td>
                <td><?php echo $finishDate ?></td>
            </tr>
<?php
        }
    ?>
        </table>
    </div>
</section>
<section id="Strongest Beers">
    <h1>Strongest Beers</h1>
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
<section id="Uniques">
    <h1>Unique Beer Statistics</h1>
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
    <h1>Recent Activity</h1>
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
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>' ;
?>