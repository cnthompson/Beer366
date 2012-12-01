<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Beer extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();
        $this->load->model( 'breweries_model' );
        $this->load->model( 'beers_model' );
        $this->load->model( 'drinkers_model' );
        $this->load->model( 'location_model' );
        $this->load->model( 'styles_model' );
        $this->load->model( 'users_model' );
        $this->load->library( 'Authenticator' );
    }

    public function index() {
        $this->authenticator->ensure_auth( '/beer/info/' );
        redirect( '/beer/info/' );
    }

    public function info( $brewery = 0, $beer = 0 ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $this->load->library( 'table' );
        $this->load->helper( 'html' );
        if( $brewery <= 0 ) {
            $data[ 'breweries' ] = $this->breweries_model->getBreweries( $brewery );
            $header[ 'title' ] = 'All Breweries';
            $this->load->view( 'templates/header.php', $header );
            $this->load->view( 'pages/all_breweries', $data );
            $this->load->view( 'templates/footer.php', null );
        } else if( $beer <= 0 ) {
            $breweries = $this->breweries_model->getBreweries( $brewery, false );
            if( count( $breweries ) == 0 ) {
                redirect( 'beer/info/' );
            } else {
                $data[ 'brewery' ] = $breweries[ 0 ];
                $data[ 'beers' ] = $this->beers_model->getBeers( $brewery, (int)$this->authenticator->get_user_id() );
                $header[ 'title' ] = 'Brewery Profile - ' . $data[ 'brewery' ][ 'name' ];
                $this->load->view( 'templates/header.php', $header );
                $this->load->view( 'pages/brewer_profile', $data );
                $this->load->view( 'templates/footer.php', null );
            }
        } else {
            $breweries = $this->breweries_model->getBreweries( $brewery );
            $beers = $this->beers_model->getBeers( $brewery, (int)$this->authenticator->get_user_id(), $beer );
            if( count( $breweries ) == 0 ) {
                redirect( 'beer/info/' );
            } else if( count( $beers ) == 0 ) {
                redirect( 'beer/info/' . $brewery . '/' );
            } else {
                $data[ 'brewery' ] = $breweries[ 0 ];
                $data[ 'beer' ] = $beers[ 0 ];
                $data[ 'drinkLog' ] = $this->drinkers_model->getLoggedDrinks( $beer );
                $data[ 'cellarBeers' ] = $this->users_model->getCellarsWithBeer( $beer );
                $header[ 'title' ] = 'Beer Profile - ' . $data[ 'beer' ][ 'beer_name' ];
                $this->load->view( 'templates/header.php', $header );
                $this->load->view( 'pages/beer_profile', $data );
                $this->load->view( 'templates/footer.php', null );
            }
        }
    }

    public function styles( $family = 0, $style = 0, $substyle = 0 ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $this->load->library( 'table' );
        if( $family <= 0 || $style <= 0 ) {
            $data[ 'families' ] = $this->styles_model->getFamilies( $family );
            $data[ 'styles'   ] = $this->styles_model->getStyles( $family, $style );
            $header[ 'title' ] = 'Beer Styles';
            $this->load->view( 'templates/header.php', $header );
            $this->load->view( 'pages/beer_styles', $data );
            $this->load->view( 'templates/footer.php', null );
        } else if( $substyle <= 0 ) {
            $families = $this->styles_model->getFamilies( $family );
            $styles  = $this->styles_model->getStyles( $family, $style );
            if( count( $families ) == 0 ) {
                redirect( 'beer/styles/' );
            } else if( count( $styles ) == 0 ) {
                redirect( 'beer/styles/' . $family . '/' );
            } else {
                $data[ 'family' ] = $families[ 0 ];
                $data[ 'style' ] = $styles[ 0 ];
                $data[ 'substyles' ] = $this->styles_model->getSubStyles( $style );
                $header[ 'title' ] = 'Beer Sub Styles';
                $this->load->view( 'templates/header.php', $header );
                $this->load->view( 'pages/beer_substyles', $data );
                $this->load->view( 'templates/footer.php', null );
            }
        } else {
            $families = $this->styles_model->getFamilies( $family );
            $styles  = $this->styles_model->getStyles( $family, $style );
            $substyles = $this->styles_model->getSubStyles( $style, $substyle );
            if( count( $families ) == 0 ) {
                redirect( 'beer/styles/' );
            } else if( count( $styles ) == 0 ) {
                redirect( 'beer/styles/' . $family . '/' );
            } else if( count( $substyles ) == 0 ) {
                redirect( 'beer/styles/' . $family . '/' . $style . '/' );
            } else {
                $data[ 'family' ] = $families[ 0 ];
                $data[ 'style' ] = $styles[ 0 ];
                $data[ 'substyle' ] = $substyles[ 0 ];
                $data[ 'beers' ] = $this->beers_model->getBeersBySubStyle( $substyle );
                $header[ 'title' ] = "Beer Sub-Style | " . $styles[ 0 ][ 'style_name' ] . " | " . $substyles[ 0 ][ 'substyle_name' ];
                $this->load->view( 'templates/header.php', $header );
                $this->load->view( 'pages/substyle_profile', $data );
                $this->load->view( 'templates/footer.php', null );
            }
        }
    }

    public function location( $continentID = 0, $subContinentID = 0, $countryID = 0, $regionID = 0, $city = NULL ) {
        // First, make sure we're properly authenticated
        $this->authenticator->ensure_auth( $this->uri->uri_string() );

        // Clean up the city name parameter
        if( $city != NULL ) {
            $city = html_entity_decode( urldecode( $city ) );
        }

        // Check for which parameters are defaulted and redirect to clean
        // up the provided URL. With the exception of the region, once
        // any parameter is defaulted, all the remaining must also be
        // defaulted or the URL is not valid.
        if( $continentID <= 0 and ( $subContinentID != 0 or $countryID != 0 or $regionID != 0 or $city != NULL ) ) {
            redirect( 'beer/location/' );
        } else if( $subContinentID <= 0 and ( $countryID != 0 or $regionID != 0 or $city != NULL ) ) {
            if( $continentID == 0 ) {
                redirect( 'beer/location/' );
            } else {
                redirect( 'beer/location/' . $continentID . '/' );
            }
        } else if( $countryID <= 0 and ( $regionID != 0 or $city != NULL ) ) {
            if( $continentID == 0 ) {
                redirect( 'beer/location/' );
            } else if( $subContinentID == 0 ) {
                redirect( 'beer/location/' . $continentID . '/' );
            } else {
                redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' );
            }
        }

        // Fetch all the specified information, making sure it's only locations with breweries
        // After the data is fetched, sanitize the remaining data accordingly
        $continents = $this->location_model->getContinents( $continentID, true );
        if( ( count( $continents ) == 0 )
         or ( ( $continentID != 0 ) and ( count( $continents ) > 1 ) ) ) {
            // We must have been passed an invalid continent id, or an id for a continent
            // that doesn't contain any breweries. In that case, redirect back to this
            // controller with no parameters, giving us the complete world view.
            redirect( 'beer/location/' );
        }

        $subcontinents = $this->location_model->getSubContinents( $continentID, $subContinentID, true );
        if( ( count( $subcontinents ) == 0 )
         or ( ( $subContinentID != 0 ) and ( count( $subcontinents ) > 1 ) ) ) {
            // We were passed an invalid subcontinent id, or an id for a subcontinent
            // that doesn't have any breweries. Redirect back to this controller using
            // our provided continent id, if it doesn't equal 0, otherwise fall back
            // on the no-parameters url and the world view.
            if( $continentID == 0 ) {
                redirect( 'beer/location/' );
            } else {
                redirect( 'beer/location/' . $continentID . '/' );
            }
        }
        $countries = $this->location_model->getCountries( $countryID, true );
        if( ( count( $countries ) == 0 )
         or (  ( $countryID != 0 ) and ( count( $countries ) > 1 ) ) ) {
            // We were passed an invalid country id, or an id for a country that
            // has no logged breweries. Redirect back to this page, sanitizing
            // the url based on the other parameters.
            if( $continentID == 0 ) {
                redirect( 'beer/location/' );
            } else if( $subContinentID == 0 ) {
                redirect( 'beer/location/' . $continentID . '/' );
            } else {
                redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' );
            }
        }
        // We only need region and city information if we have a valid country, so there's
        // no reason to load them otherwise.
        $regions = null;
        $cities = null;
        if( $countryID > 0 ) {
            $regions = $regionID <= 0 ? NULL : $this->location_model->getRegions( $countryID, $regionID, true );
            if( ( $regions != NULL and count( $regions ) == 0 )
             or ( $regionID != 0 and count( $regions ) != 1 ) ) {
                if( $continentID == 0 ) {
                    redirect( 'beer/location/' );
                } else if( $subContinentID == 0 ) {
                    redirect( 'beer/location/' . $continentID . '/' );
                } else if( $countryID == 0 ) {
                    redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' );
                } else {
                    redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' . $countryID . '/' );
                }
            }
        }

        if( $regionID > 0 or $city != NULL ) {
            $regions = $regionID <= 0 ? NULL : $this->location_model->getRegions( $countryID, $regionID, true );
            if(  $regions != NULL and count( $regions ) == 0 ) {
                if( $continentID == 0 ) {
                    redirect( 'beer/location/' );
                } else if( $subContinentID == 0 ) {
                    redirect( 'beer/location/' . $continentID . '/' );
                } else if( $countryID == 0 ) {
                    redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' );
                } else {
                    redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' . $countryID . '/' );
                }
            }

            $cities = $this->location_model->getCities( $countryID, $regionID, $city );
            if( ( count( $cities ) == 0 )
             or ( ( $city != NULL and count( $cities ) != 1 ) ) ) {
                if( $continentID == 0 ) {
                    redirect( 'beer/location/' );
                } else if( $subContinentID == 0 ) {
                    redirect( 'beer/location/' . $continentID . '/' );
                } else if( $countryID == 0 ) {
                    redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' );
                } else if( $regionID == 0 ) {
                    redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' . $countryID . '/' );
                } else {
                    redirect( 'beer/location/' . $continentID . '/' . $subContinentID . '/' . $countryID . '/' . $regionID . '/' );
                }
            }
        }

        // Make sure that the table library has been loaded
        $this->load->library( 'table' );

        //Now, sort out what we specifically want based on what parameters weren't defaulted
        if( $continentID <= 0 ) {
            // No contininent id? Just show all countries.
            $data[ 'continents'    ] = $continents;
            $data[ 'subcontinents' ] = $subcontinents;
            $data[ 'countries'     ] = $countries;
            $data[ 'mode'          ] = 'world';
            $header[ 'title' ] = 'All Countries';
            $this->load->view( 'templates/header.php', $header );
            $this->load->view( 'pages/all_countries', $data );
            $this->load->view( 'templates/footer.php', null );
        } else if( $subContinentID <= 0 ) {
            // Continent specified, but no sub-continent. Show the continent that
            // was given and a list of all the sub-continents and then countries
            // that are found on it.
            $data[ 'continents'    ] = $continents;
            $data[ 'subcontinents' ] = $subcontinents;
            $data[ 'countries'     ] = $countries;
            $data[ 'mode'          ] = 'continent';
            $header[ 'title' ] = $continents[ 0 ][ 'name' ];
            $this->load->view( 'templates/header.php', $header );
            $this->load->view( 'pages/all_countries', $data );
            $this->load->view( 'templates/footer.php', null );
        } else if( $countryID <= 0 ) {
            // Valid continent and sub-continent, so show the sub-continent
            // that was given and a list of all the countries found on it
            // This should use the all_countries page.
            $data[ 'continents'    ] = $continents;
            $data[ 'subcontinents' ] = $subcontinents;
            $data[ 'countries'     ] = $countries;
            $data[ 'mode'          ] = 'subcontinent';
            $header[ 'title' ] = $continents[ 0 ][ 'name' ] . ' | ' . $subcontinents[ 0 ][ 'name' ];
            $this->load->view( 'templates/header.php', $header );
            $this->load->view( 'pages/all_countries', $data );
            $this->load->view( 'templates/footer.php', null );
        } else if( $regionID <= 0 && $city == NULL ) {
            $countries = $this->location_model->getCountries( $countryID );
            if( count( $countries ) == 0 ) {
                redirect( 'beer/location/' );
            } else {
                $data[ 'continent'    ] = $continents[ 0 ];
                $data[ 'subcontinent' ] = $subcontinents[ 0 ];
                $data[ 'country' ] = $countries[ 0 ];
                $data[ 'regions' ] = $this->location_model->getRegions( $countryID, $regionID );
                $data[ 'cities'  ] = $this->location_model->getCities( $countryID, $regionID );
                $header[ 'title' ] = $data[ 'country' ][ 'name' ] . " | " . ( empty( $data[ 'regions' ] ) ? "Cities" : "Regions" );
                $this->load->view( 'templates/header.php', $header );
                $this->load->view( 'pages/regions_and_cities', $data );
                $this->load->view( 'templates/footer.php', null );
            }
        } else if( $city == NULL ) {
            $countries = $this->location_model->getCountries( $countryID );
            $regions   = $this->location_model->getRegions( $countryID, $regionID );
            if( count( $countries ) == 0 ) {
                redirect( 'beer/location/' );
            } else if( count( $regions ) == 0 ) {
                redirect( 'beer/location/' . $countryID . '/' );
            } else {
                $data[ 'continent'    ] = $continents[ 0 ];
                $data[ 'subcontinent' ] = $subcontinents[ 0 ];
                $data[ 'country' ] = $countries[ 0 ];
                $data[ 'regions' ] = NULL;
                $data[ 'region'  ] = $regions[ 0 ];
                $data[ 'cities'  ] = $this->location_model->getCities( $countryID, $regionID );
                $header[ 'title' ] = $data[ 'country' ][ 'name' ] . " | " . ( empty( $data[ 'regions' ] ) ? "Cities" : "Regions" );
                $this->load->view( 'templates/header.php', $header );
                $this->load->view( 'pages/regions_and_cities', $data );
                $this->load->view( 'templates/footer.php', null );
            }
        } else {
            $countries = $this->location_model->getCountries( $countryID );
            $regions   = $regionID <= 0 ? NULL : $this->location_model->getRegions( $countryID, $regionID );
            $cities    = $this->location_model->getCities( $countryID, $regionID, $city );
            if( count( $countries ) == 0 ) {
                redirect( 'beer/location/' );
            } else if ( count( $cities ) == 0 ) {
                redirect( 'beer/location/' . $countryID . '/' );
            } else {
                $data[ 'continent'    ] = $continents[ 0 ];
                $data[ 'subcontinent' ] = $subcontinents[ 0 ];
                $data[ 'country'   ] = $countries[ 0 ];
                $data[ 'region'    ] = $regions == NULL ? NULL : $regions[ 0 ];
                $data[ 'city'      ] = $cities[ 0 ];
                $data[ 'breweries' ] = $this->breweries_model->getBreweriesByLocation( $countryID, $regionID, $city );
                $header[ 'title' ] = $data[ 'city' ][ 'city' ] . " | " . ( $data[ 'region' ] == NULL ? "" : $data[ 'region' ][ 'rgn_name' ] . " | " ) . $data[ 'country' ][ 'name' ];
                $this->load->view( 'templates/header.php', $header );
                $this->load->view( 'pages/city_profile', $data );
                $this->load->view( 'templates/footer.php', null );
            }
        }

    }
}