<?php
/**
* @version 1.0
* @author Dharmik Beladiya
* @since 12/08/2017
*/
require_once __DIR__."/Facebook.php";
require_once __DIR__."/function.php";
require_once __DIR__."/Google.php";


/**
* Class ShareToDrive
* Contain Function to Share Facebook Album to User Google Drive
*/
class ShareToDrive
{
	/**
	*@var  Object of Class Google To Access Function of Google class
	*/

	private  $google="";
	
	/**
	*@var  Object of Class Facebook To Access Function of Facebook class
	*/

	private $fb="";

	/**
	*@var  Object of Class UtilityFunction To Access General function
	*/

	private $func="";
	
	function __construct()
	{
		$this->google=new Google();
		$this->func=new UtilityFunction();
		$this->fb=new Facebook();
		$this->google->client->setAccessToken($_SESSION["google_access_token"]);
		$this->fb->setAccessToken($_SESSION["facebook_access_token"]);
	}
	/**
	* Get Photos from Facebook album And move it to Google Drive
	*
	* @param $request
	*/
	function moveIT($request)
	{
		try{
			$rndmString=$this->func->generateRandomString(26);
			$dir="UserData/".$rndmString;
			mkdir($dir);
			$folderId=$this->google->getFolderExistsCreate("facebook_".str_replace(" ", "_", $this->fb->getUserName())."_album","");
			foreach ($request->data as $key => $value) 
			{
				$albumID=$value->albumID;
				$albumName=str_replace("+", " ", $value->albumName);
				$list=$this->fb->getPhotoByAlbum($albumID);
				$subFolderId=$this->google->createSubFolder($folderId,$albumName);	
	    		foreach ($list as $key => $value) 
	    		{
				    $data=file_get_contents($value);
				    $fp = fopen($dir."/".$albumName.$key.".jpg","w");
						  if (!$fp) exit;
						  fwrite($fp, $data);
						$title=$albumName.$key;
						$mimeType=mime_content_type($dir."/".$albumName.$key.".jpg");
						$filename=$dir."/".$albumName.$key.".jpg";						
				    	$this->google->insertFile($title,  $mimeType, $filename, $subFolderId);
	    		}
			}
		}
		catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$ShareToDriveObj=new ShareToDrive();
$ShareToDriveObj->moveIT($request);
?>