<h1>All Breweries</h1>
<?php
$currentLetter = '';
?>
<?php
foreach( $breweries as $brewery ):
?>
    <?php
    if( strtoupper( substr( $brewery['name'], 0, 1 ) ) != $currentLetter ):
        $currentLetter = strtoupper( substr( $brewery['name'], 0, 1 ) );
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