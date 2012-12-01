<div class="page-header">
    <h1><?php echo $this->authenticator->get_display_name() ?>'s Unique Beers</h1>
</div>

<ul>
<?php
    $cellarBeers = array();
    foreach( $uniques as $beer ) {
        if( $beer[ 'cellar' ] == 0 ) {
            echo $beer[ 'beerC' ];
            echo "<br>";
        } else {
            array_push( $cellarBeers, $beer );
        }
    }
    if( count( $cellarBeers ) > 0 ) {
        echo '<br><h2>Still In the Cellar</h2>';
        foreach( $cellarBeers as $beer ) {
            echo $beer[ 'beerC' ];
            echo "<br>";
        }
    }
?>
</ul>
