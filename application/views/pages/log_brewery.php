<div class="page-header">
    <h1>Log Brewery</h1>
</div>

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
        echo form_dropdown( 'country', $countries, $lastCountry ); //, 'onChange="changeCountry( this.options[ this.selectedIndex ].value, 1 );"' );

        echo form_label( 'Region', 'region' );
        echo form_dropdown( 'region', $regions, $lastRegion );

        echo form_fieldset_close();
    ?>
</p>
<p>
    <?php
        echo form_fieldset( 'Miscellaneous' );

        echo form_label( 'Web Page:', 'homepage' );
        echo form_input( 'homepage', set_value( 'homepage' ), 'id="homepage"' );

        echo form_label( 'Brewery Type', 'brewerytype' );
        echo form_dropdown( 'brewerytype', $breweryTypes, $lastBreweryType );

        echo form_label( 'Notes:', 'notes' );
        echo form_textarea( 'notes', set_value( 'notes' ), 'id="notes"' );

        echo form_fieldset_close();
    ?>
</p>
<p>
    <?php echo form_submit( 'submit', 'Log Brewery' ) ?>
</p>
<?php echo form_close(); ?>

</body>
</html>