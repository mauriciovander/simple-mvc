<?php 

abstract class Util{

	/**
	 *  Generate unique strings
	 **/
	static function genUID($length = 64){
		$characters = '1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM';
		$charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}


	/**
	 * hashPassword y verifyPassword permiten guardar 
	 * únicamente el HASH sin necesidad de guardar 
	 * el PASSWORD en la BD
	 */
	static function hashPassword($password){
		$options = [
		    'cost' => 12,
		];
		$ph = password_hash($password,1,$options);
		return end(explode("\$", $ph));
	}

	static function verifyPassword($password,$hash) {
		return password_verify($password, '$2y$12$'.$hash);
	}
}
