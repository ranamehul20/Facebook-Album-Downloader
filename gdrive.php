<?php
session_start();
include_once 'lib/src/Google_Client.php';
include_once 'lib/src/contrib/Google_Oauth2Service.php';
require_once 'lib/src/contrib/Google_DriveService.php';
require_once 'lib/vendor/autoload.php';
$fb = new Facebook\Facebook([
       'app_id' => '355587161647175',
      'app_secret' => '26bedaef64f2b6bf27b112f06463b98b',
      'default_graph_version' => 'v2.2',
      ]);

$client = new Google_Client();
$client->setClientId('587017132534-s267qdejgl5gj9q84mvfo7tcvmfqsoj7.apps.googleusercontent.com');
$client->setClientSecret('bWLpSwR6unbYXNJzDjnsEcrc');
$client->setRedirectUri('https://localhost/FacebookAlbum/gdrive.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));

if (isset($_GET['code']) || (isset($_SESSION['access_token']))) {
	
	
	$service = new Google_DriveService($client);
    if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		echo $_SESSION['access_token'] = $client->getAccessToken();	
			
    } else
        $client->setAccessToken($_SESSION['access_token']);
	
	$access_token=$_SESSION['fb_access_token']; 
	function generateRandomString($length = 10){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
date_default_timezone_set('UTC');
$rndmString="assets/UserData/".generateRandomString(26)."_".date("h-i");
mkdir($rndmString);
	 $response = $fb->get('/me',$access_token);
            $fb->setDefaultAccessToken($_SESSION['fb_access_token']);
            $fb_user = $response->getGraphUser();
	$ftitle="facebook_".str_replace(" ", "_", $fb_user['name'])."_album";
	$folder = new Google_Service_Drive_DriveFile();
	$folder->setTitle($ftitle);
	$folder->setMimeType('application/vnd.google-apps.folder');
	
	/*$parent = new Google_ParentReference(); //previously Google_ParentReference
	$parent->setId($parentid);
	$folder->setParents(array($parent));*/

	//now create the client specific folder  new_sub_folder
	try {
			$createdFile = $service->files->insert($folder, array(
					'mimeType' => 'application/vnd.google-apps.folder',
            ));
			// Return the created folder's id
			$subfolderid = $createdFile->id;
			echo $subfolderid;
			return $createdFile->id;
		} 
		catch (Exception $e) 
		{
			print "An error occurred: " . $e->getMessage();
		}

    $albumId=$_GET['id'];
	$albumName=$_GET['name'];
	$subFolderId=$gle->createSubFolder($service,$folderId,$albumName);
	$useralbumimage_response = $fb_obj->get("/" . $albumID . "/photos?fields=source");
    $useralbumimages = $useralbumimage_response->getGraphEdge()->asArray();
	foreach ($useralbumimages as $key => $value) {
            $data=file_get_contents($value['source']);
            $fp = fopen($rndmString."/".$albumName.$key.".jpg","w");
                    if (!$fp) exit;
                    fwrite($fp, $data);

            $title=$albumName.$key;
            $filename=$rndmString."/".$albumName.$key.".jpg";
            $mimeType=mime_content_type ( $filename );
			$file = new Google_DriveFile();
			$parent = new Google_Service_Drive_ParentReference(); //previously Google_ParentReference
			$parent->setId($subfolderid);
			$file->setParents(array($parent));
			$file->setTitle($title);
			$file->setMimeType('image/jpeg');
			$createdFile = $service->files->insert($file, array(
          'data' =>$data,
          'mimeType' => 'image/jpeg',
		  'uploadType'=>'media'
        ));
        }
	//Insert a file
    /*$fileName="A.zip";
	$file = new Google_DriveFile();
    $file->setTitle($fileName);
    $file->setMimeType('application/zip');
    $file->setDescription('A User Details is uploading in json format');*/
	//print_r($file);
    //exit;
   
    /*$createdFile = $service->files->insert($file, array(
          'data' =>file_get_contents('A.zip'),
          'mimeType' => 'application/zip',
		  'uploadType'=>'multipart'
        ));*/
		
	//unlink($fileName);
    header('Location:https://ranamehulj.000webhostapp.com');
	//print_r($createdFile);

} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}

?>