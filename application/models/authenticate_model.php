<?php

class Authenticate_Model extends CI_Model {
    function __construct() {
    }

    public function verify_user( $login, $password ) {
        // Try to validate the login as an email address first
        $query = $this
            ->db
            ->where( "LOWER( email ) = '" . strtolower( $login ) . "'" )
            ->limit( 1 )
            ->get( 'users' );
        if( $query->num_rows != 1 ) {
            // Since that failed, try it as the display name
            $query = $this
                ->db
                ->where( "LOWER( display_name ) = '" . strtolower( $login ) . "'" )
                ->limit( 1 )
                ->get( 'users' );
            if( $query->num_rows != 1 ) {
                // If both failed, return immediately
                return false;
            }
        }

        // now we know that the user exists and we have the salt
        $salt = $query->row()->password_salt;
        $hash = $query->row()->password_sha2;

        // Prepend the salt to the password, hash it, and verify it matches
        if( hash( "sha256", $salt . $password ) != $hash ) {
            return false;
        }

        // Return all the user information
        return $query->row();
    }

    public function update_last_login( $email ) {
        $data = array (
            'last_login' => date( 'Y-m-d H:i:s' ),
        );
        $query = $this
            ->db
            ->where( "LOWER( email ) = '" . strtolower( $email ) . "'" )
            ->update( 'users', $data );
    }

    public function is_username_available( $email, $username ) {
        $query = $this
            ->db
            ->where( "LOWER( display_name ) = '" . strtolower( $username ) . "'" );
        if( $email != null ) {
            $query = $this
                ->db
                ->where( "LOWER( email ) != '" . strtolower( $email ) . "'" );
        }
        $query = $this
           ->db
           ->limit( 1 )
           ->get( 'users' );
        if( $query->num_rows != 0 ) {
            return false;
        }
        return true;
    }

    public function is_email_available( $email ) {
        $query = $this
            ->db
            ->where( "LOWER( email ) = '" . strtolower( $email ) . "'" )
            ->limit( 1 )
            ->get( 'users' );
        if( $query->num_rows != 0 ) {
            return false;
        }
        return true;
    }

    public function change_username( $email, $username ) {
        $data = array(
            'display_name' => $username,
            );
        $q = $this->db
            ->where( 'email', $email )
            ->update( 'users', $data );
        if( $q == 1 ) {
            return true;
        } else {
            return false;
        }
    }

    public function change_pw( $email, $oldPW, $newPW ) {
        // first things first, let's make sure a user with this email exists
        $query = $this
           ->db
           ->where( "LOWER( email ) = '" . strtolower( $email ) . "'" )
           ->limit(1)
           ->get( 'users' );
        if( $query->num_rows != 1 ) {
            return false;
        }

        // now we know that the user exists and we have the salt
        $salt = $query->row()->password_salt;
        $hash = $query->row()->password_sha2;
        // Prepend the salt to the password, hash it, and verify it matches
        if( hash( "sha256", $salt . $oldPW ) != $hash ) {
            return false;
        }

        // at this point, we have the right user and password, generate a new salt
        $salt = bin2hex( openssl_random_pseudo_bytes( 32 ) );

        // now, update the db with the new data
        $data = array(
            'password_sha2' => hash( "sha256", $salt . $newPW ),
            'password_salt' => $salt,
            "temp" => null,
            );
        $q = $this->db
            ->where( 'password_sha2', $hash )
            ->where( 'email', $email )
            ->update( 'users', $data );
        if( $q == 1 ) {
            return true;
        } else {
            return false;
        }
    }

    public function add_new_user( $firstName, $lastName, $email, $username, $curUser ) {
        $data = array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'display_name' => $username,
            'added_by' => $curUser,
            );
        $query = $this
            ->db
            ->insert( 'users', $data );
        if( $query == 1 ) {
            return true;
        }
        return false;
    }
}

?>