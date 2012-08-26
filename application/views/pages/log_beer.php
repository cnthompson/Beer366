<div class="page-header">
    <h1><?php
        $url = 'log/beer/';
        if( $editBeer == null ) {
            $url = 'log/beer/';
            echo 'Add Beer';
        } else if( $editBeer[ 'id' ] == -1 ) {
            $url = 'log/beer/' . $editBeer[ 'brewerID' ] . '/b/';
            echo 'Add Beer';
        } else {
            $url = 'log/beer/' . $editBeer[ 'id' ] . '/';
            echo "Edit Beer - " . $editBeer[ 'brewerN' ] . ': ' . $editBeer[ 'name' ];
        }
    ?></h1>
</div>

<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
<?php if ( $error ): ?>
    <div class="alert alert-error"><?php echo $error ?></div>
<?php endif; ?>

<?php echo form_open( $url ); ?>
<?php echo form_hidden( 'beer_id', $editBeer == null ? -1 : $editBeer[ 'id' ] ); ?>
<p>
    <?php
        $attributes = array(
            'name'  => 'beername',
            'id'    => 'beername',
            'value' => set_value( 'beername', ( $editBeer == null ? null : $editBeer[ 'name' ] ) ),
            'class' => 'span4'
        );
        echo form_label( 'Beer Name:', 'beername' );
        echo form_input( $attributes );
    ?>
</p>
<p>
    <?php
        echo form_label( 'Brewery', 'brewery' );
        echo form_dropdown( 'brewery', $breweries,  set_value( 'brewery', $editBeer == null ? null : $editBeer[ 'brewerID' ] ), 'id="brewery" class="span4"' );
    ?>
</p>
<p>
    <?php
        echo form_label( 'Family', 'family' );
        echo form_dropdown( 'family', $families, set_value( 'family', -1 ), 'id="family" class="span4" onChange="changeFamily( this.options[ this.selectedIndex ].value );"' );

        echo form_label( 'Style', 'style' );
        echo form_dropdown( 'style', $styles, set_value( 'style', -1 ), 'id="style" class="span4" onChange="changeStyle( this.options[ this.selectedIndex ].value );"' );

        echo form_label( 'Sub-Style', 'substyle' );
        echo form_dropdown( 'substyle', $substyles, set_value( 'substyle', $editBeer == null ? null : $editBeer[ 'substyle' ] ), 'id="substyle" class="span4"' );
    ?>
</p>
<p>
    <?php
        $attributes = array(
            'name'   => 'abv',
            'id'     => 'abv',
            'class'  => 'span4',
            'value'  => set_value( 'abv', ( $editBeer == null ? null : $editBeer[ 'abv' ] ) )
        );
        echo form_label( 'ABV (%):', 'abv' );
        echo form_input( $attributes );

        $attributes = array(
            'name'   => 'ba',
            'id'     => 'ba',
            'class'  => 'span4',
            'value'  => set_value( 'ba', ( $editBeer == null ? null : $editBeer[ 'ba' ] ) )
        );
        echo form_label( 'BA Rating:', 'ba' );
        echo form_input( $attributes );

        $attributes = array(
            'name'   => 'bapage',
            'id'     => 'bapage',
            'class'  => 'span4',
            'value'  => set_value( 'bapage', ( $editBeer == null ? null : $editBeer[ 'bapage' ] ) )
        );
        echo form_label( 'BA Page:', 'bapage' );
        echo form_input( $attributes );
    ?>
</p>
<p>
    <?php echo form_submit( array( 'type' => 'submit', 'value' => ( $editBeer == null or $editBeer[ 'id' ] == -1 ) ? 'Add Beer' : 'Update', 'class' => 'btn' ) ) ?>
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
        $jsAllSubStyles.sort( compareSubStyles );
        if( $jsAllSubStyles.length == 0 ) {
            elem.style.visibility = 'hidden';
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
        }
    }

</script>