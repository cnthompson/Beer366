<h1>All Breweries</h1>
<?php
$currentLetter = '';
?>
<?php
foreach( $breweries as $brewery ):
?>
    <?php
    $letter = strtoupper( substr( iconv( 'UTF-8', 'ASCII//TRANSLIT//IGNORE', $brewery['name'] ), 0, 1 ) );
    if( is_numeric( $letter ) ) {
        $letter = '#';
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