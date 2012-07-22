<?php

class Breweries_Model extends CI_Model {
    function __construct() {
    }

    public function getBreweries( $brewerID ) {
        $query = $this
            ->db
            ->select( 'COUNT( beers.beer_id ) AS num_beers, iso_3166_1.name AS country_name, iso_3166_2.rgn_name, breweries.*' )
            ->from( 'breweries' )
            ->join( 'beers', 'beers.brewery_id = breweries.brewery_id', 'inner' )
            ->join( 'iso_3166_1', 'iso_3166_1.3166_1_id = breweries.country', 'inner' )
            ->join( 'iso_3166_2', 'iso_3166_2.3166_2_id = breweries.region', 'left' )
            ->group_by( 'breweries.brewery_id' )
            ->order_by( 'breweries.name', 'asc' );
        if( $brewerID <= 0 ) {
            $query = $this
                ->db
                ->get();
        } else {
            $query = $this
                ->db
                ->where( 'breweries.brewery_id', $brewerID )
                ->get();
        }
        if( $query->num_rows > 0 ) {
            return $query->result_array();
        } else {
            return array();
        }
    }
    
    public function getBreweriesByLocation( $countryID, $regionID, $city = NULL ) {
        $query = $this
            ->db
            ->select( 'COUNT( beers.beer_id ) AS num_beers, breweries.*' )
            ->from( 'breweries' )
            ->join( 'beers', 'beers.brewery_id = breweries.brewery_id', 'inner' )
            ->group_by( 'breweries.brewery_id' )
            ->order_by( 'breweries.name', 'asc' );
        if( $countryID <= 0 ) {
            $query = $this
                ->db
                ->get();
        } else if( $regionID <= 0 && $city == NULL ) {
            $query = $this
                ->db
                ->where( 'breweries.country', $countryID )
                ->get();
        } else if( $city == NULL ) {
            $query = $this
                ->db
                ->where( 'breweries.country', $countryID )
                ->where( 'breweries.region', $regionID )
                ->get();
        } else {
            $query = $this
                ->db
                ->where( 'breweries.country', $countryID )
                ->where( 'breweries.city', $city );
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