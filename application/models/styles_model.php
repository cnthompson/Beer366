<?php

class Styles_Model extends CI_Model {
    function __construct() {
    }

    public function getFamilies( $familyID = 0 ) {
        $query =  $this
            ->db
            ->select( '*' )
            ->from( 'beer_families' )
            ->order_by( 'family_name', 'asc' );
        if( $familyID > 0 ) {
            $query =  $this
                ->db
                ->where( 'family_id', $familyID );
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

    public function getStyles( $familyID = 0, $styleID = 0 ) {
        if( $familyID <= 0 ) {
            $query = $this
                ->db
                ->select( '*' )
                ->from( 'beer_style' )
                ->order_by( 'style_name', 'asc' )
                ->get();
        } else if( $styleID <= 0 ) {
            $query = $this
                ->db
                ->select( '*' )
                ->from( 'beer_style' )
                ->order_by( 'style_name', 'asc' )
                ->where( 'family_id', $familyID )
                ->get();
        } else {
            $query = $this
                ->db
                ->select( '*' )
                ->from( 'beer_style' )
                ->order_by( 'style_name', 'asc' )
                ->where( 'family_id', $familyID )
                ->where( 'style_id', $styleID )
                ->get();
        }
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getSubStyles( $styleID = 0, $substyleID = 0 ) {
        if( $styleID <= 0 ) {
            $query = $this
                ->db
                ->select( '*' )
                ->from( 'beer_sub_style' )
                ->order_by( 'substyle_name', 'asc' );
            if( $substyleID > 0 ) {
                $query = $this
                    ->db
                    ->where( 'substyle_id', $substyleID );
            }
            $query = $this
                ->db
                ->get();
        } else if( $substyleID <= 0 ) {
            $query = $this
                ->db
                ->select( '*' )
                ->from( 'beer_sub_style' )
                ->order_by( 'substyle_name', 'asc' )
                ->where( 'style_id', $styleID )
                ->get();
        } else {
            $query = $this
                ->db
                ->select( '*' )
                ->from( 'beer_sub_style' )
                ->order_by( 'substyle_name', 'asc' )
                ->where( 'style_id', $styleID )
                ->where( 'substyle_id', $substyleID )
                ->get();
        }
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }

    }

}

?>