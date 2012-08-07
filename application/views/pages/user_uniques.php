<div class="page-header">
    <h1><?php echo $_SESSION[ 'displayname' ] ?>'s Unique Beers</h1>
</div>

<ul>
<?php
    if( $userID > 0 ) {
		foreach( $uniques as $beer ) {
			echo $beer[ 'beerC' ];
			echo "</br>";
		}
	} else {
		echo "Meow!";
	}
?>
</ul>
