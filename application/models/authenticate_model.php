<?php

class Authenticate_Model extends CI_Model {
    function __construct() {
    }

    public function verify_user( $email, $password ) {
        $q = $this
           ->db
           ->where( "LOWER( email ) = '" . strtolower( $email ) . "'" )
           ->where( 'password', sha1( $password ) )
           ->limit(1)
           ->get('users');
        if( $q->num_rows > 0 ) {
            return $q->row();
        }
        return false;
    }

    public function change_pw( $email, $oldPW, $newPW ) {
        $data = array(
            'password' => sha1( $newPW ),
            );
        $q = $this->db
            ->where( 'password', sha1( $oldPW ) )
            ->where( 'email', $email )
            ->update( 'users', $data );
        if( $q == 1 ) {
            return true;
        } else {
            return false;
        }
    }
}

?>