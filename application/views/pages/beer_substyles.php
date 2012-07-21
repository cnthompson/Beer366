<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo "<title> Beer Sub-Styles | " . $style[ 'style_name' ] . "</title>" ?>
    <style>label { display: block; } .errors { color: red;} </style>
</head>
<body>

<?php
    $styleBase = base_url( "index.php/beer/styles/" . $family[ 'family_id' ] . '/' );
    $styleAnchor = anchor( $styleBase, $style[ 'style_name' ] );
    echo "<h1>" . $styleAnchor . "</h1>";
?>
<?php
    foreach( $substyles as $substyle ) {
        $base = base_url( "index.php/beer/styles/" . $family[ 'family_id' ] . '/' . $style[ 'style_id' ] . '/' . $substyle[ 'substyle_id' ] );
        $anchor = anchor( $base, $substyle[ 'substyle_name' ] );
        echo $anchor;
        echo "<br>";
    }
?>

</body>
</html>