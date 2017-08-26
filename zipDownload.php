<?php 
/**
* @version 1.0
* @author Dharmik Beladiya
* @since 12/08/2017
*/
require_once __DIR__."/Facebook.php";
require_once __DIR__."/function.php";

/**
* Class ZipDownlaod
* Contain Functions to zip Facebook Albums 
*/
class ZipDownload 
{
	/**
	* @var $fb to access functions of Facebook
	*/
	private $fb="";

	/**
	*@var  Object of Class UtilityFunction To Access General function
	*/

	private $func="";
	
	function __construct()
	{
		$this->fb=new Facebook();
		$this->func=new UtilityFunction();
		$this->fb->setAccessToken($_SESSION["facebook_access_token"]);

	}
	/**
	* Get Photos from Facebook album And move it to Google Drive
	*
	* @param $request
	*/

	function zipIT($request)
	{
		$zip = new ZipArchive;
		$rndmString=$this->func->generateRandomString(26);
		$dir="UserData/".$rndmString;
		mkdir($dir,0777);
		if ($zip->open($dir.'/album.zip', ZipArchive::CREATE) === TRUE)
		{
			foreach ($request->data as $key => $value) 
			{
				$albumID=$value->albumID;
				$albumName=str_replace("+", " ", $value->albumName);
				$list=$this->fb->getPhotoByAlbum($albumID);
		
			    foreach ($list as $key => $value) 
			    {
					$data=file_get_contents($value);
				    $fp = fopen($dir."/".$albumName.$key.".jpg","w");
					if (!$fp) exit;
					fwrite($fp, $data);
					$filename=$dir."/".$albumName.$key.".jpg";
					$path=$albumName.'/'.$key.'.jpg';
					// Add files to the zip file inside Folder Named ($rndmString variable Value)
					$zip->addFile($filename, $path);
				}
			}
		// All files are added, so close the zip file.
		$zip->close();
		}
		echo $dir."/album.zip";
	}
}
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$zipObject=new ZipDownload();
$zipObject->zipIT($request)
?>