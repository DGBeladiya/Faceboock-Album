<?php
/**
* @version 1.0
* @author Dharmik Beladiya
*/
require_once __DIR__."/lib/FacebookSDK/autoload.php";
session_start();
/**
* Contain Function to connect to Facebook,Fetch Username,Albums ETC..,and fetch the photos  * of Albums.
*
*/
class Facebook
{
	/**
	* @var Object of Facebook class of SDK used to send request to graph
	*/
	private  $fb;
	/**
	* @var Helper Object of Facebook class of SDK used to get login URL of Facebook
	*/
	private  $helper;

	private  $redirect="https://fbdrawingblock.herokuapp.com/";
	private  $permission=["email","user_photos"];
	public  $name;
	public  $coverPhoto="asset/images/1.jpg";
	function __construct()
	{
		try	{
				//Please Enter Your APP_ID and APP_SECRET
				$this->fb=new Facebook\Facebook(["app_id"=>"XXX",
				"app_secret"=>"XXX",
				"default_graph_version"=>"v2.10"]);
				$this->helper=$this->fb->getRedirectLoginHelper();
			}
			catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
	/**
	* Return Login URL for facebook with Help of Helper class Object
	*
	*/
	public function getLoginUrl()
	{
		try{
			return $this->helper->getLoginUrl($this->redirect,$this->permission);
		}
			catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
	/**
	* Set access Token in $_SESSION['facebook_access_token'] and Call the                    * setDefaultAccessToken Method of facebook class
	* @param $accessToken
	*/
	public function setAccessToken($accessToken){
		try
		{
			if(!isset($_SESSION["facebook_access_token"]))
				$_SESSION["facebook_access_token"]=(string)$accessToken;
			
			$this->fb->setDefaultAccessToken($accessToken);
		}
			catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
	/**
	* Return Access Token
	*
	*/
	public function getAccessToken(){
		try{
			return $this->helper->getAccessToken();
		}
			catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
	/**
	* Return Username of currently login user
	*
	*/
	public function getUserName(){
		try{
			$response=$this->fb->get("me?fields=id,name");
		
		$data= $response->getGraphNode();
		return $data["name"];
		}
		catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
		
	}
	/**
	* Return Album List of Currently
	*
	*/
	public function getAlbumListAndName(){
		try{
			$response=$this->fb->get("me?fields=id,name,cover,albums{picture,name}");
			$data= $response->getGraphNode();
			$this->name=$data["name"];
			$this->coverPhoto=$data["cover"]["source"];
			$listAlbum=array();
			
			if($this->fb->next($data["albums"]))
			{
				$listAlbum=array_merge($data["albums"]->asArray(),$listAlbum);
				while($data=$this->fb->next($data["albums"]))
				{
					$listAlbum=array_merge($data["albums"]->asArray(),$listAlbum);
				}
			}
			else
			{
				$listAlbum=array_merge($data["albums"]->asArray(),$listAlbum);
			}
			return $listAlbum;
		}
		catch(Exception $e)
		{
			echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
		}
	}
	/**
	* Return List of photos link of Given Album
	* @param $albumId
	*/
	public function getPhotoByAlbum($albumId){
		try{
			$response=$this->fb->get("/".$albumId."/photos/?fields=id,source");
				$photos=$response->getGraphEdge();
				$photoList=array();
				if($this->fb->next($photos))
				{
					$photoList=array_merge($photos->asArray(),$photoList);
					while ($photos=$this->fb->next($photos)) {
						$photoList=array_merge($photos->asArray(),$photoList);
					}
				}
				else
				{
					$photoList=array_merge($photos->asArray(),$photoList);
				}
				
				return $photoList;
		}
		catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
}
?>