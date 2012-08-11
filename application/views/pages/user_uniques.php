<div class="page-header">
    <h1><?php echo $this->authenticator->get_display_name() ?>'s Unique Beers</h1>
</div>

<ul>
<?php
    $fridgeBeers = array();
    foreach( $uniques as $beer ) {
        if( $beer[ 'fridge' ] == 0 ) {
            echo $beer[ 'beerC' ];
            echo "</br>";
        } else {
            array_push( $fridgeBeers, $beer );
        }
    }
    if( count( $fridgeBeers ) > 0 ) {
        echo '</br><h2>Still In the Fridge</h2>';
        foreach( $fridgeBeers as $beer ) {
            echo $beer[ 'beerC' ];
            echo "</br>";
        }
    }
?>
</ul>
