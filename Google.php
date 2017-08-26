 <?php
/**
* @version 1.0
* @author Dharmik Beladiya
*/
require_once __DIR__."/lib/GoogleSDK/src/Google/Client.php";
require_once __DIR__."/lib/GoogleSDK/src/Google/Service/Oauth2.php";
require_once __DIR__.'/lib/GoogleSDK/src/Google/Service/Drive.php';

/**
* Class Google
* Contain function to Access User GoogleDrive
*/
class Google
{
	/**
	* @var clientId to Access API
	*/
	private $clientId="";
	/**
	* @var cleint Secret to Access API
	*/
	private $clientSecret="";
	/**
	* @var URL of Redirect page After Login To Google Drive
	*/
	private $redirectUri="";
	/**
	* @var Object of Google_Drive_Service Class
	*/
	private $service;
	/**
	* @var Array of User Permission to Take
	*/
	private $scope = array(
	'https://www.googleapis.com/auth/drive.file',
	'https://www.googleapis.com/auth/userinfo.email',
	'https://www.googleapis.com/auth/userinfo.profile');
	/**
	* @var Object of Class Google_Client
	*/
	var $client="";
	function __construct()
	{
		try{
			//Please Enter your Client_id.json path
			$json = json_decode(file_get_contents("clientID.json"), true);
			$this->clientId = $json['web']['client_id'];
			$this->clientSecret = $json['web']['client_secret'];
			$this->redirectUri = $json['web']['redirect_uris'][0];
			$this->client = new Google_Client();
		
			$this->client->setClientId($this->clientId);
			$this->client->setRedirectUri($this->redirectUri);
			$this->client->setClientSecret($this->clientSecret);
			$this->client->setAccessType('offline');
			$this->client->setApprovalPrompt('auto');
			$this->client->setState("");

			$this->client->setScopes($this->scope);
			$this->service=new Google_Service_Drive($this->client);
		}
		catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
	/**
	* Return URL for Google Drive LOgin
	*/
	function getLoginUrl() {
		try{
			$tmpUrl = parse_url($this->client->createAuthUrl());
			$query = explode('&', $tmpUrl['query']);
			$query[] = 'user_id=' . urlencode("");
		
			return
					$tmpUrl['scheme'] . '://' . $tmpUrl['host'] .
					$tmpUrl['path'] . '?' . implode('&', $query);
		}
		catch(Exception $e)
		{
			echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
		}
	}
	/**
	* Create Folder inside the given FolderId Folder with name passed as FolderName
	* @var $folderId Id of Folder inside in which you Want To Create Folder
	* @var $folderName Name of Folder Which you want ot Create inside Another Folder 
	*/
	function createSubFolder($folderId,$folderName)
	{
		try{
		  	$files =$this->service->files->listFiles(array('q' => "'$folderId' in parents"));
			$found = false;

		    // Go through each one to see if there is already a folder with the specified name
		    foreach ($files['items'] as $item) {
		        if ($item['title'] == $folderName) {
		            $found = true;
		            return $item['id'];
		            
		        }
		    }
		    if(!$found){
				$subFolder=new Google_Service_Drive_DriveFile();
		        $subFolder->setTitle($folderName);
		        $subFolder->setMimeType('application/vnd.google-apps.folder');
		        $parent=new Google_Service_Drive_ParentReference();
		        $parent->setId($folderId);
		        $subFolder->setParents(array($parent));
		        try {
		            $subFolderMeataData = $this->service->files->insert($subFolder, array(
		                'mimeType' => 'application/vnd.google-apps.folder',
		            ));                 
		        } 
		        catch (Exception $e) {
		                    print "An error occurred: " . $e->getMessage();
		        }
		        return $subFolderMeataData->id;
		        }
	      }
	      catch(Exception $e)
			{
				echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
			}
	}
	/**
	* Return FolderId of The Given Folder if Folder is Not Exit Then It will create Folder  * And Return That Folder ID
	*/
	function getFolderExistsCreate($folderName, $folderDesc) {
	    // List all user files (and folders) at Drive root
	    $files = $this->service->files->listFiles();
	    $found = false;

	    // Go through each one to see if there is already a folder with the specified name
	    foreach ($files['items'] as $item) {
	        if ($item['title'] == $folderName) {
	            $found = true;
	            return $item['id'];
	            break;
	        }
	    }

	    // If not, create one
	    if ($found == false) {
	        $folder = new Google_Service_Drive_DriveFile();

	        //Setup the folder to create
	        $folder->setTitle($folderName);

	        if(!empty($folderDesc))
	            $folder->setDescription($folderDesc);

	        $folder->setMimeType('application/vnd.google-apps.folder');

	        //Create the Folder
	        try {
	            $createdFile = $this->service->files->insert($folder, array(
	                'mimeType' => 'application/vnd.google-apps.folder',
	            ));

	            // Return the created folder's id
	            return $createdFile->id;
	        } catch (Exception $e) {
	            print "An error occurred: " . $e->getMessage();
	        }
	    }
	}
	/**
	* Insert File Inside Given FolderID
	*/
	function insertFile($title,  $mimeType, $filename, $folderID) {
	$file = new Google_Service_Drive_DriveFile();

	// Set the metadata
	$file->setTitle($title);
	$file->setDescription("");
	$file->setMimeType($mimeType);

	// Setup the folder you want the file in, if it is wanted in a folder
			$parent = new Google_Service_Drive_ParentReference();
			$parent->setId($folderID);
			$file->setParents(array($parent));
		
	try {
		// Get the contents of the file uploaded
	$data = file_get_contents($filename);

		// Try to upload the file, you can add the parameters e.g. if you want to convert a .doc to editable google format, add 'convert' = 'true'
		$createdFile = $this->service->files->insert($file, array(
			'data' => $data,
			'mimeType' => $mimeType,
			'uploadType'=> 'multipart'
			));

		// Return a bunch of data including the link to the file we just uploaded
		//return $createdFile;
	} catch (Exception $e) {
		print "An error occurred: " . $e->getMessage();
	}
	}
}
?>