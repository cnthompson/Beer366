<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Authenticate extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();
    }

    public function index() {
        if( isset( $_SESSION[ 'email' ] ) ) {
            redirect( 'welcome' );
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email' );
        $this->form_validation->set_rules('password', 'Password', 'required' );
        
        if( $this->form_validation->run() !== false ) {
            $this->load->model('authenticate_model');
            $res = $this->authenticate_model->verify_user( $this->input->post( 'email_address' ), $this->input->post( 'password' ) );
            if( $res !== false ) {
                $_SESSION[ 'userid'      ] = $res->user_id;
                $_SESSION[ 'firstname'   ] = $res->first_name;
                $_SESSION[ 'lastname'    ] = $res->last_name;
                $_SESSION[ 'displayname' ] = $res->display_name;
                $_SESSION[ 'email'       ] = $res->email;
                redirect('welcome');
            }
        }
        
        $this->load->view( 'login_view' );
    }
    
    public function logout() {
        session_unset();
        redirect( 'authenticate' );
    }
    
    public function changePassword() {
        if( !isset( $_SESSION[ 'email' ] ) ) {
            redirect( 'welcome' );
        }

        $this->load->library( 'form_validation' );
        $this->form_validation->set_rules( 'cur_password', 'Current Password', 'required|callback_cur_pw_check' );
        $this->form_validation->set_rules( 'new_password', 'New Password', 'required|matches[confirm_password]|callback_new_pw_check' );
        $this->form_validation->set_rules( 'confirm_password', 'Confirm Password', 'required' );
        if( $this->form_validation->run() !== false ) {
            $this->load->model( 'authenticate_model' );
            $res = $this->authenticate_model->change_pw( $_SESSION[ 'email' ], $this->input->post( 'cur_password' ), $this->input->post( 'new_password' ) );
            if( $res == FALSE ) {
                echo 'Password not changed.';
            } else {
                echo 'Password successfully changed.';
            }
        }
        
        $this->load->view( 'change_pw_view' );
    }
    
    public function cur_pw_check( $pw ) {
        $this->load->model('authenticate_model');
        $res = $this->authenticate_model->verify_user( $_SESSION[ 'email' ], $pw );
        if( $res == false ) {
            $this->form_validation->set_message( 'cur_pw_check', 'You current password is not correct.' );
            return FALSE;
        }
        return TRUE;
    }
    
    public function new_pw_check( $pw ) {
        if( $pw == 'meow' ) {
            $this->form_validation->set_message( 'new_pw_check', 'The %s field can not be the word "meow"' );
            return FALSE;
        }
    }
    
}