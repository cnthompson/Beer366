<div class="page-header">
    <h1>
        <?php echo ( $editBrewer == null ? "Add Brewery" : ( "Edit Brewery - " . $editBrewer[ 'name' ] ) ) ; ?>
    </h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>


<?php echo form_open( 'log/brewery' . ( $editBrewer == null ? '' : ( '/' . $editBrewer[ 'id' ] ) ) ); ?>
<?php echo form_hidden( 'brewer_id', $editBrewer == null ? -1 : $editBrewer[ 'id' ] ); ?>
<p>
    <?php
        $attributes = array(
            'name'  => 'shortname',
            'id'    => 'shortname',
            'class' => 'span4',
            'value' => ( $editBrewer == null ? null : $editBrewer[ 'name' ] )
        );
        echo form_label( 'Brewery Name (Friendly Name):', 'shortname' );
        echo form_input( $attributes );
    ?>
</p>
<p>
    <?php
        $attributes = array(
            'name'  => 'fullname',
            'id'    => 'fullname',
            'class' => 'span4',
            'value' => ( $editBrewer == null ? null : $editBrewer[ 'fName' ] )
        );
        echo form_label( 'Full Name:', 'fullname' );
        echo form_input( $attributes );
    ?>
</p>
<p>
    <?php
        echo form_fieldset('Brewery Address');

        $attributes = array(
            'name'  => 'address',
            'id'    => 'address',
            'class' => 'span4',
            'value' => ( $editBrewer == null ? null : $editBrewer[ 'street' ] )
        );
        echo form_label( 'Street Address:', 'address' );
        echo form_input( $attributes );

        $attributes = array(
            'name'  => 'city',
            'id'    => 'city',
            'class' => 'span4',
            'value' => ( $editBrewer == null ? null : $editBrewer[ 'city' ] )
        );
        echo form_label( 'City:', 'city' );
        echo form_input( $attributes );

        $attributes = array(
            'name'  => 'postcode',
            'id'    => 'postcode',
            'class' => 'span4',
            'value' => ( $editBrewer == null ? null : $editBrewer[ 'postal' ] )
        );
        echo form_label( 'Postal Code:', 'postcode' );
        echo form_input( $attributes );

        echo form_label( 'Country', 'country' );
        echo form_dropdown( 'country', $countries,  set_value( 'country', $editBrewer == null ? '226' : $editBrewer[ 'country' ] ), 'id="country" class="span4" onChange="changeCountry( this.options[ this.selectedIndex ].value );"' );

        $attributes = array(
            'id' => 'regionlabel'
        );
        echo form_label( 'Region', 'region', $attributes );
        echo form_dropdown( 'region', array(), set_value( 'region', $editBrewer == null ? null : $editBrewer[ 'region' ] ), 'id="region" class="span4"' );

        echo form_fieldset_close();
    ?>
</p>
<p>
    <?php
        echo form_fieldset( 'Miscellaneous' );

        $attributes = array(
            'name'  => 'homepage',
            'id'    => 'homepage',
            'class' => 'span4',
            'value' => ( $editBrewer == null ? null : $editBrewer[ 'homepage' ] )
        );
        echo form_label( 'Web Page:', 'homepage' );
        echo form_input( $attributes );

        echo form_label( 'Brewery Type', 'brewerytype' );
        echo form_dropdown( 'brewerytype', $breweryTypes, set_value( 'brewerytype', $editBrewer == null ? '1' : $editBrewer[ 'type' ] ), 'class="span4"' );

        $attributes = array(
            'name'  => 'notes',
            'id'    => 'notes',
            'class' => 'span4',
            'value' => ( $editBrewer == null ? null : $editBrewer[ 'notes' ] )
        );
        echo form_label( 'Notes:', 'notes' );
        echo form_textarea( $attributes );

        echo form_fieldset_close();
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => $editBrewer == null ? 'Add Brewery' : 'Update', 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>

<script type="text/javascript">
    <?php
        //First, we'll create a javascript mapping of countries to regions
        echo 'var $jsCountryToRegionMap = {};';
        foreach( $c2rMap as $countryCode => $rgns ) {
            echo '$jsCountryToRegionMap[ ' . $countryCode . ' ] = {};';
            foreach( $rgns as $rgnCode => $rgn ) {
                echo '$jsCountryToRegionMap[ ' . $countryCode . ' ][ ' . $rgnCode . ' ] = \'' . $rgn . '\';';
            }
        }

        //Then, we'll trigger an onchange event to initialize the region dropdown
        echo 'document.getElementById( "country" ).onchange();' ;

        // And make sure that the drink we're editing has been selected
        if( $editBrewer != null ) {
            echo 'var elem = document.getElementById( "region" );';
            echo 'for( i = 0; i < elem.options.length; i++ ) {';
            echo '    if( elem.options[ i ].value == "' . $editBrewer[ 'region' ] . '") {';
            echo '        elem.options[ i ].selected = true;';
            echo '        break;';
            echo '    }';
            echo '}';
        }
    ?>

    function changeCountry( $curCountry ) {
        var $jsRegions = $jsCountryToRegionMap[ $curCountry ];
        var elem = document.getElementById( "region" );
        var prevValue = '';
        if( elem.selectedIndex >= 0 ) {
            prevValue = elem.options[ elem.selectedIndex ].value;
        }
        var elemLabel = document.getElementById( "regionlabel" );
        if( $curCountry == 226 ) {
            elemLabel.text = 'State';
        } else {
            elemLabel.InnerHTML = 'Region4';
        }
        elem.options.length = 0;
        if( Object.keys( $jsRegions ).length == 0 ) {
            elem.style.visibility = 'hidden';
            elemLabel.style.visibility = 'hidden';
        } else {
            for( var $key in Object.keys( $jsRegions ) ) {
                if( $jsRegions.hasOwnProperty( $key ) ) {
                    var opt = document.createElement( 'option' );
                    opt.value = $key;
                    opt.text = $jsRegions[ $key ];
                    elem.options.add( opt );
                }
            }
            for( i = 0; i < elem.options.length; i++ ) {
                if( elem.options[ i ].value == prevValue ) {
                    elem.options[ i ].selected = true;
                    break;
                }
            }
            elem.style.visibility = 'visible';
            elemLabel.style.visibility = 'visible';
        }
    }

</script>