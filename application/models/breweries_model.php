<?php

class Breweries_Model extends CI_Model {
    function __construct() {
    }

    public function getBreweries( $brewerID = 0, $withBeer = true ) {
        $query = $this
            ->db
            ->from( 'breweries' )
            ->join( 'iso_3166_1', 'iso_3166_1.3166_1_id = breweries.country', 'inner' )
            ->join( 'iso_3166_2', 'iso_3166_2.3166_2_id = breweries.region', 'left' )
            ->join( 'subcontinents', 'subcontinents.subcontinent_id = iso_3166_1.subcontinent_id', 'inner' )
            ->group_by( 'breweries.brewery_id' )
            ->order_by( 'breweries.name', 'asc' );
        if( $withBeer ) {
            $query = $this
                ->db
                ->select( 'COUNT( beers.beer_id ) AS num_beers, iso_3166_1.name AS country_name, iso_3166_2.rgn_name, iso_3166_1.subcontinent_id, subcontinents.continent_id, breweries.*' )
                ->join( 'beers', 'beers.brewery_id = breweries.brewery_id', 'inner' );
        } else {
            $query = $this
                ->db
                ->select( 'COUNT( breweries.brewery_id ) AS num_beers, iso_3166_1.name AS country_name, iso_3166_2.rgn_name, iso_3166_1.subcontinent_id, subcontinents.continent_id, breweries.*' );
        }
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

    function getBreweryTypes( $typeID = 0 ) {
        $query = $this
            ->db
            ->select( '*' )
            ->from( 'brewery_types' )
            ->order_by( 'brewery_types.brewer_type_name', 'asc' );
        if( $typeID > 0 ) {
            $query = $this
                ->db
                ->where( 'brewery_types.brewer_type', $typeID );
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

    function updateBrewery( $id, $sName, $fName, $street, $city, $post, $country, $region, $homepage, $type, $notes ) {
        if( ( $sName == null || strlen( $sName ) == 0 )
         || ( $fName == null || strlen( $fName ) == 0 )
         || ( $city == null  || strlen( $city ) == 0 )
         || ( $country <= 0 )
         || ( $type <= 0 ) ) {
            return FALSE;
        }
        $data = array (
            'name'          => $sName,
            'full_name'     => $fName,
            'street'        => ( $street == null || strlen( $street ) == 0 ) ? null : $street,
            'city'          => $city,
            'postal_code'   => ( $post == null || strlen( $post ) == 0 ) ? null : $post,
            'country'       => $country,
            'region'        => ( $region == 0 ) ? null : $region,
            'homepage'      => ( $homepage == null || strlen( $homepage ) == 0 ) ? null : $homepage,
            'brewery_type'  => $type,
            'notes'         => ( $notes == null || strlen( $notes) == 0 ) ? null : $notes,
        );
        $query = null;
        if( $id > 0 ) {
            //updating
            $query = $this
                ->db
                ->where( 'brewery_id', $id )
                ->update( 'breweries', $data );
        } else {
            //inserting
            $query = $this
                ->db
                ->insert( 'breweries', $data );
        }
        if( $query == 1 ) {
            $query = $this
                ->db
                ->select( 'brewery_id' )
                ->where( $data )
                ->get( 'breweries' );
            if( $query->num_rows > 0 ) {
                $results = $query->result_array();
                return $results[ 0 ][ 'brewery_id' ];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function checkIfBreweryExistsByName( $brewerID, $name ) {
        $query = $this
            ->db
            ->where( "LOWER( name ) = '" . str_replace( "'", "\\'", strtolower( $name ) ) . "'" )
            ->where( "brewery_id != " . $brewerID )
            ->limit( 1 )
            ->get( 'breweries' );
        if( $query->num_rows > 0 ) {
            return true;
        }
        return false;
    }

    public function checkIfBreweryExistsByFullName( $brewerID, $name ) {
        $query = $this
            ->db
            ->where( "LOWER( full_name ) = '" . str_replace( "'", "\\'", strtolower( $name ) ) . "'" )
            ->where( "brewery_id != " . $brewerID )
            ->limit( 1 )
            ->get( 'breweries' );
        if( $query->num_rows > 0 ) {
            return $query->row()->name;
        }
        return null;
    }
}

?>