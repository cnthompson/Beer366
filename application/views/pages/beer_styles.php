<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title> Beer Styles </title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php echo "<h1>Beer Styles</h1>" ?>
<?php
        $map = NULL;
        foreach( $families as $family ) {
            $map[ $family[ 'family_id' ] ] = array();
        }
        foreach( $styles as $style ) {
            if( array_key_exists( $style[ 'family_id' ], $map ) ) {
                $map[ $style[ 'family_id' ] ][] = $style;
            }
        }
        foreach( $families as $family ) {
            echo "<h2>" . $family[ 'family_name' ] ."</h2>";
            foreach( $map[ $family[ 'family_id' ] ] as $style ) {
                $base = base_url( "beer/styles/" . $family[ 'family_id' ] . '/' . $style[ 'style_id' ] );
                $anchor = anchor( $base, $style[ 'style_name' ] );
                echo $anchor;
                echo "<br>";
            }
        }
?>

</body>
</html>