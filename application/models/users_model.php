<?php

class Users_Model extends CI_Model {
    function __construct() {
    }

    public function getUsers( $userID = 0 ) {
        $query = $this
            ->db
            ->select( '*' )
            ->from( 'users' )
            ->order_by( 'users.display_name', 'asc' );
        if( $userID <= 0 ) {
            $query = $this
                ->db
                ->get();
        } else {
            $query = $this
                ->db
                ->where( 'users.user_id', $userID )
                ->get();
        }
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getTotalBeerCountForUser( $userID ) {
        $query = $this
            ->db
            ->select( 'user_id, COUNT( user_id ) AS beer_count' )
            ->from( 'drink_log' )
            ->group_by( 'user_id' )
            ->order_by( 'beer_count', 'desc' );
        if( $userID > 0 ) {
            $query = $this
                ->db
                ->where( 'user_id', $userID );
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

    public function getUniqueBeerCountForUser( $userID ) {
        $query = $this
            ->db
            ->select( 'user_id, COUNT( user_id ) AS unique_count' )
            ->from( "( SELECT user_id, beer_id FROM drink_log GROUP BY user_id, beer_id ) as total", NULL, FALSE )
            ->group_by( 'user_id' )
            ->order_by( 'unique_count', 'desc' );
        if( $userID > 0 ) {
            $query = $this
                ->db
                ->where( 'user_id', $userID );
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

    public function getAllUniqueBeersByBrewery( $userID = 0 ) {
        $query = $this
            ->db
            ->select( 'CONCAT( breweries.name, \': \', beers.beer_name ) AS beerC', false )
            ->from( 'drink_log' )
            ->join( 'beers', 'beers.beer_id = drink_log.beer_id', 'inner' )
            ->join( 'breweries', 'breweries.brewery_id = beers.brewery_id', 'inner' )
            ->group_by( 'drink_log.beer_id, drink_log.user_id' )
            ->order_by( 'beerC', 'asc' );
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

    public function getScratchpad( $userID, $scratchID = -1 ) {
        $query = $this
            ->db
            ->select( '*' )
            ->from( 'scratchpad' )
            ->order_by( 'date', 'asc' )
            ->where( 'user_id', $userID );
        if( $scratchID != -1 ) {
            $query = $this
                ->db
                ->where( 'scratchpad_id', $scratchID );
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

    public function deleteScratch( $id ) {
        $query = $this
            ->db
            ->where( 'scratchpad_id', $id )
            ->limit( 1 )
            ->delete( 'scratchpad' );
    }

    public function updateScratch( $id, $date, $user, $brewery, $beer, $substyle, $size, $rating, $notes ) {
        if( $user <= 0 ) {
            return 0;
        }

        $d = strtotime( $date );
        $data = array (
            'date'      => ( $d == FALSE || $d == -1 ) ? null : date( 'Y-m-d', $d ),
            'user_id'   => $user,
            'brewer'    => ( $brewery == null  || strlen( $brewery ) == 0  ) ? null : $brewery,
            'beer'      => ( $beer == null     || strlen( $beer ) == 0     ) ? null : $beer,
            'substyle'  => ( $substyle == null || strlen( $substyle ) == 0 ) ? null : $substyle,
            'size'      => ( $size == null     || strlen( $size ) == 0     ) ? null : $size,
            'rating'    => ( $rating == null   || strlen( $rating ) == 0   ) ? null : $rating,
            'notes'     => ( $notes == null    || strlen( $notes ) == 0    ) ? null : $notes,
        );
        $query = null;
        if( $id > 0 ) {
            //updating
            $query = $this
                ->db
                ->where( 'scratchpad_id', $id )
                ->update( 'scratchpad', $data );
        } else {
            //inserting
            $query = $this
                ->db
                ->insert( 'scratchpad', $data );
        }
        if( $query == 1 ) {
            $query = $this
                ->db
                ->select( 'scratchpad_id' )
                ->where( $data )
                ->get( 'scratchpad' );
            if( $query->num_rows > 0 ) {
                $results = $query->result_array();
                return $results[ 0 ][ 'scratchpad_id' ];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}

?>