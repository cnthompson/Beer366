<ul class="breadcrumb">
  <li>
    <?php echo anchor( base_url('beer/location'), 'Countries' ); ?>
  </li>
  <span class="divider">/</span>
  <li>
    <?php echo anchor( base_url( 'beer/location/' . $continent[ 'continent_id' ] . '/' ), $continent[ 'name' ] ); ?>
  </li>
  <span class="divider">/</span>
  <li>
    <?php echo anchor( base_url( 'beer/location/' . $continent[ 'continent_id' ] . '/' . $subcontinent[ 'subcontinent_id' ] . '/' ), $subcontinent[ 'name' ] ); ?>
  </li>
  <span class="divider">/</span>
  <li>
    <?php echo anchor( base_url( 'beer/location/' . $continent[ 'continent_id' ] . '/' . $subcontinent[ 'subcontinent_id' ] . '/' . $country[ '3166_1_id' ] . '/' ), $country[ 'name' ] ); ?>
  </li>
  <span class="divider">/</span>
  <?php
    if( $region != null ) {
        echo '<li>';
        echo anchor( base_url( 'beer/location/' . $continent[ 'continent_id' ] . '/' . $subcontinent[ 'subcontinent_id' ] . '/' . $country[ '3166_1_id' ] . '/' . $region[ '3166_2_id' ] . '/' ), $region[ 'rgn_name' ] );
        echo '</li>';
        echo '<span class="divider">/</span>';
    }
  ?>
  <li>
    <?php echo $city[ 'city' ]; ?>
  </li>
</ul>
</p>
    <h2><?php echo $city[ 'city' ] . ' Breweries' ?></h2>
    <ul>
    <?php
        foreach( $breweries as $brewery ) {
            echo '<li>';
            echo anchor( base_url( "beer/info/" . $brewery[ 'brewery_id' ] ), $brewery[ 'full_name' ] );
            echo '</li>';
        }
    ?>
    </ul>
</p>