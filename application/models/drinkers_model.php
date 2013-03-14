<?php

class Drinkers_Model extends CI_Model {
    function __construct() {
    }

    public function getCompleteLog( $userID = 0 ) {
        $query = $this
            ->db
            ->select( 'dl.*, dl2.log_id AS original, beers.beer_name AS beer_name, beers.brewery_id, breweries.name AS brewery_name, beers.substyle_id, beer_sub_style.substyle_name, beer_sub_style.style_id, beer_style.family_id, serving_size.name AS ssize, users.display_name' )
            ->from( 'drink_log dl' )
            ->join( 'drink_log dl2', 'dl.user_id = dl2.user_id AND dl.beer_id = dl2.beer_id AND ( dl2.date < dl.date OR ( dl2.date = dl.date AND dl2.log_id < dl.log_id ) )', 'left' )
            ->join( 'beers', 'beers.beer_id = dl.beer_id', 'inner' )
            ->join( 'breweries', 'breweries.brewery_id = beers.brewery_id', 'inner' )
            ->join( 'beer_sub_style', 'beer_sub_style.substyle_id = beers.substyle_id', 'inner' )
            ->join( 'beer_style', 'beer_sub_style.style_id = beer_style.style_id', 'inner' )
            ->join( 'serving_size', 'dl.size_id = serving_size.size_id', 'inner' )
            ->join( 'users', 'users.user_id = dl.user_id', 'inner' )
            ->group_by( 'dl.log_id' )
            ->order_by( 'dl.date, dl.log_id', 'asc' );
            
        if( $userID > 0 ) {
            $query = $this
                ->db
                ->where( 'dl.user_id', $userID );
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

    public function getBeersByABV( $userID, $limit ) {
        $query = $this
            ->db
            ->select( 'drink_log.*, beers.beer_name AS beer_name, beers.beer_id AS beer_id, breweries.name AS brewer_name, breweries.brewery_id AS brewery_id, beers.beer_abv' )
            ->from( 'drink_log' )
            ->join( 'beers', 'beers.beer_id = drink_log.beer_id', 'inner' )
            ->join( 'breweries', 'breweries.brewery_id = beers.brewery_id', 'inner' )
            ->group_by( 'drink_log.beer_id' )
            ->order_by( 'beers.beer_abv', 'desc' )
            ->order_by( 'beers.beer_name', 'asc' )
            ->limit( $limit );
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

    public function getLoggedDrink( $id ) {
        $query = $this
            ->db
            ->join( 'beers', 'beers.beer_id = drink_log.beer_id' )
            ->where( 'log_id', $id )
            ->limit( 1 )
            ->get( 'drink_log' );
        if( $query->num_rows == 1 ) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function updateLoggedDrink( $id, $date, $user, $beer, $ssize, $rating, $notes ) {
        $d = strtotime( $date );
        if( ( $d == FALSE || $d == -1 )
         || ( $user <= 0 )
         || ( $beer <= 0 )
         || ( $ssize <= 0 ) ) {
            return FALSE;
        }
        $data = array (
            'date'      => date( 'Y-m-d', $d ),
            'user_id'   => $user,
            'beer_id'   => $beer,
            'size_id'   => $ssize,
            'rating'    => ( is_numeric( $rating ) ) ? $rating : null,
            'notes'     => ( $notes == null || strlen( $notes ) == 0 ) ? null : $notes,
        );
        $query = null;
        if( $id > 0 ) {
            //updating
            $query = $this
                ->db
                ->where( 'log_id', $id )
                ->update( 'drink_log', $data );
        } else {
            //inserting
            $query = $this
                ->db
                ->insert( 'drink_log', $data );
        }
        if( $query == 1 ) {
            $query = $this
                ->db
                ->select( 'log_id' )
                ->where( $data )
                ->get( 'drink_log' );
            if( $query->num_rows > 0 ) {
                $results = $query->result_array();
                return $results[ 0 ][ 'log_id' ];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getGloballyUniqueCount( $id = 0 ) {
        $sql = "SELECT u.display_name, u.user_id, COUNT( t.user_id ) AS uniques"
             . " FROM ( "
             . "  SELECT dl.user_id FROM drink_log AS dl"
             . "  LEFT JOIN drink_log AS dl2 ON ( dl.beer_id = dl2.beer_id AND dl.user_id != dl2.user_id )"
             . "  WHERE dl2.user_id IS NULL"
             . "  GROUP BY dl.user_id, dl.beer_id ) AS t"
             . " INNER JOIN users AS u ON ( u.user_id = t.user_id )"
             . ( ( $id > 0 ) ? ( " WHERE u.user_id = '" . $id . "'" ) : " WHERE u.last_login >= ( NOW() - INTERVAL 30 DAY )" )
             . " GROUP BY t.user_id"
             . " ORDER BY uniques DESC";
        $query = $this->db->query( $sql );
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }
}

?>