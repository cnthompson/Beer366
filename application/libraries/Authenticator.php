<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Authenticator {

    // Constructor
    function __construct() {
        if( !isset( $_SESSION ) ) {
            session_start();
        }
    }

    // Save User Information
    // Save some authenticated user information as session data
    public function save_info( $userID, $fName, $lName, $dName, $email, $page, $current ) {
        $_SESSION[ 'userid'      ] = $userID;
        $_SESSION[ 'firstname'   ] = $fName;
        $_SESSION[ 'lastname'    ] = $lName;
        $_SESSION[ 'displayname' ] = $dName;
        $_SESSION[ 'email'       ] = $email;
        $_SESSION[ 'homepage'    ] = $page;
        $_SESSION[ 'current'     ] = $current;
    }

    // Get the current session email, if it is set
    public function get_email() {
        if( isset( $_SESSION[ 'email' ] ) ) {
            return $_SESSION[ 'email' ];
        }
        return "";
    }

    // Get the current session display name, if it is set
    public function get_display_name() {
        if( isset( $_SESSION[ 'displayname' ] ) ) {
            return $_SESSION[ 'displayname' ];
        }
        return "";
    }

    // Get the current session user id, if it is set
    public function get_user_id() {
        if( isset( $_SESSION[ 'userid' ] ) ) {
            return $_SESSION[ 'userid' ];
        }
        return -1;
    }

    // Get whether or not a given user is the current user
    public function is_current_user( $id ) {
        return $id == $this->get_user_id();
    }

    // Get the current session preferred homepage or NULL
    public function get_homepage() {
        if( isset( $_SESSION[ 'homepage' ] ) ) {
            return $_SESSION[ 'homepage' ];
        }
        return null;
    }

    // Set the homepage for the user
    public function set_homepage( $page ) {
        $_SESSION[ 'homepage' ] = $page;
    }

    // Check if the current password is considered expired
    public function is_password_expired() {
        return ( ( isset( $_SESSION[ 'current' ] ) )
              && ( $_SESSION[ 'current' ] == false ) );
    }

    // Set whether or not the current password is considered expired
    public function set_password_expired( $expired) {
        $_SESSION[ 'current' ] = !$expired;
    }

    // Ensure User Authenticated
    // This method is designed to be called from any public controller
    // access point that should only be accessed if the user has
    // been authenticated. If the user has not been authenticated,
    // or if their password is not current, they will be automatically
    // redirected to the appropriate page. If the user is properly
    // authenticated, this will just return to the calling method
    public function ensure_auth( $page = '', $ignoreExpired = false ) {
        if( ( isset( $_SESSION[ 'email' ] ) )
         && ( ( ( isset( $_SESSION[ 'current' ] ) )
             && ( $_SESSION[ 'current' ] == true ) )
           || ( $ignoreExpired ) ) ) {
            return;
        } else if( ! ( isset( $_SESSION[ 'email' ] ) ) ) {
            redirect( 'authenticate?page=' . $page );
        } else {
            redirect( 'authenticate/changePassword?page=' . $page );
        }
    }

    // Check if a user is Authenticated
    // Similar to ensure_auth except just returns a boolean and
    // does not attempt to direct the user to an authentication
    // page.
    public function check_auth( $ignoreExpired = false ) {
        return ( isset( $_SESSION[ 'email' ] ) )
            && ( ( ( isset( $_SESSION[ 'current' ] ) )
                && ( $_SESSION[ 'current' ] == true )
              || ( $ignoreExpired ) ) );
    }

}

?>