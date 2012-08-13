<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Users extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();
        $this->load->model( 'beers_model' );
        $this->load->model( 'users_model' );
        $this->load->model( 'drinkers_model' );
        $this->load->library( 'Authenticator' );
    }

    public function index() {
        $redirect = 'users/totals/';
        $this->authenticator->ensure_auth( $redirect );
        redirect( $redirect );
    }

    public function info() {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $header[ 'title' ] = 'User Information';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/user_info.php' );
        $this->load->view( 'templates/footer.php', null );
    }

    public function totals( $userID = 0 ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $data[ 'title' ] = 'All Drinkers';
        $this->load->view( 'templates/header.php', $data );

        if( $userID <= 0 ) {
            $this->load->library( 'table' );
            $allUsers = $this->users_model->getUsers( $userID );
            $userToTotalsMap = NULL;
            foreach( $allUsers as $user ) {
                $result = $this->users_model->getTotalBeerCountForUser( $user[ 'user_id' ] );
                $total = 0;
                if( count( $result ) > 0 ) {
                    $total = $result[ 0 ][ 'beer_count' ];
                }
                $userToTotalsMap[ $user[ 'user_id' ] ][ 'total' ] = $total;
                $result = $this->users_model->getUniqueBeerCountForUser( $user[ 'user_id' ] );
                $unique = 0;
                if( count( $result ) > 0 ) {
                    $unique = $result[ 0 ][ 'unique_count' ];
                }
                $userToTotalsMap[ $user[ 'user_id' ] ][ 'unique' ] = $unique;
            }
            $data[ 'allUsers' ] = $allUsers;
            $data[ 'totals' ] = $userToTotalsMap;
            $data[ 'drinkLog' ] = $this->drinkers_model->getRecentLoggedDrinks( $userID, 7 );
            $data[ 'abv' ] = $this->drinkers_model->getBeersByABV( $userID, 10 );
            $data[ 'uniques' ] =$this->drinkers_model->getGloballyUniqueCount();
            $this->load->view( 'pages/all_users', $data );
        } else {
            $this->load->helper( 'html' );
            $this->load->library( 'table' );
            $allUsers = $this->users_model->getUsers( $userID );
            $data[ 'user' ] = $allUsers[ 0 ];
            $data[ 'drinkLog' ] = $this->drinkers_model->getRecentLoggedDrinks( $userID, 10 );
            $data[ 'fives' ] = $this->beers_model->getBeersByRating( $userID, 5 );
            $data[ 'abv' ] = $this->drinkers_model->getBeersByABV( $userID, 10 );
            $data[ 'fridgeCount' ] = $this->users_model->getFridgeBeerCount( $userID );
            $data[ 'tradeCount'  ] = $this->users_model->getFridgeBeerTradeCount( $userID );
            $this->load->view( 'pages/user_profile', $data );
        }

        $this->load->view( 'templates/footer.php', null );
    }

    public function scratch() {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $data[ 'user' ] = (int)$this->authenticator->get_user_id();
        $data[ 'scratches' ] = $this->users_model->getScratchpad( $data[ 'user' ], -1 );

        $this->load->helper( 'html' );
        $this->load->library( 'table' );

        $header[ 'title' ] = 'Scratchpad';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/scratchpad.php', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    public function fridge( $userID = 0 ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );

        $uid = $userID <= 0 ? (int)$this->authenticator->get_user_id() : $userID;
        $allUsers = $this->users_model->getUsers( $uid );
        if( count( $allUsers ) == 0 ) {
            redirect( 'users/fridge/' );
        }
        $data[ 'user' ] = $allUsers[ 0 ];

        $data[ 'fridge_beers' ] = $this->users_model->getFridgeBeers( $data[ 'user' ][ 'user_id' ], (int)$this->authenticator->get_user_id(), -1 );

        $this->load->helper( 'html' );
        $this->load->library( 'table' );

        $header[ 'title' ] = $this->authenticator->is_current_user( $data[ 'user' ][ 'user_id' ] ) ? 'My Fridge' : ( $data[ 'user' ][ 'display_name' ] . '\'s Fridge' );
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/user_fridge.php', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    public function uniques() {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $data[ 'uniques' ] = $this->users_model->getAllUniqueBeersByBrewery( $this->authenticator->get_user_id() );

        $header[ 'title' ] = 'Unique Beers';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/user_uniques.php', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    public function make_start() {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        if( !isset( $_GET[ 'page' ] ) ) {
            if( $this->authenticator->get_homepage() == null ) {
                redirect( 'users' );
            } else {
                redirect( $this->authenticator->get_homepage() );
            }
        }
        $page = $_GET[ 'page' ];

        if( $this->users_model->updateHomepage( (int)$this->authenticator->get_user_id(), $page ) ) {
            $this->authenticator->set_homepage( $page );
        }

        if( $this->authenticator->get_homepage() == null ) {
            redirect( 'users' );
        } else {
            redirect( $this->authenticator->get_homepage() );
        }
    }

    public function log( $userID = 0 ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );

        $allUsers = $this->users_model->getUsers( $userID );
        if( count( $allUsers ) == 0 ) {
            redirect( 'users/log/' );
        }
        $data[ 'user' ] = $userID <= 0 ? null : $allUsers[ 0 ];

        $data[ 'loggedBeers' ] = $this->drinkers_model->getCompleteLog( $userID );

        $this->load->helper( 'html' );
        $this->load->library( 'table' );

        $header[ 'title' ] = 'Beer Log';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/user_log.php', $data );
        $this->load->view( 'templates/footer.php', null );
    }
}