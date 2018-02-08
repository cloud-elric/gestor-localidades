<?php
namespace app\models;

use app\modules\ModUsuarios\models\EntUsuarios;


class Utils{

    public static function changeFormatDate($string) {
		$date = date_create ( $string );
		return date_format ( $date, "d-m-Y" );
    }
    
    public static function changeFormatDateInput($string) {
		$date = date_create ( $string );
		return date_format ( $date, "Y-m-d H:i:s" );
    }
    
    public static function generateToken($pre = 'tok') {
		$token = $pre . md5 ( uniqid ( $pre ) ) . uniqid ();
		return $token;
	}
}