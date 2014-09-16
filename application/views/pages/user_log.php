<div class="page-header">
    <?php
        if( $user == null ) {
            echo '<h1>All Logged Drinks</h1>';
        } else if( $this->authenticator->is_current_user( $user[ 'user_id' ] ) ) {
            echo '<h1>My Logged Drinks</h1>';
        } else {
            echo '<h1>' . $user[ 'display_name' ] . '\'s Logged Drinks</h1>';
        }
    ?>
</div>
<?php
    $tmpl = array (
        'table_open' => '<table class="table table-bordered sortable">'
    );
    $this->table->set_template( $tmpl );
    if( $user == null ) {
        $this->table->set_heading( 'Date', 'Person', 'Beer', 'Brewery', 'Style', 'Size', 'Rating', 'Notes', 'Unique?' );
    } else if( $this->authenticator->is_current_user( $user[ 'user_id' ] ) ) {
        $this->table->set_heading( '', 'Date', 'Beer', 'Brewery', 'Style', 'Size', 'Rating', 'Notes', 'Unique?' );
    } else {
        $this->table->set_heading( 'Date', 'Beer', 'Brewery', 'Style', 'Size', 'Rating', 'Notes', 'Unique?' );
    }
    foreach( $loggedBeers as $beer ) {
        $beerBase = base_url( "beer/info/" . $beer[ 'brewery_id' ] . "/" . $beer[ 'beer_id' ] . '/' );
        $beerAnchor = anchor( $beerBase, $beer[ 'beer_name' ] );
        $brewerBase = base_url( "beer/info/" . $beer[ 'brewery_id' ] . '/' );
        $brewerAnchor = anchor( $brewerBase, $beer[ 'brewery_name' ] );
        $styleBase = base_url( "beer/styles/" . $beer[ 'family_id' ] . '/' . $beer[ 'style_id' ] . '/' . $beer[ 'substyle_id' ] . '/' );
        $styleAnchor = anchor( $styleBase, $beer[ 'substyle_name' ] );
        $editAnchor    = anchor( base_url( 'log/drink/' . $beer[ 'log_id' ] . '/l/' ), '<i class="icon-pencil"></i>', array( 'title' => 'Edit' ) );
        if( $user == null ) {
            $this->table->add_row( $beer[ 'date' ], $beer[ 'display_name' ], $beerAnchor, $brewerAnchor, $styleAnchor, $beer[ 'ssize' ], $beer[ 'rating' ], $beer[ 'notes' ], $beer[ 'original' ] == null ? 'X' : '' );
        } else if( $this->authenticator->is_current_user( $user[ 'user_id' ] ) ) {
            $this->table->add_row( $editAnchor, $beer[ 'date' ], $beerAnchor, $brewerAnchor, $styleAnchor, $beer[ 'ssize' ], $beer[ 'rating' ], $beer[ 'notes' ], $beer[ 'original' ] == null ? 'X' : '' );
        } else {
            $this->table->add_row( $beer[ 'date' ], $beerAnchor, $brewerAnchor, $styleAnchor, $beer[ 'ssize' ], $beer[ 'rating' ], $beer[ 'notes' ], $beer[ 'original' ] == null ? 'X' : '' );
        }
    }
    echo $this->table->generate();
?>
<?php
    $source = base_url( "/js/" );
    echo '<script type="text/javascript" src="' . $source . '/sorttable.js"></script>' ;
?>
