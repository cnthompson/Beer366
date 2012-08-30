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
    $allBeers = array();
    $query = $this
        ->db
        ->select( 'CONCAT( breweries.name, \': \', beers.beer_name ) AS beerC, \'0\' AS fridge', false )
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
        foreach( $query->result_array() as $unique ) {
            array_push( $allBeers, $unique );
        }
    }

    $query = $this
        ->db
        ->select( 'CONCAT( breweries.name, \': \', beers.beer_name ) AS beerC, \'1\' AS fridge', false )
        ->from( 'fridge' )
        ->join( 'beers', 'beers.beer_id = fridge.beer_id', 'inner' )
        ->join( 'breweries', 'breweries.brewery_id = beers.brewery_id', 'inner' )
        ->join( 'drink_log', 'fridge.beer_id = drink_log.beer_id AND drink_log.user_id = ' . $userID, 'left' )
        ->where( 'fridge.user_id', $userID )
        ->where( 'drink_log.log_id IS NULL' )
        ->order_by( 'beerC', 'asc' )
        ->get();
    if( $query->num_rows > 0 ) {
        foreach( $query->result_array() as $fridge ) {
            array_push( $allBeers, $fridge );
        }
    }

    return $allBeers;
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

    public function updateHomepage( $userID, $page ) {
        if( $userID <= 0 ) {
            return false;
        }
        $data = array(
            'homepage' => $page,
            );
        $q = $this->db
            ->where( 'user_id', $userID )
            ->update( 'users', $data );
        if( $q == 1 ) {
            return true;
        } else {
            return false;
        }
    }

    public function getFridgeBeers( $userID, $curUser, $fridgeID = -1 ) {
        $query = $this
            ->db
            ->select( 'fridge.*, beers.beer_name, beers.brewery_id, beers.beer_ba_rating, breweries.name AS brewery_name, beer_style.family_id, beer_sub_style.style_id, beer_sub_style.substyle_id, beer_sub_style.substyle_name, serving_size.name AS size_name, drink_log.log_id AS have_had, f2.id AS in_my_fridge' )
            ->from( 'fridge' )
            ->join( 'beers', 'beers.beer_id = fridge.beer_id', 'inner' )
            ->join( 'breweries', 'breweries.brewery_id = beers.brewery_id', 'inner' )
            ->join( 'beer_sub_style', 'beers.substyle_id = beer_sub_style.substyle_id', 'inner' )
            ->join( 'beer_style', 'beer_sub_style.style_id = beer_style.style_id', 'inner' )
            ->join( 'serving_size', 'serving_size.size_id = fridge.size_id', 'inner' )
            ->join( 'drink_log', 'fridge.beer_id = drink_log.beer_id AND drink_log.user_id = ' . $curUser, 'left' )
            ->join( 'fridge AS f2', 'fridge.beer_id = f2.beer_id AND f2.user_id = ' . $curUser, 'left' )
            ->order_by( 'breweries.name, beers.beer_name, fridge.size_id', 'asc' )
            ->group_by( 'fridge.id' )
            ->where( 'fridge.user_id', $userID );
        if( $fridgeID != -1 ) {
            $query = $this
                ->db
                ->where( 'fridge.id', $fridgeID );
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

    public function getFridgesWithBeer( $beer ) {
        $query = $this
            ->db
            ->select( 'fridge.*, users.display_name AS user_name, serving_size.name AS size_name' )
            ->from( 'fridge' )
            ->join( 'users', 'users.user_id = fridge.user_id', 'inner' )
            ->join( 'serving_size', 'serving_size.size_id = fridge.size_id', 'inner' )
            ->order_by( 'users.display_name', 'asc' )
            ->where( 'fridge.beer_id', $beer )
            ->get();
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getFridgeBeerCount( $userID ) {
        $query = $this
            ->db
            ->select( 'SUM( fridge.quantity ) AS total' )
            ->from( 'fridge' )
            ->where( 'fridge.user_id', $userID )
            ->get();
        if( $query->num_rows != 1 ) {
            return 0;
        } else {
            return (int)$query->row()->total;
        }
    }

    public function getFridgeBeerTradeCount( $userID ) {
        $query = $this
            ->db
            ->select( 'SUM( fridge.will_trade ) AS total' )
            ->from( 'fridge' )
            ->where( 'fridge.user_id', $userID )
            ->get();
        if( $query->num_rows != 1 ) {
            return 0;
        } else {
            return (int)$query->row()->total;
        }
    }

    public function deleteFridgeBeer( $id ) {
        $query = $this
            ->db
            ->where( 'id', $id )
            ->limit( 1 )
            ->delete( 'fridge' );
    }

    public function checkIfInFridge( $userID, $beerID, $sizeID ) {
        $query = $this
            ->db
            ->where( 'user_id', $userID )
            ->where( 'beer_id', $beerID )
            ->where( 'size_id', $sizeID )
            ->limit( 1 )
            ->get( 'fridge' );
        return $query->num_rows() == 1;
    }
    
    public function updateFridgeBeer( $id, $user, $beer, $size, $number, $trade, $notes ) {
        if( ( $user <= 0 )
         || ( $beer <= 0 )
         || ( $size <= 0 )
         || ( $number <= 0 )
         || ( $trade < 0 ) ) {
            return 0;
        }

        $data = array (
            'user_id'    => $user,
            'beer_id'    => $beer,
            'size_id'    => $size,
            'quantity'   => $number,
            'will_trade' => $trade,
            'notes'      => ( $notes == null    || strlen( $notes ) == 0    ) ? null : $notes,
        );
        $query = null;
        if( $id > 0 ) {
            //updating
            $query = $this
                ->db
                ->where( 'id', $id )
                ->update( 'fridge', $data );
        } else {
            //inserting
            $query = $this
                ->db
                ->insert( 'fridge', $data );
        }
        if( $query == 1 ) {
            $query = $this
                ->db
                ->select( 'id' )
                ->where( $data )
                ->get( 'fridge' );
            if( $query->num_rows > 0 ) {
                $results = $query->result_array();
                return $results[ 0 ][ 'id' ];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

}

?>