<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Authenticate extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();
        $this->load->library( 'Authenticator' );
    }

    public function index() {
        $pageGiven = false;
        $redirect = 'users';
        $data[ 'page' ] = 'authenticate';
        if( isset( $_GET[ 'page' ] ) ) {
            $pageGiven = true;
            $redirect = $_GET[ 'page' ];
            $data[ 'page' ] = 'authenticate?page=' . $redirect;
        }
        if( $this->authenticator->check_auth( true ) ) {
            if( $pageGiven or $this->authenticator->get_homepage() == null ) {
                redirect( $redirect );
            } else {
                redirect( $this->authenticator->get_homepage() );
            }
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('login', 'Login', 'trim|required' );
        $this->form_validation->set_rules('password', 'Password', 'trim|required' );

        $data[ 'error' ] = '';

        if( $this->form_validation->run() !== false ) {
            $this->load->model('authenticate_model');
            $res = $this->authenticate_model->verify_user( $this->input->post( 'login' ), $this->input->post( 'password' ) );
            if( $res == false ) {
                // unsuccessful login
                $data[ 'error' ] = 'Login unsuccessful. Please try again.';
            } else {
                // save the user information from the query
                $this->authenticator->save_info( $res->user_id, $res->first_name, $res->last_name, $res->display_name, $res->email, $res->homepage, $res->temp != 1, $res->admin == 1 );
                if( $pageGiven or $this->authenticator->get_homepage() == null ) {
                    redirect( $redirect );
                } else {
                    redirect( $this->authenticator->get_homepage() );
                }
            }
        }

        $data[ 'title' ] = 'Beer366 Login';
        //$this->load->view( 'templates/header.php', $header );
        $this->load->view( 'login_view', $data );
        //$this->load->view( 'templates/footer.php', null );
    }

    public function logout() {
        session_unset();
        redirect( 'authenticate' );
    }

    public function changeLogin() {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $data[ 'error' ] = '';
        $data[ 'page' ] = 'authenticate/changeLogin';

        $this->load->library( 'form_validation' );
        $this->form_validation->set_rules( 'new_username', 'Current Username', 'trim|required|callback_username_check' );
        if( $this->form_validation->run() !== false ) {
            if( strtolower( $this->input->post( 'new_username' ) ) == strtolower( $this->authenticator->get_display_name() ) ) {
                $data[ 'error' ] = 'Your new username cannot be the same as your old one.';
            } else {
                $this->load->model( 'authenticate_model' );
                $res = $this->authenticate_model->change_username( $this->authenticator->get_email(), $this->input->post( 'new_username' ) );
                if( $res == FALSE ) {
                    $data[ 'error' ] = 'Username not changed.';
                } else {
                    $this->authenticator->set_username( $this->input->post( 'new_username' ) );
                    redirect( $this->authenticator->get_homepage() );
                }
            }
        }
        $header[ 'title' ] = 'Change Username';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'change_username', $data );
        $this->load->view( 'templates/footer.php', null );
    }
    
    public function username_check( $username ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $this->load->model('authenticate_model');
        $res = $this->authenticate_model->is_username_available( $this->authenticator->get_email(), $username );
        if( $res == false ) {
            $this->form_validation->set_message( 'username_check', 'That username is not available.' );
            return FALSE;
        }
        return TRUE;
    }

    public function changePassword() {
        $pageGiven = false;
        $redirect = 'users';
        $data[ 'page' ] = 'authenticate/changePassword';
        if( isset( $_GET[ 'page' ] ) ) {
            $pageGiven = true;
            $redirect = $_GET[ 'page' ];
            $data[ 'page' ] = 'authenticate/changePassword?page=' . $redirect;
        }
        $this->authenticator->ensure_auth( $this->uri->uri_string(), true );

        $data[ 'error' ] = '';

        $this->load->library( 'form_validation' );
        $this->form_validation->set_rules( 'cur_password', 'Current Password', 'trim|required|callback_cur_pw_check' );
        $this->form_validation->set_rules( 'new_password', 'New Password', 'trim|required|matches[confirm_password]|callback_new_pw_check' );
        $this->form_validation->set_rules( 'confirm_password', 'Confirm Password', 'trim|required' );
        if( $this->form_validation->run() !== false ) {
            if( $this->input->post( 'cur_password' ) == $this->input->post( 'new_password' ) ) {
                $data[ 'error' ] = 'Your new password cannot be the same as your old one.';
            } else {
                $this->load->model( 'authenticate_model' );
                $res = $this->authenticate_model->change_pw( $this->authenticator->get_email(), $this->input->post( 'cur_password' ), $this->input->post( 'new_password' ) );
                if( $res == FALSE ) {
                    $data[ 'error' ] = 'Password not changed.';
                } else {
                    $this->authenticator->set_password_expired( false );
                    if( $pageGiven or $this->authenticator->get_homepage() == null ) {
                        redirect( $redirect );
                    } else {
                        redirect( $this->authenticator->get_homepage() );
                    }
                }
            }
        }
        $header[ 'title' ] = 'Change Password';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'change_pw_view', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    public function cur_pw_check( $pw ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string(), true );
        $this->load->model('authenticate_model');
        $res = $this->authenticate_model->verify_user( $this->authenticator->get_email(), $pw );
        if( $res == false ) {
            $this->form_validation->set_message( 'cur_pw_check', 'Your current password is not correct.' );
            return FALSE;
        }
        return TRUE;
    }

    public function new_pw_check( $pw ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string(), true );
        if( $pw == 'meow' ) {
            $this->form_validation->set_message( 'new_pw_check', 'The %s field can not be the word "meow"' );
            return FALSE;
        } else if( $pw == 'beer366' ) {
            $this->form_validation->set_message( 'new_pw_check', 'The %s field can not be the phrase "beer366"' );
            return FALSE;
        }
        return TRUE;
    }

}