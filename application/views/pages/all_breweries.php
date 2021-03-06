<div class="page-header">
    <h1>All Breweries</h1>
</div>
<?php
$currentLetter = '';
?>
<?php
foreach( $breweries as $brewery ):
?>
    <?php
    //$letter = substr( iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $brewery['name'] ), 0, 1 );
    $letter = substr( $brewery['name'], 0, 1 );
    if( ord( $letter ) == 195 ) {
        $letter = 'O';
    } else if( ord( $letter ) == 197 ) {
        $letter = 'Z';
    } else if( is_numeric( $letter ) ) {
        $letter = '#';
    } else if( ( ord( $letter ) < 65 )
            || ( ( ord( $letter ) > 90 )
              && ( ord( $letter ) < 97 ) )
            || ( ( ord( $letter ) > 122 ) ) ) {
        $letter = '*';
    } else {
        $letter = strtoupper( $letter );
    }
    if( $letter != $currentLetter ):
        $currentLetter = $letter;
    ?>
        <a name="<?php echo $currentLetter ?>"></a>
        <div class="span12"><h2><?php echo $currentLetter ?></h2></div>
    <?php
    endif;
    ?>
    <div class="span3">
        <a href="<?php echo base_url( 'beer/info/' . $brewery['brewery_id'] ) ?>"><?php echo $brewery['name'] ?></a>
        &nbsp;(<?php echo $brewery['num_beers'] ?>)
    </div>
<?php
endforeach;
?>