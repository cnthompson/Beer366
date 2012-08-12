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
    <?php echo anchor( base_url( 'beer/styles/' . $family[ 'family_id' ] . '/' . $style[ 'style_id' ] . '/' ), $style[ 'style_name' ] ); ?>
  </li>
  <span class="divider">/</span>
  <li>
    <?php echo $substyle[ 'substyle_name' ]; ?>
  </li>
</ul>
<h2>Beers</h2>
</p>
    <?php
        $tmpl = array(
            'table_open' => '<table class="table table-bordered sortable">'
        );
        $this->table->set_template( $tmpl );
        $this->table->set_heading( 'Beer', 'Brewer', 'ABV', 'BA Rating' );
        foreach( $beers as $beer ) {
            $name  = $beer[ 'beer_name' ];
            $s1 = base_url( "beer/info/" . $beer[ 'brewery_id' ] . "/" . $beer[ 'beer_id' ] );
            $nameAnchor = anchor( $s1, $name );

            $brewer = $beer[ 'brewer_name' ];
            $s1 = base_url( "beer/info/" . $beer[ 'brewery_id' ] );
            $brewAnchor = anchor( $s1, $brewer );

            $abvF  = (float)$beer[ 'beer_abv' ];
            $abvS  = $abvF == 0.0 ? '<unknown>' : sprintf( '%.2f%%', $abvF );
            $ba    = $beer[ 'beer_ba_rating' ];
            $ba    = $ba == NULL ? '-' : $ba;

            $this->table->add_row( $nameAnchor, $brewAnchor, $abvS, $ba );
        }
        echo $this->table->generate();
    ?>
</p>
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>' ;
?>
