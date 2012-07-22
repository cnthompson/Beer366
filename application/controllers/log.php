<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Log extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model( 'breweries_model' );
        $this->load->model( 'location_model' );
        session_start();
    }

    public function index() {
        if( !isset( $_SESSION[ 'email' ] ) ) {
            redirect( 'authenticate' );
        }
        redirect( 'users/totals/' . $_SESSION[ 'userid' ] );
    }


    public function brewery() {
        if( !isset( $_SESSION[ 'email' ] ) ) {
            redirect( 'authenticate' );
        }

        $data[ 'lastCountry' ] = '226';
        $allCountries = $this->location_model->getCountries( 0, false );
        foreach( $allCountries as $country ) {
            $data[ 'countries' ][ $country[ '3166_1_id' ] ] = $country[ 'name' ];
        }

        $data[ 'lastRegion' ] = '0';
        $allRegions = $this->location_model->getRegions( 226, 0, false );
        $data[ 'regions' ][ '0' ] = '---';
        foreach( $allRegions as $region ) {
            $data[ 'regions' ][ $region[ '3166_2_id' ] ] = $region[ 'rgn_name' ];
        }

        $data[ 'lastBreweryType' ] = '0';
        $allTypes = $this->breweries_model->getBreweryTypes();
        foreach( $allTypes as $type ) {
            $data[ 'breweryTypes' ][ $type[ 'brewer_type' ] ] = $type[ 'brewer_type_name' ];
        }

        $this->load->library( 'form_validation' );
        if( $this->form_validation->run() !== false ) {

        }
        $header[ 'title' ] = 'Log Brewery';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/log_brewery', $data );
    }
}