<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Brewery</title>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<div class="page-header">
    <h1>Add Brewery</h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>

<?php echo form_open( 'log/brewery' ); ?>
<p>
    <?php
        echo form_label( 'Brewery Name (Friendly Name):', 'shortname' );
        echo form_input( 'shortname', set_value( 'shortname' ), 'id="shortname"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'Full Name:', 'fullname' );
        echo form_input( 'fullname', set_value( 'fullname' ), 'id="fullname"' );
    ?>
</p>
<p>
    <?php
        echo form_fieldset('Brewery Address');

        echo form_label( 'Street Address:', 'address' );
        echo form_input( 'address', set_value( 'address' ), 'id="address"' );

        echo form_label( 'City:', 'city' );
        echo form_input( 'city', set_value( 'city' ), 'id="city"' );

        echo form_label( 'Postal Code:', 'postcode' );
        echo form_input( 'postcode', set_value( 'postcode' ), 'id="postcode"' );

        echo form_label( 'Country', 'country' );
        echo form_dropdown( 'country', $countries,  set_value( 'country', '226' ), 'id="country" onChange="changeCountry( this.options[ this.selectedIndex ].value );"' );

        $attributes = array(
            'id' => 'regionlabel'
        );
        echo form_label( 'Region', 'region', $attributes );
        echo form_dropdown( 'region', array(), set_value( 'region', null ), 'id="region"' );

        echo form_fieldset_close();
    ?>
</p>
<p>
    <?php
        echo form_fieldset( 'Miscellaneous' );

        echo form_label( 'Web Page:', 'homepage' );
        echo form_input( 'homepage', set_value( 'homepage' ), 'id="homepage"' );

        echo form_label( 'Brewery Type', 'brewerytype' );
        echo form_dropdown( 'brewerytype', $breweryTypes, set_value( 'brewerytype', '1' ) );

        echo form_label( 'Notes:', 'notes' );
        echo form_textarea( 'notes', set_value( 'notes' ), 'id="notes"' );

        echo form_fieldset_close();
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => 'Add Brewery', 'class' => 'btn' ) ) ?>
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
    ?>
    function changeCountry( $curCountry ) {
        var $jsRegions = $jsCountryToRegionMap[ $curCountry ];
        var elem = document.getElementById( "region" );
        var elemLabel = document.getElementById( "regionlabel" );
        if( $curCountry == 226 ) {
            elemLabel.InnerHTML = 'State';
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
            elem.style.visibility = 'visible';
            elemLabel.style.visibility = 'visible';
        }
    }

</script>

</body>
</html>
