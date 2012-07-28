<body>

<div class="page-header">
    <h1>
        <?php echo ( $editBeer == null ? "Add Beer" : ( "Edit Beer - " . $editBeer[ 'brewerN' ] . ': ' . $editBeer[ 'name' ] ) ) ; ?>
    </h1>
</div>
<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>


<?php echo form_open( 'log/beer' . ( $editBeer == null ? '' : ( '/' . $editBeer[ 'id' ] ) ) ); ?>
<?php echo form_hidden( 'beer_id', $editBeer == null ? -1 : $editBeer[ 'id' ] ); ?>
<p>
    <?php
        echo form_label( 'Beer Name:', 'beername' );
        echo form_input( 'beername', set_value( 'beername', $editBeer == null ? null : $editBeer[ 'name' ] ), 'id="beername"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'Brewery', 'brewery' );
        echo form_dropdown( 'brewery', $breweries,  set_value( 'brewery', $editBeer == null ? null : $editBeer[ 'brewerID' ] ), 'id="brewery"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'Family', 'family' );
        echo form_dropdown( 'family', $families, set_value( 'family', -1 ), 'id="family" onChange="changeFamily( this.options[ this.selectedIndex ].value );"' );

        echo form_label( 'Style', 'style' );
        echo form_dropdown( 'style', $styles, set_value( 'style', -1 ), 'id="style" onChange="changeStyle( this.options[ this.selectedIndex ].value );"' );

        echo form_label( 'Sub-Style', 'substyle' );
        echo form_dropdown( 'substyle', $substyles, set_value( 'substyle', $editBeer == null ? null : $editBeer[ 'substyle' ] ), 'id="substyle"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'ABV (%):', 'abv' );
        echo form_input( 'abv', set_value( 'abv', $editBeer == null ? null : $editBeer[ 'abv' ] ), 'id="abv"' );

        echo form_label( 'BA Rating:', 'ba' );
        echo form_input( 'ba', set_value( 'ba', $editBeer == null ? null : $editBeer[ 'ba' ] ), 'id="ba"' );
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => $editBeer == null ? 'Add Beer' : 'Update', 'class' => 'btn' ) ) ?>
</p>
<?php echo form_close(); ?>

<script type="text/javascript">
    <?php
        //First, we'll create a javascript mapping of families to styles
        echo 'var $jsFamilyToStylesMap = {};';
        foreach( $family2stylesMap as $familyID => $styleInfo ) {
            echo '$jsFamilyToStylesMap[ ' . $familyID . ' ] = new Array();';
            foreach( $styleInfo as $styleID => $style ) {
                echo '$jsFamilyToStylesMap[ ' . $familyID . ' ].push(  new StyleObj( ' . $styleID . ', "' . $style . '", ' . $familyID . ' ) );';
            }
        }

        //Then create a mapping of styles to sub-styles
        echo 'var $jsStyleToSStylesMap = {};';
        foreach( $style2sstylesMap as $styleID => $sstyleInfo ) {
            echo '$jsStyleToSStylesMap[ ' . $styleID . ' ] = new Array();';
            foreach( $sstyleInfo as $sstyleID => $sstyle ) {
                echo '$jsStyleToSStylesMap[ ' . $styleID . ' ].push(  new SubStyleObj( ' . $sstyleID . ', "' . $sstyle . '", ' . $styleID . ' ) );';
            }
        }

        //Then, we'll trigger an onchange event to initialize the region dropdown
        echo 'document.getElementById( "family" ).onchange();' ;

        //if( $editBeer != null ) {
        //    echo 'document.getElementById( "substyle" ).value = "' . $editBeer[ 'substyle' ] . '";';
        //}
    ?>
    function StyleObj( styleID, styleName, familyID ) {
        this.styleID = styleID;
        this.styleName = styleName;
        this.familyID = familyID;
    }
    function compareStyles( style1, style2 ) {
        if( style1.styleID == -1 ) {
            return -1;
        } else if( style2.styleID == -1 ) {
            return 1;
        } else if( style1.styleName > style2.styleName ) {
            return 1;
        } else if( style2.styleName > style1.styleName ) {
            return -1;
        } else {
            return 0;
        }
    }
    function SubStyleObj( sstyleID, sstyleName, styleID ) {
        this.sstyleID = sstyleID;
        this.sstyleName = sstyleName;
        this.styleID = styleID;
    }
    function compareSubStyles( sstyle1, sstyle2 ) {
        if( sstyle1.sstyleID == -1 ) {
            return -1;
        } else if( sstyle2.sstyleID == -1 ) {
            return 1;
        } else if( sstyle1.sstyleName > sstyle2.sstyleName ) {
            return 1;
        } else if( sstyle2.sstyleName > sstyle1.sstyleName ) {
            return -1;
        } else {
            return 0;
        }
    }
    function changeFamily( $curFamily ) {
        var elem = document.getElementById( "style" );
        var prevValue = elem.options[ elem.selectedIndex ].value;
        elem.options.length = 0;
        $jsAllStyles = new Array();
        if( $curFamily == -1 ) {
            for( $familyID in $jsFamilyToStylesMap ) {
                for( var i = 0; i < $jsFamilyToStylesMap[ $familyID ].length; i++ ) {
                    $jsAllStyles.push( $jsFamilyToStylesMap[ $familyID ][ i ] );
                }
           }
           $jsAllStyles.push( new StyleObj( -1, 'All', -1 ) );
        } else {
            var $jsStyles = $jsFamilyToStylesMap[ $curFamily ];
            for( var i = 0; i < $jsStyles.length; i++ ) {
                $jsAllStyles.push( $jsStyles[ i ] );
            }
        }
        $jsAllStyles.sort( compareStyles );
        if( $jsAllStyles.length == 0 ) {
            elem.onchange();
            elem.style.visibility = 'hidden';
            elemLabel.style.visibility = 'hidden';
        } else {
            for( var i = 0; i < $jsAllStyles.length; i++ ) {
                var opt = document.createElement( 'option' );
                opt.value = $jsAllStyles[ i ].styleID;
                opt.text = $jsAllStyles[ i ].styleName;
                elem.options.add( opt );
            }
            for( i = 0; i < elem.options.length; i++ ) {
                if( elem.options[ i ].value == prevValue ) {
                    elem.options[ i ].selected = true;
                    break;
                }
            }
            elem.onchange();
            elem.style.visibility = 'visible';
            elemLabel.style.visibility = 'visible';
        }
    }

    function changeStyle( $curStyle ) {
        var elem = document.getElementById( "substyle" );
        var prevValue = elem.options[ elem.selectedIndex ].value;
        elem.options.length = 0;
        $jsAllSubStyles = new Array();
        if( $curStyle == -1 ) {
            for( $styleID in $jsStyleToSStylesMap ) {
                for( var i = 0; i < $jsStyleToSStylesMap[ $styleID ].length; i++ ) {
                    $jsAllSubStyles.push( $jsStyleToSStylesMap[ $styleID ][ i ] );
                }
           }
        } else {
            var $jsSStyles = $jsStyleToSStylesMap[ $curStyle ];
            for( var i = 0; i < $jsSStyles.length; i++ ) {
                $jsAllSubStyles.push( $jsSStyles[ i ] );
            }
        }
        $jsAllSubStyles.sort( compareStyles );
        if( $jsAllSubStyles.length == 0 ) {
            elem.style.visibility = 'hidden';
            elemLabel.style.visibility = 'hidden';
        } else {
            for( var i = 0; i < $jsAllSubStyles.length; i++ ) {
                var opt = document.createElement( 'option' );
                opt.value = $jsAllSubStyles[ i ].sstyleID;
                opt.text = $jsAllSubStyles[ i ].sstyleName;
                elem.options.add( opt );
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

</body>
</html>