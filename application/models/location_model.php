<?php

class Location_Model extends CI_Model {
    function __construct() {
    }

    public function getContinents( $continentID = 0, $withBreweries = true ) {
        $query = $this
            ->db
            ->select( 'continents.*, COUNT( iso_3166_1.3166_1_id ) AS num_brewers' )
            ->from( 'continents' )
            ->group_by( 'continents.continent_id' )
            ->order_by( 'continents.name', 'asc' );
        if( $withBreweries ) {
            $query = $this
                ->db
                ->join( 'subcontinents', 'subcontinents.continent_id = continents.continent_id', 'inner' )
                ->join( 'iso_3166_1', 'iso_3166_1.subcontinent_id = subcontinents.subcontinent_id', 'inner' )
                ->join( 'breweries', 'breweries.country =  iso_3166_1.3166_1_id', 'inner' );
        }
        if( $continentID > 0 ) {
            $query = $this
                ->db
                ->where( 'continents.continent_id', $continentID );
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

    public function getSubContinents( $continentID = 0, $subContinentID = 0, $withBreweries = true ) {
        $query = $this
            ->db
            ->select( 'subcontinents.*, COUNT( iso_3166_1.3166_1_id ) AS num_brewers' )
            ->from( 'subcontinents' )
            ->group_by( 'subcontinents.subcontinent_id' )
            ->order_by( 'subcontinents.name', 'asc' );
        if( $withBreweries ) {
            $query = $this
                ->db
                ->join( 'iso_3166_1', 'iso_3166_1.subcontinent_id = subcontinents.subcontinent_id', 'inner' )
                ->join( 'breweries', 'breweries.country =  iso_3166_1.3166_1_id', 'inner' );
        }
        if( $continentID <= 0 ) {
            if( $subContinentID > 0 ) {
                $query = $this
                    ->db
                    ->where( 'subcontinents.subcontinent_id', $subContinentID );
            }
        } else if( $subContinentID <= 0 ) {
            $query = $this
                ->db
                ->where( 'subcontinents.continent_id', $continentID );
        } else {
            $query = $this
                ->db
                ->where( 'subcontinents.continent_id', $continentID )
                ->where( 'subcontinents.subcontinent_id', $subContinentID );
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

    public function getCountries( $countryID = 0, $withBreweries = true ) {
        $query = $this
            ->db
            ->select( 'iso_3166_1.*, subcontinents.continent_id, COUNT( iso_3166_1.3166_1_id ) AS num_brewers'  )
            ->from( 'iso_3166_1' )
            ->join( 'subcontinents', 'subcontinents.subcontinent_id = iso_3166_1.subcontinent_id', 'inner' )
            ->group_by( 'iso_3166_1.3166_1_id' )
            ->order_by( 'iso_3166_1.name', 'asc' );
        if( $withBreweries ) {
            $query = $this
                ->db
                ->join( 'breweries', 'breweries.country =  iso_3166_1.3166_1_id', 'inner' );
        }
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

    public function getRegions( $countryID = 0, $regionID = 0, $withBreweries = true ) {
        $query = $this
            ->db
            ->select( 'iso_3166_2.*, COUNT( iso_3166_2.3166_2_id ) AS num_brewers'  )
            ->from( 'iso_3166_2' )
            ->group_by( 'iso_3166_2.3166_2_id' )
            ->order_by( 'iso_3166_2.rgn_name', 'asc' );
        if( $withBreweries ) {
            $query = $this
                ->db
                ->join( 'breweries', 'breweries.country =  iso_3166_2.3166_1_id AND breweries.region = iso_3166_2.3166_2_id', 'inner' );
        }
        if( $countryID <= 0 ) {
            if( $regionID > 0 ) {
                $query = $this
                    ->db
                    ->where( 'iso_3166_2.3166_2_id', $regionID );
            }
        } else if( $regionID <= 0 ) {
            $query = $this
                ->db
                ->where( 'iso_3166_2.3166_1_id', $countryID );
        } else {
            $query = $this
                ->db
                ->where( 'iso_3166_2.3166_1_id', $countryID )
                ->where( 'iso_3166_2.3166_2_id', $regionID );
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