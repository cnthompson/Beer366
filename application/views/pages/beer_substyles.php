<ul class="breadcrumb">
  <li>
    <?php echo anchor( base_url( 'beer/styles/' ), 'All Styles' ); ?>
  </li>
  <span class="divider">/</span>
  <li>
    <?php echo anchor( base_url( 'beer/styles/' . $family[ 'family_id' ] . '/' ), $family[ 'family_name' ] ); ?>
  </li>
  <span class="divider">/</span>
  <li>
    <?php echo $style[ 'style_name' ]; ?>
  </li>
</ul>

<h1><?php echo $style[ 'style_name' ] ?></h1>
<?php
    foreach( $substyles as $substyle ) {
        $base = base_url( "beer/styles/" . $family[ 'family_id' ] . '/' . $style[ 'style_id' ] . '/' . $substyle[ 'substyle_id' ] );
        $anchor = anchor( $base, $substyle[ 'substyle_name' ] );
        echo $anchor;
        echo "<br>";
    }
?>