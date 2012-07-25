<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Log Drink</title>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<div class="page-header">
    <h1>Log Drink</h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>

<?php echo form_open( 'log/drink' ); ?>
<p>
    <?php
        echo form_label( 'Date:', 'date' );
        echo form_input( 'date', set_value( 'date', date( 'Y-m-d' ) ), 'id="date"' );

        echo form_label( 'Brewery', 'brewery' );
        echo form_dropdown( 'brewery', $breweries,  set_value( 'brewery', '1' ), 'id="brewery" onChange="changeBrewery( this.options[ this.selectedIndex ].value );"' );
        
        $attributes = array(
            'id' => 'beerlabel'
        );
        echo form_label( 'Beer', 'beer', $attributes );
        echo form_dropdown( 'beer', array(), set_value( 'beer', null ), 'id="beer"' );

        echo form_label( 'Serving Size', 'ssize' );
        echo form_dropdown( 'ssize', $sizes,  set_value( 'ssize', 7 ), 'id="ssize"' );

        echo form_label( 'Rating:', 'rating' );
        echo form_input( 'rating', set_value( 'rating' ), 'id="rating"' );

        echo form_label( 'Notes:', 'notes' );
        echo form_textarea( 'notes', set_value( 'notes' ), 'id="notes"' );
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => 'Log Drink', 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>

<script type="text/javascript">
    <?php
        //First, we'll create a javascript mapping of breweries to beers
        echo 'var $jsBreweryToBeerMap = {};';
        foreach( $brew2beerMap as $breweryID => $beerInfo ) {
            echo '$jsBreweryToBeerMap[ ' . $breweryID . ' ] = new Array();';
            foreach( $beerInfo as $beerID => $beer ) {
                echo '$jsBreweryToBeerMap[ ' . $breweryID . ' ].push(  new BeerObj( ' . $beerID . ', "' . $beer . '", ' . $breweryID . ' ) );';
            }
        }
        
        //Then, we'll trigger an onchange event to initialize the region dropdown
        echo 'document.getElementById( "brewery" ).onchange();' ;
    ?>
    function BeerObj( beerID, beerName, breweryID ) {
        this.beerID = beerID;
        this.beerName = beerName;
        this.breweryID = breweryID;
    }
    function changeBrewery( $curBrewery ) {
        var $jsBeers = $jsBreweryToBeerMap[ $curBrewery ];
        var elem = document.getElementById( "beer" );
        elem.options.length = 0;
        if( $jsBeers.length == 0 ) {
            elem.style.visibility = 'hidden';
            elemLabel.style.visibility = 'hidden';
        } else {
            for( var i = 0; i < $jsBeers.length; i++ ) {
                var opt = document.createElement( 'option' );
                opt.value = $jsBeers[ i ].beerID;
                opt.text = $jsBeers[ i ].beerName;
                elem.options.add( opt );
            }
            elem.style.visibility = 'visible';
            elemLabel.style.visibility = 'visible';
        }
    }
</script>

</body>
</html>
