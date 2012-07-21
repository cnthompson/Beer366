<?php

class Beers_Model extends CI_Model {
    function __construct() {
    }

    public function getBeers( $brewerID, $beerID=0 ) {
        $query = $this
            ->db
            ->select( 'beers.*, beer_sub_style.substyle_name, beer_style.style_id, beer_style.family_id' )
            ->from( 'beers' )
            ->join( 'beer_sub_style', 'beers.substyle_id = beer_sub_style.substyle_id' )
            ->join( 'beer_style', 'beer_sub_style.style_id = beer_style.style_id' )
            ->order_by( 'beers.beer_name', 'asc' );
        if( $brewerID <= 0 ) {
            $query = $this
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
            return false;
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
            return false;
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
            return false;
        }
    }

}

?>