<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Authenticate extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();
    }

    public function index() {
        if( isset( $_SESSION[ 'username' ] ) ) {
            redirect( 'welcome' );
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email' );
        $this->form_validation->set_rules('password', 'Password', 'required' );
        
        if( $this->form_validation->run() !== false ) {
            $this->load->model('authenticate_model');
            $res = $this->authenticate_model->verify_user( $this->input->post( 'email_address' ), $this->input->post( 'password' ) );
            if( $res !== false ) {
                $_SESSION[ 'username' ] = $this->input->post( 'email_address' );
                redirect('welcome');
            }
        }
        
        $this->load->view( 'login_view' );
    }
    
    public function logout() {
        session_unset();
        $this->load->view('login_view');
    }
}