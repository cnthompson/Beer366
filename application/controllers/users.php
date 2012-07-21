<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Users extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model( 'beers_model' );
        $this->load->model( 'users_model' );
        $this->load->model( 'drinkers_model' );
        session_start();
    }

    public function index() {
        if( !isset( $_SESSION[ 'email' ] ) ) {
            redirect( 'welcome' );
        }
        redirect( 'users/totals' );
    }

    public function totals( $userID = 0 ) {
        $data[ 'title' ] = 'All Drinkers';
        $this->load->view( 'templates/header.php', $data );

        if( $userID <= 0 ) {
            $this->load->library( 'table' );
            $allUsers = $this->users_model->getUsers( $userID );
            $userToTotalsMap = NULL;
            foreach( $allUsers as $user ) {
                $result = $this->users_model->getTotalBeerCountForUser( $user[ 'user_id' ] );
                $total = $result[ 0 ][ 'beer_count' ];
                $userToTotalsMap[ $user[ 'user_id' ] ][ 'total' ] = $total;
                $result = $this->users_model->getUniqueBeerCountForUser( $user[ 'user_id' ] );
                $unique = $result[ 0 ][ 'unique_count' ];
                $userToTotalsMap[ $user[ 'user_id' ] ][ 'unique' ] = $unique;
            }
            $data[ 'allUsers' ] = $allUsers;
            $data[ 'totals' ] = $userToTotalsMap;
            $data[ 'drinkLog' ] = $this->drinkers_model->getRecentLoggedDrinks( $userID, 7 );
            $this->load->view( 'pages/all_users', $data );
        } else {
            $this->load->library( 'table' );
            $allUsers = $this->users_model->getUsers( $userID );
            $data[ 'user' ] = $allUsers[ 0 ];
            $data[ 'drinkLog' ] = $this->drinkers_model->getRecentLoggedDrinks( $userID, 10 );
            $data[ 'fives' ] = $this->beers_model->getBeersByRating( $userID, 5 );
            $this->load->view( 'pages/user_profile', $data );
        }

        $this->load->view( 'templates/footer.php', $data );
    }
}