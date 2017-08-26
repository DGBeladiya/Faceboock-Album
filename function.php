<?php
/**
* @version 1.0
* @author Dharmik Beladiya
* @since 15/08/2017
* Class UtilityFunction 
*/
class UtilityFunction 
{

	/**
	* Return String of Random Alphabet and Digit of Specifed Length
	*
	*/	
	function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
}

?>