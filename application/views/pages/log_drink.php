<div class="page-header">
    <h1>
        <?php echo ( $editDrink == null ? "Log Drink" : "Edit Logged Drink" ); ?>
    </h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>


<?php echo form_open( 'log/drink' . ( $editDrink == null ? '' : ( '/' . $editDrink[ 'id' ] ) ) ); ?>
<?php echo form_hidden( 'drink_id', $editDrink == null ? -1 : $editDrink[ 'id' ] ); ?>
<p>
    <?php
        echo form_label( 'Date:', 'date' );
        echo form_input( 'date', set_value( 'date', $editDrink == null ? date( 'Y-m-d' ) : $editDrink[ 'date' ] ), 'id="date" class="span4"' );

        echo form_label( 'Brewery', 'brewery' );
        echo form_dropdown( 'brewery', $breweries, set_value( 'brewery', $editDrink == null ? null : $editDrink[ 'brewery' ] ), 'id="brewery" class="span4" onChange="changeBrewery( this.options[ this.selectedIndex ].value );"' );

        $attributes = array(
            'id' => 'beerlabel'
        );
        echo form_label( 'Beer', 'beer', $attributes );
        echo form_dropdown( 'beer', array(), set_value( 'beer', $editDrink == null ? null : $editDrink[ 'beer_id' ] ), 'id="beer" class="span4"' );

        echo form_label( 'Serving Size', 'ssize' );
        echo form_dropdown( 'ssize', $sizes,  set_value( 'ssize', $editDrink == null ? 7 : $editDrink[ 'size_id' ] ), 'id="ssize" class="span4"' );

        echo form_label( 'Rating:', 'rating' );
        echo form_input( 'rating', set_value( 'rating', $editDrink == null ? null : $editDrink[ 'rating' ] ), 'id="rating" class="span4"' );

        echo form_label( 'Notes:', 'notes' );
        echo form_textarea( 'notes', set_value( 'notes', $editDrink == null ? null : $editDrink[ 'notes' ] ), 'id="notes" class="span4"' );
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => ( $editDrink == null ? 'Log Drink' : 'Update' ), 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.22.custom.min.js"></script>
<script type="text/javascript">
    $( "#date" ).datepicker( { dateFormat: "yy-mm-dd" } );

    <?php
        //First, we'll create a javascript mapping of breweries to beers
        echo 'var $jsBreweryToBeerMap = {};';
        foreach( $brew2beerMap as $breweryID => $beerInfo ) {
            echo '$jsBreweryToBeerMap[ ' . $breweryID . ' ] = new Array();';
            foreach( $beerInfo as $beerID => $beer ) {
                echo '$jsBreweryToBeerMap[ ' . $breweryID . ' ].push(  new BeerObj( ' . $beerID . ', "' . $beer . '", ' . $breweryID . ' ) );';
            }
        }
    ?>

    //Then, we'll trigger an onchange event to initialize the region dropdown
    document.getElementById( "brewery" ).onchange();

    <?php
        // And make sure that the drink we're editing has been selected
        if( $editDrink != null ) {
            echo 'var elem = document.getElementById( "beer" );';
            echo 'for( i = 0; i < elem.options.length; i++ ) {';
            echo '    if( elem.options[ i ].value == "' . $editDrink[ 'beer_id' ] . '") {';
            echo '        elem.options[ i ].selected = true;';
            echo '        break;';
            echo '    }';
            echo '}';
        }
    ?>

    function BeerObj( beerID, beerName, breweryID ) {
        this.beerID = beerID;
        this.beerName = beerName;
        this.breweryID = breweryID;
    }
    function changeBrewery( $curBrewery ) {
        var $jsBeers = $jsBreweryToBeerMap[ $curBrewery ];
        var elem = document.getElementById( "beer" );
        var prevValue = '';
        if( elem.selectedIndex >= 0 ) {
            prevValue = elem.options[ elem.selectedIndex ].value;
        }
        elem.options.length = 0;
        if( $jsBeers.length > 0 ) {
            for( var i = 0; i < $jsBeers.length; i++ ) {
                var opt = document.createElement( 'option' );
                opt.value = $jsBeers[ i ].beerID;
                opt.text = $jsBeers[ i ].beerName;
                elem.options.add( opt );
            }
            for( i = 0; i < elem.options.length; i++ ) {
                if( elem.options[ i ].value == prevValue ) {
                    elem.options[ i ].selected = true;
                    break;
                }
            }
        }
    }
</script>