<!-- Home Page -->
<?php
require_once __DIR__.'/Facebook.php';
require_once __DIR__.'/Google.php';

$google=new Google();
$fb=new Facebook();
$accessToken=(isset($_SESSION["facebook_access_token"]))?$_SESSION["facebook_access_token"]:$fb->getAccessToken();
if(!isset($accessToken))
{
	$flag=0;
	$loginURL=$fb->getLoginUrl();
}
else
{
	$flag=1;
	
	$fb->setAccessToken($accessToken);
	$listAlbum=$fb->getAlbumListAndName();
}
if(isset($_SESSION["google_access_token"]))
{
	$gFlag=1;
//	echo $_SESSION["google_access_token"]; 
}
else
{
	$googleLoginURL=$google->getLoginUrl();
	$gFlag=0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Album Manager</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link href="asset/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!--// bootstrap-css -->
<!-- css -->
<link rel="stylesheet" href="asset/css/style.css" type="text/css" media="all" />
<!--// css -->
<link rel="stylesheet" href="asset/css/lightbox.css">  
<!-- font-awesome icons -->
<link href="asset/css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
<!-- font -->
<link href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<!-- //font -->
<script src="asset/js/jquery-1.11.1.min.js"></script>
<script src="asset/js/bootstrap.js"></script>
 <link rel="stylesheet" href="asset/css/fakeLoader.css">
   <script src="asset/js/fakeLoader.js"></script>
   <link rel="stylesheet" href="asset/css/demo.css">
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script> 
<style type="text/css">
	.team-wthree-grid{
		margin-bottom: 30px;
	}
	li
	{
		cursor: pointer;
	}
</style>

</head>
<body ng-app="mainApp" ng-controller="listController">
<div class="fakeloader" ng-show="progressHideShow"></div>

	<!-- banner -->
	<div class="banner" style="background: url(<?php echo $fb->coverPhoto?>)no-repeat;background-size: cover;">
			
			<div class="banner-info">
				<div class="banner-info-text">
					<div class="container">
						<div class="agileits-logo">
							<h1><a href="index.html">Album<span>Manager</span></a></h1>
						</div>
						<div class="w3-border"> </div>
						<div class="w3layouts-banner-info">
								<?php if($flag)
								{
									echo "<h2><span>".$fb->name."</span></h2>";

									}?>
							<div class="w3ls-button">
								<?php if(!$flag)
								{
									echo "<a href='$loginURL'>Login With FaceBook</a>";
								}	

								?>
							</div>
							<div class="w3ls-button">
								<?php if(!$gFlag)
								{
									echo "<a href='$googleLoginURL'>Login With Google</a>";
								}	

								?>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="header-top">
					<div class="container">
						<div class="header-top-info">
							
						</div>
					</div>
				</div>
	</div>
	<!-- //banner -->
	
	
	
	
	<?php if($flag){?>
	<div id="team" class="team">

		<div class="container">	
			<div class="agileits-heading">
				<h3>Your Album</h3>

				<div class="agileits-heading">
      				 <h3>
      				 	<input type="submit" name="multipleDownload" value="Download Multiple Album" class="btn btn-primary" style="margin: auto;" ng-click="downloadMultiple()" ng-disabled="buttonEnable">
      				 	<?php if($gFlag){?>
      				 	<input type="submit" name="moveToDrive" value="Move to Drive" class="btn btn-primary" style="margin: auto;" ng-click="moveMultipleToDrive()" ng-disabled="buttonEnable">
      				 	<input type="submit" name="moveAllToDrive" value="Move all To Drive" class="btn btn-primary" style="margin: auto;" ng-click="moveAllToDrive()">
      				 	<?php }?>
      				 </h3>
    			</div>
				
			</div>		
			<div class="teamw3-agileinfo">
			<?php 
			foreach ($listAlbum as $key => $list) {
			?>
			<!--<?php echo "{{allAlbumListPush(".trim($list['id']).",".str_replace(" ", "+",$list['name']).")}}";?>-->
			{{allAlbumListPush(<?php echo trim($list['id']);?>,<?php echo '"'.str_replace(" ","+",$list["name"]).'"';?>)}}
				<div class="col-md-3 col-xs-6 team-wthree-grid">
					<div class="btm-right" >
						<img src=<?php echo "".$list["picture"]["url"];?> alt=" " width="180"height="200">
						<div class="w3social-icons captn-icon"> 
							<ul>
								<li title="Download This Album" ng-click=<?php echo 'downloadOne('.trim($list['id']).',&#34;'.str_replace(' ', '+', $list["name"]).'&#34;,'.$key.')';?> ><a><i class="fa fa-download"></i></a></li>
								<?php if($gFlag){?>
								<li title="Move This Album" ng-click=<?php echo 'moveOneToDrive('.trim($list['id']).',&#34;'.str_replace(' ', '+', $list["name"]).'&#34;,'.$key.')';?> ><a href="#"><i class="fa fa-google-plus"></i></a></li> 
								<?php }?> 
							</ul>
						</div>
						<div class="captn">

							<a <?php echo "href=AlbumPhoto.php?albumID=".$list["id"]."";?>><h4><?php echo $list["name"];?></h4></a>	
							<br/>
							<input type="submit" value="Select" class="btn btn-info" ng-click=<?php echo 'add('.trim($list['id']).',&#34;'.str_replace(' ', '+', $list["name"]).'&#34;,'.$key.')';?>  ng-hide=view[<?php echo $key; ?>]>
							<input type="submit" value="Remove" class="btn btn-danger" ng-click=<?php echo 'remove('.trim($list['id']).','.$key.')';?>  ng-show=view[<?php echo $key; ?>]   >
						</div>
					</div>
				</div>
					<?php }?>
				<div class="clearfix"> </div>
			</div>
		
		</div>
	</div>
	<?php  } ?>
	
	<!-- contact -->
	<!-- footer -->
<div class="footer">
			<div class="w3_agileits_copy_right_social">
				<div class="agileits_w3layouts_copy_right">
					<p>Developed By Dharmik Beladiya(DG) | Design by <a href="http://w3layouts.com/">W3layouts</a></p>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //footer -->
	<script src="asset/js/jarallax.js"></script>
	<script src="asset/js/SmoothScroll.min.js"></script>
	<script type="asset/text/javascript">
		/* init Jarallax */
		$('.jarallax').jarallax({
			speed: 0.5,
			imgWidth: 1366,
			imgHeight: 768
		})
	</script>
	<script src="asset/js/responsiveslides.min.js"></script>
	<script type="text/javascript" src="asset/js/move-top.js"></script>
	<script type="text/javascript" src="asset/js/easing.js"></script>
	<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {					
			$().UItoTop({ easingType: 'easeOutQuart' });});
	</script>
	<!-- //here ends scrolling icon -->
	
	<!-- stats --><script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.js"></script>
	<script type="text/javascript" src="asset/js/listController.js">
	</script>
	<script src="asset/js/jquery.waypoints.min.js"></script>
	<script src="asset/js/jquery.countup.js"></script>
		<script>
			$('.counter').countUp();
		</script>
	<!-- //stats -->
	 <script>
            $(document).ready(function(){
                $(".fakeloader").fakeLoader({
                    timeToHide:1200,
                    bgColor:"#9b59b6",
                    spinner:"spinner7"
                });
            });
     </script>
</body>	
</html>