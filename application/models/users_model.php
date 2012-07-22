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
}

?>