<?php

class Drinkers_Model extends CI_Model {
    function __construct() {
    }

    public function getLoggedDrinks( $beerID ) {
        $query = $this
            ->db
            ->select( 'drink_log.*, users.display_name, serving_size.name AS ss_name' )
            ->from( 'drink_log' )
            ->join( 'users', 'drink_log.user_id = users.user_id', 'inner' )
            ->join( 'serving_size', 'drink_log.size_id = serving_size.size_id', 'left' )
            ->where( 'drink_log.beer_id', $beerID )
            ->order_by( 'drink_log.date', 'asc' )
            ->order_by( 'users.display_name', 'asc' )
            ->get();
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getRecentLoggedDrinks( $userID, $daysPast ) {
        $where = 'drink_log.date >= ( NOW() - INTERVAL ' . $daysPast . ' DAY )';
        $query = $this
            ->db
            ->select( 'drink_log.*, users.display_name, serving_size.name AS ss_name, beers.beer_name AS beer_name, beers.beer_id AS beer_id, breweries.name AS brewer_name, breweries.brewery_id AS brewery_id' )
            ->from( 'drink_log' )
            ->join( 'users', 'drink_log.user_id = users.user_id', 'inner' )
            ->join( 'serving_size', 'drink_log.size_id = serving_size.size_id', 'left' )
            ->join( 'beers', 'beers.beer_id = drink_log.beer_id', 'inner' )
            ->join( 'breweries', 'breweries.brewery_id = beers.brewery_id', 'inner' )
            ->where( $where, '', false )
            ->order_by( 'drink_log.date', 'desc' )
            ->order_by( 'drink_log.log_id', 'desc' );
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


}

?>