<div class="page-header">
    <h1>
        <?php echo ( $editCellar == null || $editCellar[ 'id' ] == -1 ) ? "Add Cellar Beer" : "Edit Cellar Beer"; ?>
    </h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>

<?php
    $url = 'log/cellar';
    if( $editCellar != null ) {
        $url = 'log/cellar/' . $editCellar[ 'id' ];
    }
    echo form_open( $url );
?>
<?php echo form_hidden( 'cellar_id', $editCellar == null ? -1 : $editCellar[ 'id' ] ); ?>
    <?php
        echo '<p>';
        $label = 'Brewery:';
        echo form_label( $label, 'brewery' );
        echo form_dropdown( 'brewery', $breweries, set_value( 'brewery', $editCellar == null ? null : $editCellar[ 'brewery' ] ), 'id="brewery" class="span4" onChange="changeBrewery( this.options[ this.selectedIndex ].value );"' );
        echo '</p>';

        echo '<p>';
        $label = 'Beer:';
        echo form_label( $label, 'beer', array( 'id' => 'beerlabel' ) );
        echo form_dropdown( 'beer', array(), set_value( 'beer', $editCellar == null ? null : $editCellar[ 'beer_id' ] ), 'id="beer" class="span4"' );
        echo '</p>';

        echo '<p>';
        $label = 'Serving Size:';
        echo form_label( $label, 'ssize' );
        echo form_dropdown( 'ssize', $sizes,  set_value( 'ssize', $editCellar == null ? 7 : $editCellar[ 'size_id' ] ), 'id="ssize" class="span4"' );
        echo '</p>';

        echo '<p>';
        $label = 'Quantity:';
        echo form_label( $label, 'quantity' );
        echo form_input( 'quantity', set_value( 'quantity', $editCellar == null ? null : $editCellar[ 'quantity' ] ), 'id="quantity" class="span4"' );
        echo '</p>';

        echo '<p>';
        $label = 'Willing to Trade:';
        echo form_label( $label, 'trade' );
        echo form_input( 'trade', set_value( 'trade', $editCellar == null ? null : $editCellar[ 'trade' ] ), 'id="trade" class="span4"' );
        echo '</p>';

        echo '<p>';
        echo form_label( 'Notes:', 'notes' );
        echo form_textarea( 'notes', set_value( 'notes', $editCellar == null ? null : $editCellar[ 'notes' ] ), 'id="notes" class="span4"' );
        echo '</p>';
    ?>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => ( $editCellar == null || $editCellar[ 'id' ] == -1 ) ? 'Add' : 'Update', 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>

<script type="text/javascript">
    <?php
        //First, we'll create a javascript mapping of breweries to beers
        echo 'var $jsBreweryToBeerMap = {};';
        foreach( $brew2beerMap as $breweryID => $beerInfo ) {
            echo '$jsBreweryToBeerMap[ ' . $breweryID . ' ] = new Array();';
            foreach( $beerInfo as $beerID => $beer ) {
                echo '$jsBreweryToBeerMap[ ' . $breweryID . ' ].push(  new BeerObj( ' . $beerID . ', "' . str_replace( '"', '\\"', $beer ) . '", ' . $breweryID . ' ) );';
            }
        }
    ?>

    //Then, we'll trigger an onchange event to initialize the region dropdown
    document.getElementById( "brewery" ).onchange();

    <?php
        // And make sure that the drink we're editing has been selected
        if( $editCellar != null ) {
            echo 'var elem = document.getElementById( "beer" );';
            echo 'for( i = 0; i < elem.options.length; i++ ) {';
            echo '    if( elem.options[ i ].value == "' . $editCellar[ 'beer_id' ] . '") {';
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
        if( typeof $jsBeers === "undefined" ) {
            return;
        }
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
