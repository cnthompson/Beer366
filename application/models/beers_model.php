<?php

class Beers_Model extends CI_Model {
    function __construct() {
    }

    public function getBeers( $brewerID, $userID, $beerID=0 ) {
        $query = $this
            ->db
            ->select( 'beers.*, beer_sub_style.substyle_name, beer_style.style_id, beer_style.family_id, drink_log.log_id AS have_had, fridge.id AS in_my_fridge' )
            ->from( 'beers' )
            ->join( 'beer_sub_style', 'beers.substyle_id = beer_sub_style.substyle_id' )
            ->join( 'beer_style', 'beer_sub_style.style_id = beer_style.style_id' )
            ->join( 'drink_log', 'beers.beer_id = drink_log.beer_id AND drink_log.user_id = ' . $userID, 'left' )
            ->join( 'fridge', 'beers.beer_id = fridge.beer_id AND fridge.user_id = ' . $userID, 'left' )
            ->order_by( 'beers.beer_name', 'asc' )
            ->group_by( 'beers.beer_id' );
        if( $brewerID <= 0 ) {
            if( $beerID > 0 ) {
                $query = $this
                    ->db
                    ->where( 'beers.beer_id', $beerID );
            }
            $query = $this
                ->db
                ->get();
        } else if( $beerID <= 0 ) {
            $query = $this
                ->db
                ->where( 'beers.brewery_id', $brewerID )
                ->get();
        } else {
            $query = $this
                ->db
                ->where( 'beers.brewery_id', $brewerID )
                ->where( 'beers.beer_id', $beerID )
                ->get();
        }
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getBeersBySubStyle( $substyleID ) {
        $query = $this
            ->db
            ->select( 'beers.*, breweries.full_name AS brewer_name' )
            ->from( 'beers' )
            ->join( 'breweries', 'breweries.brewery_id = beers.brewery_id', 'inner' )
            ->order_by( 'beers.beer_name', 'asc' )
            ->where( 'beers.substyle_id', $substyleID )
            ->get();
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }


    public function getBeersByRating( $userID, $rating ) {
        $query = $this
            ->db
            ->select( 'beers.beer_name, beers.beer_id, breweries.name AS brewery_name, beers.brewery_id' )
            ->from( 'drink_log' )
            ->join( 'beers', 'beers.beer_id = drink_log.beer_id', 'inner' )
            ->join( 'breweries', 'beers.brewery_id = breweries.brewery_id', 'inner' )
            ->where( 'drink_log.rating', $rating )
            ->group_by( 'drink_log.beer_id' )
            ->order_by( 'beers.beer_name', 'asc' );
        if( $userID > 0 ) {
            $query = $this
                ->db
                ->where( 'drink_log.user_id', $userID );
        }
        $query = $this
            ->db
            ->get();
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function checkIfBeerExistsByNameAndBrewer( $brewerID, $name, $beerID ) {
        $query = $this
            ->db
            ->where( 'brewery_id', $brewerID )
            ->where( "LOWER( beer_name ) = '" . str_replace( "'", "\\'", strtolower( $name ) ) . "'" )
            ->where( "beer_id != " . $beerID )
            ->limit( 1 )
            ->get( 'beers' );
        if( $query->num_rows > 0 ) {
            return true;
        }
        return false;
    }

    public function checkIfPageExistsByBeer( $page, $beerID ) {
        $starter = 'http://beeradvocate.com/beer/profile/';
        if( $page != null and ( strncmp( $page, $starter, strlen( $starter ) ) == 0 ) ) {
            $page = substr( $page, strlen( $starter ) );
        }
        $query = $this
            ->db
            ->where( "LOWER( ba_page ) = '" . str_replace( "'", "\\'", strtolower( $page ) ) . "'" )
            ->where( "beer_id != " . $beerID )
            ->limit( 1 )
            ->get( 'beers' );
        if( $query->num_rows > 0 ) {
            return true;
        }
        return false;
    }

    function updateBeer( $id, $name, $brewer, $substyle, $abv, $ba, $bapage ) {
        if( ( $name == null || strlen( $name ) == 0 )
         || ( $brewer <= 0 )
         || ( $substyle <= 0 ) ) {
            return FALSE;
        }
        $starter = 'http://beeradvocate.com/beer/profile/';
        $page = ( $bapage == null || strlen( $bapage ) == 0 ) ? null : $bapage;
        if( $page != null and ( strncmp( $page, $starter, strlen( $starter ) ) == 0 ) ) {
            $page = substr( $page, strlen( $starter ) );
        }
        $data = array (
            'beer_name'     => $name,
            'brewery_id'    => $brewer,
            'substyle_id'   => $substyle,
            'beer_abv'      => ( $abv == null || strlen( $abv ) == 0 ) ? null : $abv,
            'beer_ba_rating'=> ( $ba == null || strlen( $ba ) == 0 ) ? null : $ba,
            'ba_page'       => $page,
        );
        $query = null;
        if( $id > 0 ) {
            //updating
            $query = $this
                ->db
                ->where( 'beer_id', $id )
                ->update( 'beers', $data );
        } else {
            //inserting
            $query = $this
                ->db
                ->insert( 'beers', $data );
        }
        if( $query == 1 ) {
            $query = $this
                ->db
                ->select( 'beer_id' )
                ->where( $data )
                ->get( 'beers' );
            if( $query->num_rows > 0 ) {
                $results = $query->result_array();
                return $results[ 0 ][ 'beer_id' ];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function getServingSizes( $ssize = 0 ) {
        $query = $this
            ->db
            ->select( '*' )
            ->from( 'serving_size' );
        if( $ssize > 0 ) {
            $query = $this
                ->db
                ->where( 'size_id', $ssize );
        }
        $query = $this
            ->db
            ->get();
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }
}

?>