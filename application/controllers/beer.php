<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Beer extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model( 'breweries_model' );
        $this->load->model( 'beers_model' );
        $this->load->model( 'drinkers_model' );
        $this->load->model( 'location_model' );
        $this->load->model( 'styles_model' );
        session_start();
    }

    public function index() {
        if( !isset( $_SESSION[ 'email' ] ) ) {
            redirect( 'welcome' );
        }
        echo 'Meow';
    }


    public function info( $brewery = 0, $beer = 0 ) {
        $this->load->library( 'table' );
        if( $brewery <= 0 ) {
            $data[ 'breweries' ] = $this->breweries_model->getBreweries( $brewery );
            $this->load->view( 'pages/all_breweries', $data );
        } else if( $beer <= 0 ) {
            $breweries = $this->breweries_model->getBreweries( $brewery );
            if( count( $breweries ) == 0 ) {
                echo 'Meow error occurred!';
            } else {
                $data[ 'brewery' ] = $breweries[ 0 ];
                $data[ 'beers' ] = $this->beers_model->getBeers( $brewery );
                $this->load->view( 'pages/brewer_profile', $data );
            }
        } else {
            $breweries = $this->breweries_model->getBreweries( $brewery );
            $beers = $this->beers_model->getBeers( $brewery, $beer );
            if( ( count( $breweries ) == 0 ) || ( count( $beers ) == 0 ) ) {
                echo 'Meow error occurred!';
            } else {
                $data[ 'brewery' ] = $breweries[ 0 ];
                $data[ 'beer' ] = $beers[ 0 ];
                $data[ 'drinkLog' ] = $this->drinkers_model->getLoggedDrinks( $beer );
                $this->load->view( 'pages/beer_profile', $data );
            }
        }
    }

    public function styles( $family = 0, $style = 0, $substyle = 0 ) {
        $this->load->library( 'table' );
        if( $family <= 0 || $style <= 0 ) {
            $data[ 'families' ] = $this->styles_model->getFamilies( $family );
            $data[ 'styles'   ] = $this->styles_model->getStyles( $family, $style );
            $this->load->view( 'pages/beer_styles', $data );
        } else if( $substyle <= 0 ) {
            $families = $this->styles_model->getFamilies( $family );
            $styles  = $this->styles_model->getStyles( $family, $style );
            if( ( count( $families ) == 0 ) || ( count( $styles ) == 0 ) ) {
                echo 'Meow error occurred!';
            } else {
                $data[ 'family' ] = $families[ 0 ];
                $data[ 'style' ] = $styles[ 0 ];
                $data[ 'substyles' ] = $this->styles_model->getSubStyles( $style );
                $this->load->view( 'pages/beer_substyles', $data );
            }
        } else {
            $families = $this->styles_model->getFamilies( $family );
            $styles  = $this->styles_model->getStyles( $family, $style );
            $substyles = $this->styles_model->getSubStyles( $style, $substyle );
            if( ( count( $families ) == 0 ) || ( count( $styles ) == 0 ) || ( count( $substyles ) == 0 ) ) {
                echo 'Meow error occurred!';
            } else {
                $data[ 'family' ] = $families[ 0 ];
                $data[ 'style' ] = $styles[ 0 ];
                $data[ 'substyle' ] = $substyles[ 0 ];
                $data[ 'beers' ] = $this->beers_model->getBeersBySubStyle( $substyle );
                $this->load->view( 'pages/substyle_profile', $data );
            }
        }
    }

    public function location( $countryID = 0, $regionID = 0, $city = NULL ) {
        $this->load->library( 'table' );
        if( $city != NULL ) {
            $city = urldecode( $city );
        }
        if( $countryID <= 0 ) {
            $data[ 'countries' ] = $this->location_model->getCountries( $countryID );
            $this->load->view( 'pages/all_countries', $data );
        } else if( $regionID <= 0 && $city == NULL ) {
            $countries = $this->location_model->getCountries( $countryID );
            if( count( $countries ) == 0 ) {
                echo 'Meow error occurred!';
            } else {
                $data[ 'country' ] = $countries[ 0 ];
                $data[ 'regions' ] = $this->location_model->getRegions( $countryID, $regionID );
                $data[ 'cities'  ] = $this->location_model->getCities( $countryID, $regionID );
                $this->load->view( 'pages/regions_and_cities', $data );
            }
        } else if( $city == NULL ) {
            $countries = $this->location_model->getCountries( $countryID );
            $regions   = $this->location_model->getRegions( $countryID, $regionID );
            if( ( count( $countries ) == 0 ) || ( count( $regions ) == 0 ) ) {
                echo 'Meow error occurred!';
            } else {
                $data[ 'country' ] = $countries[ 0 ];
                $data[ 'regions' ] = NULL;
                $data[ 'region'  ] = $regions[ 0 ];
                $data[ 'cities'  ] = $this->location_model->getCities( $countryID, $regionID );
                $this->load->view( 'pages/regions_and_cities', $data );
            }
        } else {
            $countries = $this->location_model->getCountries( $countryID );
            $regions   = $regionID <= 0 ? NULL : $this->location_model->getRegions( $countryID, $regionID );
            $cities    = $this->location_model->getCities( $countryID, $regionID, $city );
            if( ( count( $countries ) == 0 ) || ( count( $cities ) == 0 ) ) {
                echo 'Meow error occurred!';
            } else {
                $data[ 'country'   ] = $countries[ 0 ];
                $data[ 'region'    ] = $regions == NULL ? NULL : $regions[ 0 ];
                $data[ 'city'      ] = $cities[ 0 ];
                $data[ 'breweries' ] = $this->breweries_model->getBreweriesByLocation( $countryID, $regionID, $city );
                $this->load->view( 'pages/city_profile', $data );
            }
        }

    }
}