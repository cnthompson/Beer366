<div class="page-header">
    <h1>Beer Sub-Styles</h1>
</div>
<?php
    $styleBase = base_url( "beer/styles/" . $family[ 'family_id' ] . '/' );
    $styleAnchor = anchor( $styleBase, $style[ 'style_name' ] );
    echo "<h1>" . $styleAnchor . "</h1>";
?>
<?php
    foreach( $substyles as $substyle ) {
        $base = base_url( "beer/styles/" . $family[ 'family_id' ] . '/' . $style[ 'style_id' ] . '/' . $substyle[ 'substyle_id' ] );
        $anchor = anchor( $base, $substyle[ 'substyle_name' ] );
        echo $anchor;
        echo "<br>";
    }
?>