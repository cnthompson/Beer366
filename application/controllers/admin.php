<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        session_start();
        $this->load->library( 'Authenticator' );
    }

    public function index() {
        $this->authenticator->ensure_auth( '' );
        redirect( '' );
    }

    public function addUser() {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        if( !$this->authenticator->is_admin() ) {
            redirect( '' );
        }

        $data[ 'page' ] = 'admin/addUser';
        $data[ 'error' ] = '';

        $this->load->library( 'form_validation' );
        $this->form_validation->set_rules( 'first_name', 'First Name', 'trim|required' );
        $this->form_validation->set_rules( 'last_name', 'Last Name', 'trim|required' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email||callback_email_check' );
        $this->form_validation->set_rules( 'username', 'Username', 'trim|required|callback_username_check' );
        if( $this->form_validation->run() !== false ) {
            $this->load->model( 'authenticate_model' );
            $res = $this->authenticate_model->add_new_user( $this->input->post( 'first_name' ), $this->input->post( 'last_name' ), $this->input->post( 'email' ), $this->input->post( 'username' ), $this->authenticator->get_user_id() );
            if( $res == FALSE ) {
                $data[ 'error' ] = 'New user was not added.';
            } else {
                if( $this->authenticator->get_homepage() == null ) {
                    redirect( '' );
                } else {
                    redirect( $this->authenticator->get_homepage() );
                }
            }
        }
        $header[ 'title' ] = 'Add New User';
        $this->load->view( 'templates/header.php', $header );
        $this->load->view( 'pages/add_new_user', $data );
        $this->load->view( 'templates/footer.php', null );
    }

    public function username_check( $username ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $this->load->model('authenticate_model');
        $res = $this->authenticate_model->is_username_available( null, $username );
        if( $res == false ) {
            $this->form_validation->set_message( 'username_check', 'That username is not available.' );
            return FALSE;
        }
        return TRUE;
    }

    public function email_check( $email ) {
        $this->authenticator->ensure_auth( $this->uri->uri_string() );
        $this->load->model('authenticate_model');
        $res = $this->authenticate_model->is_email_available( $email );
        if( $res == false ) {
            $this->form_validation->set_message( 'email_check', 'That email is not available.' );
            return FALSE;
        }
        return TRUE;
    }

}