<?php

class Location_Model extends CI_Model {
    function __construct() {
    }

    public function getCountries( $countryID = 0 ) {
        $query = $this
            ->db
            ->select( 'iso_3166_1.*, COUNT( iso_3166_1.3166_1_id ) AS num_brewers'  )
            ->from( 'iso_3166_1' )
            ->join( 'breweries', 'breweries.country =  iso_3166_1.3166_1_id', 'inner' )
            ->group_by( 'iso_3166_1.3166_1_id' )
            ->order_by( 'iso_3166_1.name', 'asc' );
        if( $countryID <= 0 ) {
            $query = $this
                ->db
                ->get();
        } else {
            $query = $this
                ->db
                ->where( 'iso_3166_1.3166_1_id', $countryID )
                ->get();
        }
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    
    public function getRegions( $countryID = 0, $regionID = 0 ) {
        $query = NULL;
        if( $countryID <= 0 ) {
            $query = $this
                ->db
                ->select( 'iso_3166_2.*, COUNT( iso_3166_2.3166_2_id ) AS num_brewers'  )
                ->from( 'iso_3166_2' )
                ->join( 'breweries', 'breweries.country =  iso_3166_2.3166_1_id AND breweries.region = iso_3166_2.3166_2_id', 'inner' )
                ->group_by( 'iso_3166_2.3166_2_id' )
                ->order_by( 'iso_3166_2.rgn_name', 'asc' )
                ->get();
        } else if( $regionID <= 0 ) {
            $query = $this
                ->db
                ->select( 'iso_3166_2.*, COUNT( iso_3166_2.3166_2_id ) AS num_brewers'  )
                ->from( 'iso_3166_2' )
                ->join( 'breweries', 'breweries.country =  iso_3166_2.3166_1_id AND breweries.region = iso_3166_2.3166_2_id', 'inner' )
                ->where( 'iso_3166_2.3166_1_id', $countryID )
                ->group_by( 'iso_3166_2.3166_2_id' )
                ->order_by( 'iso_3166_2.rgn_name', 'asc' )
                ->get();
        } else {
            $query = $this
                ->db
                ->select( 'iso_3166_2.*, COUNT( iso_3166_2.3166_2_id ) AS num_brewers'  )
                ->from( 'iso_3166_2' )
                ->join( 'breweries', 'breweries.country =  iso_3166_2.3166_1_id AND breweries.region = iso_3166_2.3166_2_id', 'inner' )
                ->where( 'iso_3166_2.3166_1_id', $countryID )
                ->where( 'iso_3166_2.3166_2_id', $regionID )
                ->group_by( 'iso_3166_2.3166_2_id' )
                ->order_by( 'iso_3166_2.rgn_name', 'asc' )
                ->get();
        }
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    
    public function getCities( $countryID = 0, $regionID = 0, $city = NULL ) {
        $query = NULL;
        if( $countryID <= 0 ) {
            $query = $this
                ->db
                ->select( 'breweries.city, breweries.region, COUNT( breweries.city ) AS num_brewers' )
                ->from( 'breweries' )
                ->group_by( 'breweries.country, breweries.region, breweries.city' )
                ->order_by( 'breweries.city', 'asc' )
                ->get();
        } else if( $regionID <= 0 && $city == NULL ) {
            $query = $this
                ->db
                ->select( 'breweries.city, breweries.region, COUNT( breweries.city ) AS num_brewers' )
                ->from( 'breweries' )
                ->where( 'breweries.country', $countryID )
                ->group_by( 'breweries.region, breweries.city' )
                ->order_by( 'breweries.city', 'asc' )
                ->get();
        } else if( $city == NULL ) {
            $query = $this
                ->db
                ->select( 'breweries.city, breweries.region, COUNT( breweries.city ) AS num_brewers' )
                ->from( 'breweries' )
                ->where( 'breweries.country', $countryID )
                ->where( 'breweries.region', $regionID )
                ->group_by( 'breweries.city' )
                ->order_by( 'breweries.city', 'asc' )
                ->get();
        } else {
            $query = $this
                ->db
                ->select( 'breweries.city, breweries.region, COUNT( breweries.city ) AS num_brewers' )
                ->from( 'breweries' )
                ->where( 'breweries.country', $countryID )
                ->where( 'breweries.city', $city )
                ->group_by( 'breweries.city' )
                ->order_by( 'breweries.city', 'asc' );
            if( $regionID > 0 ) {
                $query = $this
                    ->db
                    ->where( 'breweries.region', $regionID );
            }
            $query = $this
                ->db
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