
<!-- Display Slider of -->
<?php 
require_once __DIR__."/Facebook.php";
	if(isset($_GET["albumID"]))
	{
		$fb=new Facebook();
		$fb->setAccessToken($_SESSION["facebook_access_token"]);
		$photoLinks=$fb->getPhotoByAlbum($_GET["albumID"]);
	}
	else
	{
		header("location:./");
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DG Album Manager</title>
<link rel="stylesheet" type="text/css" href="asset/css/slider.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easing.1.3.js"></script>
</head>
<body>
	<div id="bg">
		<a href="#" class="nextImageBtn" title="next"></a>
		<a href="#" class="prevImageBtn" title="previous"></a>
		<img src=<?php  echo $photoLinks[0]["source"]?> width="1680" height="1050" alt="DG Album Manager" title="DG Album Manager" id="bgimg" />
	</div>
	<div id="preloader">
		<img src="asset/images/ajax-loader_dark.gif" width="32" height="32" />
	</div>
	<div id="img_title">
		
	</div>
	<div id="toolbar">
		<a href="#" title="Maximize" onClick="ImageViewMode('normal');return false"><img src="asset/images/toolbar_fs_icon.png" width="50" height="50"  /></a>
	</div>
	<div id="thumbnails_wrapper">
		<div id="outer_container">
			<div class="thumbScroller">
				<div class="container">
	 
					<?php 
						foreach ($photoLinks as $key => $value) {
							//print_r($value)
					?>
				    	<div class="content">
				        	<div style="width: 50%;height: 10%;"><a href=<?php echo $value['source'];?>></a></div>
				        </div>
				        <?php   		
						}
					?>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="asset/js/slider.js"></script>
</body>
</html>