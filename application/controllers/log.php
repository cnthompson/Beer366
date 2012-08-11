<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Log extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();
        $this->load->model( 'breweries_model' );
        $this->load->model( 'beers_model' );
        $this->load->model( 'location_model' );
        $this->load->model( 'styles_model' );
        $this->load->model( 'drinkers_model' );
        $this->load->model( 'users_model' );
        $this->load->library( 'Authenticator' );
    }

    public function index() {
        $redirect = 'users/totals/' . $this->authenticator->get_user_id();
        $this->authenticator->ensure_auth( $redirect );
        redirect( $redirect );
    }


    public function brewery( $id = 0 ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );

        $data[ 'error' ] = '';

        $editBrewers = array();
        if( $id > 0 ) {
            $editBrewers = $this->breweries_model->getBreweries( $id, false );
        }
        $data[ 'editBrewer' ] = null;
        if( count( $editBrewers ) == 1 ) {
            $data[ 'editBrewer' ][ 'id'       ] = $editBrewers[ 0 ][ 'brewery_id'   ];
            $data[ 'editBrewer' ][ 'name'     ] = $editBrewers[ 0 ][ 'name'         ];
            $data[ 'editBrewer' ][ 'fName'    ] = $editBrewers[ 0 ][ 'full_name'    ];
            $data[ 'editBrewer' ][ 'street'   ] = $editBrewers[ 0 ][ 'street'       ];
            $data[ 'editBrewer' ][ 'city'     ] = $editBrewers[ 0 ][ 'city'         ];
            $data[ 'editBrewer' ][ 'postal'   ] = $editBrewers[ 0 ][ 'postal_code'  ];
            $data[ 'editBrewer' ][ 'country'  ] = $editBrewers[ 0 ][ 'country'      ];
            $data[ 'editBrewer' ][ 'region'   ] = $editBrewers[ 0 ][ 'region'       ];
            $data[ 'editBrewer' ][ 'homepage' ] = $editBrewers[ 0 ][ 'homepage'     ];
            $data[ 'editBrewer' ][ 'type'     ] = $editBrewers[ 0 ][ 'brewery_type' ];
            $data[ 'editBrewer' ][ 'notes'    ] = $editBrewers[ 0 ][ 'notes'        ];
        }

        $allCountries = $this->location_model->getCountries( 0, false );
        foreach( $allCountries as $country ) {
            $data[ 'countries' ][ $country[ '3166_1_id' ] ] = $country[ 'name' ];
            $data[ 'c2rMap' ][ $country[ '3166_1_id' ] ] = array();
        }

        $allRegions = $this->location_model->getRegions( 226, 0, false );
        foreach( $allRegions as $region ) {
            $data[ 'c2rMap' ][ $region[ '3166_1_id' ] ][ $region[ '3166_2_id' ] ] = $region[ 'rgn_name' ];
        }

        $allTypes = $this->breweries_model->getBreweryTypes();
        foreach( $allTypes as $type ) {
            $data[ 'breweryTypes' ][ $type[ 'brewer_type' ] ] = $type[ 'brewer_type_name' ];
        }

        $this->load->library( 'form_validation' );

        $this->form_validation->set_rules( 'shortname', 'Brewery Name', 'trim|required' );
        $this->form_validation->set_rules( 'fullname', 'Full Brewery Name', 'trim|required' );

        $this->form_validation->set_rules( 'address', 'Street Address', 'trim' );
        $this->form_validation->set_rules( 'city', 'City', 'trim|required' );
        $this->form_validation->set_rules( 'postcode', 'Postal Code', 'trim' );
        $this->form_validation->set_rules( 'country', 'Country', 'required|callback_checkCountry' );
        $this->form_validation->set_rules( 'region', 'Region', 'callback_checkRegion' );

        $this->form_validation->set_rules( 'homepage', 'Home Page', 'trim' );
        $this->form_validation->set_rules( 'brewerytype', 'Brewery Type', 'required|callback_checkBreweryType' );
        $this->form_validation->set_rules( 'notes', 'Notes', 'trim' );

        if( $this->form_validation->run() !== false ) {
            $brewerID = (int)$this->input->post( 'brewer_id' );
            $sName = $this->input->post( 'shortname' );
            $fName = $this->input->post( 'fullname' );
            $address = $this->input->post( 'address' );
            $city = $this->input->post( 'city' );
            $post = $this->input->post( 'postcode' );
            $country = $this->input->post( 'country' );
            $region = $this->input->post( 'region' );
            $homepage = $this->input->post( 'homepage' );
            $type = $this->input->post( 'brewerytype' );
            $notes = $this->input->post( 'notes' );
            $res = $this->breweries_model->updateBrewery( $brewerID, $sName, $fName, $address, $city, $post, $country, $region, $homepage, $type, $notes );
            if( $res == 0 ) {
                 $data[ 'error' ] = 'An unknown error occurred while adding the brewery.';
            } else {
                $brewerBase = base_url( "beer/info/" . $res );
                redirect( $brewerBase );
            }
        }
        $header[ 'title' ] = $data[ 'editBrewer' ] == null ? "Add Brewery" : ( "Edit Brewery - " . $data[ 'editBrewer' ][ 'name' ] ) ;
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/log_brewery', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    function checkCountry( $country ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $res = $this->location_model->getCountries( $country, false );
        if( count( $res ) == 0 ) {
            $this->form_validation->set_message( 'checkCountry', 'Your country selection is invalid.' );
            return FALSE;
        }
        return TRUE;
    }

    function checkRegion( $region ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $res = $this->location_model->getRegions( 0, $region, false );
        if( count( $res ) == 0 ) {
            $this->form_validation->set_message( 'checkRegion', 'Your region selection is invalid.' );
            return FALSE;
        }
        return TRUE;
    }

    function checkBreweryType( $type ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $res = $this->breweries_model->getBreweryTypes( $type );
        if( count( $res ) == 0 ) {
            $this->form_validation->set_message( 'checkBreweryType', 'Your brewery type selection is invalid.' );
            return FALSE;
        }
        return TRUE;
    }

    public function beer( $id = 0 ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );

        $data[ 'error' ] = '';

        $editBeers = array();
        if( $id > 0 ) {
            $editBeers = $this->beers_model->getBeers( 0, $id );
        }
        $data[ 'editBeer' ] = null;
        if( count( $editBeers ) == 1 ) {
            $editBrewer = $this->breweries_model->getBreweries( $editBeers[ 0 ][ 'brewery_id'     ], false );
            if( count( $editBrewer ) == 1 ) {
                $data[ 'editBeer' ][ 'id'       ] = $editBeers[ 0 ][ 'beer_id'        ];
                $data[ 'editBeer' ][ 'name'     ] = $editBeers[ 0 ][ 'beer_name'      ];
                $data[ 'editBeer' ][ 'brewerID' ] = $editBeers[ 0 ][ 'brewery_id'     ];
                $data[ 'editBeer' ][ 'brewerN'  ] = $editBrewer[ 0 ][ 'name'          ];
                $data[ 'editBeer' ][ 'substyle' ] = $editBeers[ 0 ][ 'substyle_id'    ];
                $data[ 'editBeer' ][ 'abv'      ] = $editBeers[ 0 ][ 'beer_abv'       ];
                $data[ 'editBeer' ][ 'ba'       ] = $editBeers[ 0 ][ 'beer_ba_rating' ];
            }
        }

        $allBreweries = $this->breweries_model->getBreweries( 0, false );
        foreach( $allBreweries as $brewery ) {
            $data[ 'breweries' ][ $brewery[ 'brewery_id' ] ] = $brewery[ 'name' ];
        }

        $allFamilies = $this->styles_model->getFamilies();
        $data[ 'families' ][ -1 ] = 'All';
        foreach( $allFamilies as $family ) {
            $data[ 'families' ][ $family[ 'family_id' ] ] = $family[ 'family_name' ];
            $data[ 'family2stylesMap' ][ $family[ 'family_id' ] ] = array();
        }

        $allStyles = $this->styles_model->getStyles();
        $data[ 'styles' ][ -1 ] = 'All';
        foreach( $allStyles as $style ) {
            $data[ 'styles' ][ $style[ 'style_id' ] ] = $style[ 'style_name' ];
            $data[ 'family2stylesMap' ][ $style[ 'family_id' ] ][ $style[ 'style_id' ] ] = $style[ 'style_name' ];
            $data[ 'style2sstylesMap' ][ $style[ 'style_id' ] ] = array();
        }

        $allSubStyles = $this->styles_model->getSubStyles();
        foreach( $allSubStyles as $substyle ) {
            $data[ 'substyles' ][ $substyle[ 'substyle_id' ] ] = $substyle[ 'substyle_name' ];
            $data[ 'style2sstylesMap' ][ $substyle[ 'style_id' ] ][ $substyle[ 'substyle_id' ] ] = $substyle[ 'substyle_name' ];
        }

        $this->load->library( 'form_validation' );

        $this->form_validation->set_rules( 'beername', 'Beer Name', 'trim|required' );
        $this->form_validation->set_rules( 'brewery', 'Brewery', 'required|callback_checkBrewery' );
        $this->form_validation->set_rules( 'substyle', 'Sub-Style', 'required|callback_checkSubStyle' );
        $this->form_validation->set_rules( 'abv', 'ABV', 'trim|numeric' );
        $this->form_validation->set_rules( 'ba', 'BA Rating', 'trim|integer' );

        if( $this->form_validation->run() !== false ) {
            $beerID = (int)$this->input->post( 'beer_id' );
            $beerName = $this->input->post( 'beername' );
            $brewery = $this->input->post( 'brewery' );
            $substyle = $this->input->post( 'substyle' );
            $abv = $this->input->post( 'abv' );
            $ba = $this->input->post( 'ba' );
            if( $this->beers_model->checkIfBeerExistsByNameAndBrewer( (int)$brewery, $beerName, $beerID ) ) {
                $data[ 'error' ] = 'The name "' . $beerName . '" already exists for this brewery.';
            } else {
                $res = $this->beers_model->updateBeer( $beerID, $beerName, $brewery, $substyle, $abv, $ba );
                if( $res == 0 ) {
                     $data[ 'error' ] = 'An unknown error occurred while adding the beer.';
                } else {
                    $beerBase = base_url( "beer/info/" . $brewery . '/' . $res );
                    redirect( $beerBase );
                }
            }
        }

        $header[ 'title' ] = $data[ 'editBeer' ] == null ? 'Add Beer' : ( 'Edit Beer - ' . $data[ 'editBeer' ][ 'brewerN' ] . ': ' . $data[ 'editBeer' ][ 'name' ] );
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/log_beer', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    function checkBrewery( $brewery ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $res = $this->breweries_model->getBreweries( $brewery, false );
        if( count( $res ) == 0 ) {
            $this->form_validation->set_message( 'checkBrewery', 'Your brewery selection is invalid.' );
            return FALSE;
        }
        return TRUE;
    }

    function checkSubStyle( $substyle ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $res = $this->styles_model->getSubStyles( 0, $substyle );
        if( count( $res ) == 0 ) {
            $this->form_validation->set_message( 'checkSubStyle', 'Your sub-style selection is invalid.' );
            return FALSE;
        }
        return TRUE;
    }

    function drink( $id = 0, $extra = '' ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );

        $data[ 'error' ] = '';
        $data[ 'scratch' ] = null;

        if( $extra == '' ) { 
            $editDrinks = $this->drinkers_model->getLoggedDrink( $id );
            $data[ 'editDrink' ] = null;
            if( $editDrinks != null ) {
                $data[ 'editDrink' ][ 'id'      ] = $editDrinks->log_id;
                $data[ 'editDrink' ][ 'date'    ] = $editDrinks->date;
                $data[ 'editDrink' ][ 'user_id' ] = $editDrinks->user_id;
                $data[ 'editDrink' ][ 'beer_id' ] = $editDrinks->beer_id;
                $data[ 'editDrink' ][ 'brewery' ] = $editDrinks->brewery_id;
                $data[ 'editDrink' ][ 'size_id' ] = $editDrinks->size_id;
                $data[ 'editDrink' ][ 'rating'  ] = $editDrinks->rating;
                $data[ 'editDrink' ][ 'notes'   ] = $editDrinks->notes;
            }
            if( $data[ 'editDrink' ] != null and $data[ 'editDrink' ][ 'user_id' ] != $this->authenticator->get_user_id() ) {
                redirect( 'log/drink' );
            }
        } else if( $extra == 'd' ) {
            $beers = $this->beers_model->getBeers( 0, $id );
            if( count( $beers ) == 1 ) {
                $data[ 'editDrink' ][ 'id'      ] = -1;
                $data[ 'editDrink' ][ 'date'    ] = date( 'Y-m-d' );
                $data[ 'editDrink' ][ 'user_id' ] = $this->authenticator->get_user_id();
                $data[ 'editDrink' ][ 'beer_id' ] = $beers[ 0 ][ 'beer_id'    ];
                $data[ 'editDrink' ][ 'brewery' ] = $beers[ 0 ][ 'brewery_id' ];
                $data[ 'editDrink' ][ 'size_id' ] = 7;
                $data[ 'editDrink' ][ 'rating'  ] = null;
                $data[ 'editDrink' ][ 'notes'   ] = null;
            }
        }

        $allBreweries = $this->breweries_model->getBreweries( 0, false );
        foreach( $allBreweries as $brewery ) {
            $data[ 'breweries' ][ $brewery[ 'brewery_id' ] ] = $brewery[ 'name' ];
            $data[ 'brew2beerMap' ][ $brewery[ 'brewery_id' ] ] = array();
        }

        $allBeers = $this->beers_model->getBeers( 0 );
        foreach( $allBeers as $beer ) {
            $data[ 'brew2beerMap' ][ $beer[ 'brewery_id' ] ][ $beer[ 'beer_id' ] ] = $beer[ 'beer_name' ];
        }

        $allSizes = $this->beers_model->getServingSizes();
        foreach( $allSizes as $size ) {
            $data[ 'sizes' ][ $size[ 'size_id' ] ] = $size[ 'name' ];
        }

        $this->load->library( 'form_validation' );

        $this->form_validation->set_rules( 'date', 'Date', 'trim|required|callback_checkDate' );
        $this->form_validation->set_rules( 'beer', 'Beer', 'callback_checkBeer' );
        $this->form_validation->set_rules( 'ssize', 'Serving Size', 'callback_checkSSize' );
        $this->form_validation->set_rules( 'rating', 'Rating', 'trim|numeric|callback_checkRating' );
        $this->form_validation->set_rules( 'notes', 'Notes', 'trim' );

        if( $this->form_validation->run() !== false ) {
            $drinkID = (int)$this->input->post( 'drink_id' );
            $date = $this->input->post( 'date' );
            $user = $this->authenticator->get_user_id();
            $beer = $this->input->post( 'beer' );
            $ssize = $this->input->post( 'ssize' );
            $rating = $this->input->post( 'rating' );
            $notes = $this->input->post( 'notes' );
            $res = $this->drinkers_model->updateLoggedDrink( $drinkID, $date, $user, $beer, $ssize, $rating, $notes );
            if( $res == 0 ) {
                $data[ 'error' ] = 'An unknown error occurred while logging your drink.';
            } else {
                $beerBase = base_url( "users/totals/" . $user );
                redirect( $beerBase );
            }
        }

        $header[ 'title' ] = ( $data[ 'editDrink' ] == null or $extra == 'd' ) ? 'Log Drink' : 'Edit Logged Drink';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/log_drink', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    function checkDate( $date ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $d = strtotime( $date );
        if( $d == false || $d == -1 ) {
            $this->form_validation->set_message( 'checkDate', "Could not understand date." );
            return FALSE;
        }
        return TRUE;
    }

    function checkBeer( $beer ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $res = $this->beers_model->getBeers( 0, $beer );
        if( count( $res ) == 0 ) {
            $this->form_validation->set_message( 'checkBeer', 'Your beer selection is invalid.' );
            return FALSE;
        }
        return TRUE;
    }

    function checkSSize( $ssize ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $res = $this->beers_model->getServingSizes( $ssize );
        if( count( $res ) == 0 ) {
            $this->form_validation->set_message( 'checkSSize', 'Your serving size selection is invalid.' );
            return FALSE;
        }
        return TRUE;
    }

    function checkRating( $rating ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        if( $rating < 0 || $rating > 5 ) {
            $this->form_validation->set_message( 'checkRating', 'Your rating must be between 0 and 5, inclusive.' );
            return FALSE;
        }
        return TRUE;
    }

    function scratch( $id = 0, $extra = '' ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );

        $data[ 'error' ] = '';
        $data[ 'scratch' ] = null;
        $data[ 'editDrink' ] = null;

        $editScratch = $this->users_model->getScratchpad( $this->authenticator->get_user_id(), $id );
        $data[ 'editScratch' ] = null;
        if( $editScratch != null and count( $editScratch ) > 0 ) {
            $data[ 'editScratch' ][ 'id'      ] = $editScratch[ 0 ][ 'scratchpad_id' ];
            $data[ 'editScratch' ][ 'date'    ] = $editScratch[ 0 ][ 'date'          ];
            $data[ 'editScratch' ][ 'user_id' ] = $editScratch[ 0 ][ 'user_id'       ];
            $data[ 'editScratch' ][ 'brewery' ] = $editScratch[ 0 ][ 'brewer'        ];
            $data[ 'editScratch' ][ 'beer'    ] = $editScratch[ 0 ][ 'beer'          ];
            $data[ 'editScratch' ][ 'sstyle'  ] = $editScratch[ 0 ][ 'substyle'      ];
            $data[ 'editScratch' ][ 'size'    ] = $editScratch[ 0 ][ 'size'          ];
            $data[ 'editScratch' ][ 'rating'  ] = $editScratch[ 0 ][ 'rating'        ];
            $data[ 'editScratch' ][ 'notes'   ] = $editScratch[ 0 ][ 'notes'         ];
        }

        if( $extra == 'x' and $data[ 'editScratch' ] != null ) {
            $this->users_model->deleteScratch( $id );
            redirect( "users/scratch/" );
        } else if( $extra == 'c' and $data[ 'editScratch' ] != null ) {
            $data[ 'scratch' ][ 'id'      ] = $data[ 'editScratch' ][ 'id' ];
            $data[ 'scratch' ][ 'date'    ] = $data[ 'editScratch' ][ 'date'    ];
            $data[ 'scratch' ][ 'user_id' ] = $data[ 'editScratch' ][ 'user_id' ];
            $data[ 'scratch' ][ 'brewery' ] = $data[ 'editScratch' ][ 'brewery' ];
            $data[ 'scratch' ][ 'beer'    ] = $data[ 'editScratch' ][ 'beer'    ];
            $data[ 'scratch' ][ 'sstyle'  ] = $data[ 'editScratch' ][ 'sstyle'  ];
            $data[ 'scratch' ][ 'size'    ] = $data[ 'editScratch' ][ 'size'    ];
            $data[ 'scratch' ][ 'rating'  ] = $data[ 'editScratch' ][ 'rating'  ];
            $data[ 'scratch' ][ 'notes'   ] = $data[ 'editScratch' ][ 'notes'   ];

            $data[ 'editDrink' ][ 'id'      ] = -1;
            $d = strtotime( $data[ 'editScratch' ][ 'date' ] );
            $data[ 'editDrink' ][ 'date'    ] = ( $d == FALSE || $d == -1 ) ? null : date( 'Y-m-d', $d );
            $data[ 'editDrink' ][ 'user_id' ] = $data[ 'scratch' ][ 'user_id' ];
            $data[ 'editDrink' ][ 'rating'  ] = null;
            if( ( is_numeric( $data[ 'scratch' ][ 'rating'  ] ) )
             && ( $data[ 'scratch' ][ 'rating'  ] > 0 )
             && ( $data[ 'scratch' ][ 'rating'  ] <= 5 ) ) {
                $data[ 'editDrink' ][ 'rating'  ] = $data[ 'scratch' ][ 'rating'  ];
            }
            $data[ 'editDrink' ][ 'notes'   ] = $data[ 'scratch' ][ 'notes'   ];

            $data[ 'editDrink' ][ 'brewery' ] = -1;
            $allBreweries = $this->breweries_model->getBreweries( 0, false );
            foreach( $allBreweries as $brewery ) {
                $data[ 'breweries' ][ $brewery[ 'brewery_id' ] ] = $brewery[ 'name' ];
                $data[ 'brew2beerMap' ][ $brewery[ 'brewery_id' ] ] = array();
                if( ( strtolower( $brewery[ 'name' ] ) == strtolower( $data[ 'scratch' ][ 'brewery' ] ) )
                 || ( strtolower( $brewery[ 'full_name' ] ) == strtolower( $data[ 'scratch' ][ 'brewery' ] ) ) ) {
                    $data[ 'editDrink' ][ 'brewery' ] = $brewery[ 'brewery_id' ];
                }
            }

            $data[ 'editDrink' ][ 'beer_id' ] = -1;
            $allBeers = $this->beers_model->getBeers( 0 );
            foreach( $allBeers as $beer ) {
                $data[ 'brew2beerMap' ][ $beer[ 'brewery_id' ] ][ $beer[ 'beer_id' ] ] = $beer[ 'beer_name' ];
                if( ( $data[ 'editDrink' ][ 'brewery' ] == $beer[ 'brewery_id' ] )
                 && ( strtolower( $beer[ 'beer_name' ] ) == strtolower( $data[ 'scratch' ][ 'beer' ] ) ) ) {
                    $data[ 'editDrink' ][ 'beer_id' ] = $beer[ 'beer_id' ];
                 }
            }

            $data[ 'editDrink' ][ 'size_id' ] = -1;
            $allSizes = $this->beers_model->getServingSizes();
            foreach( $allSizes as $size ) {
                $data[ 'sizes' ][ $size[ 'size_id' ] ] = $size[ 'name' ];
                if( ( strtolower( $size[ 'name' ] ) == strtolower( $data[ 'scratch' ][ 'size' ] ) )
                 || ( strtolower( $size[ 'ml' ] ) == strtolower( $data[ 'scratch' ][ 'size' ] ) ) ) {
                    $data[ 'editDrink' ][ 'size_id' ] = $size[ 'size_id' ];
                 }
            }

            $this->load->library( 'form_validation' );

            $this->form_validation->set_rules( 'date', 'Date', 'trim|required|callback_checkDate' );
            $this->form_validation->set_rules( 'beer', 'Beer', 'callback_checkBeer' );
            $this->form_validation->set_rules( 'ssize', 'Serving Size', 'callback_checkSSize' );
            $this->form_validation->set_rules( 'rating', 'Rating', 'trim|numeric|callback_checkRating' );
            $this->form_validation->set_rules( 'notes', 'Notes', 'trim' );

            if( $this->form_validation->run() !== false ) {
                $drinkID = (int)$this->input->post( 'drink_id' );
                $date = $this->input->post( 'date' );
                $user = $this->authenticator->get_user_id();
                $beer = $this->input->post( 'beer' );
                $ssize = $this->input->post( 'ssize' );
                $rating = $this->input->post( 'rating' );
                $notes = $this->input->post( 'notes' );
                $res = $this->drinkers_model->updateLoggedDrink( $drinkID, $date, $user, $beer, $ssize, $rating, $notes );
                if( $res == 0 ) {
                    $data[ 'error' ] = 'An unknown error occurred while logging your drink.';
                } else {
                    $this->users_model->deleteScratch( $id );
                    redirect( "users/scratch/" );
                }
            }

            $header[ 'title' ] = 'Convert Scratchpad Drink';
            $this->load->view( 'templates/header.php', $header );
            $this->load->view( 'pages/log_drink', $data );
            $this->load->view( 'templates/footer.php', null );
        } else if( $extra != '' ) {
            redirect( "users/scratch/" );
        } else {
            $this->load->library( 'form_validation' );

            $this->form_validation->set_rules( 'date',     'Date',     'trim' );
            $this->form_validation->set_rules( 'brewery',  'Brewery',  'trim' );
            $this->form_validation->set_rules( 'beer',     'Beer',     'trim' );
            $this->form_validation->set_rules( 'substyle', 'SubStyle', 'trim' );
            $this->form_validation->set_rules( 'size',     'Size',     'trim' );
            $this->form_validation->set_rules( 'rating',   'Rating',   'trim' );
            $this->form_validation->set_rules( 'notes',    'Notes',    'trim' );

            if( $this->form_validation->run() !== false ) {
                $scratchID = (int)$this->input->post( 'scratch_id' );
                $date       = $this->input->post( 'date' );
                $user       = $this->authenticator->get_user_id();
                $brewery    = $this->input->post( 'brewery' );
                $beer       = $this->input->post( 'beer' );
                $substyle   = $this->input->post( 'substyle' );
                $size       = $this->input->post( 'size' );
                $rating     = $this->input->post( 'rating' );
                $notes      = $this->input->post( 'notes' );
                $res = $this->users_model->updateScratch( $scratchID, $date, $user, $brewery, $beer, $substyle, $size, $rating, $notes );
                if( $res == 0 ) {
                     $data[ 'error' ] = 'An unknown error occurred while adding the item to your scratchpad.';
                } else {
                    redirect( "users/scratch/" );
                }
            }
            $header[ 'title' ] = $data[ 'editScratch' ] == null ? "Add Scratch" : "Edit Scratch";
            $this->load->view( 'templates/header.php', $header );
            $this->load->view( 'pages/add_scratch', $data );
            $this->load->view( 'templates/footer.php', null );
        }
    }
}