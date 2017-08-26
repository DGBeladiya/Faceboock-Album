<?php
try{
	$dir="UserData/";
	$files=opendir($dir);
	while (($file = readdir($files)) !== false){
	     if ((time()-filectime($dir.$file)) > 10) {  
	     	if(!($file==".." || $file==".")){
	     		$nestedFile=opendir($dir.$file);
	     		while (($tempFile=readdir($nestedFile))!=false) {
	     			if(!($tempFile==".." || $tempFile==".")){
	     				unlink($dir.$file."/".$tempFile);
	     			}
	     		}
	            rmdir($dir.$file);
			}	
	    }
	}
}
catch(Exception $e)
{
	echo "Sorry We Are Not avaliable Due to Following Reasone".$e->getMessage();
}
?>