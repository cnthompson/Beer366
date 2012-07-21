<h1>All Breweries</h1>
<p>
    <?php
        $this->table->set_heading( 'Brewery' );
        foreach( $breweries as $brewery ) {
            $s1 = base_url( "index.php/beer/info/" . $brewery[ 'brewery_id' ] );
            $s2 = anchor( $s1, $brewery[ 'name' ] );
            $numBeers = " (" . $brewery[ 'num_beers' ] . " beer";
            if( $brewery[ 'num_beers' ] == 1 ) {
                $numBeers .= ")";
            } else {
                $numBeers .= "s)";
            }
            $this->table->add_row( $s2 . $numBeers );
        }
        echo $this->table->generate();
    ?>
</p>