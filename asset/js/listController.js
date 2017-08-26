		angular.module("mainApp",[]).controller("listController",function($window,$scope,$http,$window)
		{
			$scope.selected=[];
			$scope.view=[];
			$scope.index=0;
			$scope.progressHideShow=false;
			$scope.allAlbumList=[];
			$scope.buttonEnable=true;
			$scope.allAlbumListPush=function(albumID,albumName)
			{
			
				$scope.allAlbumList.push({"albumID":albumID+"","albumName":albumName});
			}
			$scope.displayButton=function(id)
			{
				$scope.view[$scope.index++]=true;
			}
			$scope.downloadMultiple=function(){
				if($scope.selected.length>0){
					$scope.download({data:$scope.selected});
				}
			}

			$scope.downloadOne=function(albumID,albumName){
				
				$scope.download({data:[{"albumID":albumID+"","albumName":albumName}]});
			}
			$scope.download=function(data)
			{
				$scope.progressHideShow=true;
				$http({method:"post",url:"zipDownload.php",data:data, 
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
					}).success(function(data){
						$scope.progressHideShow=false;
						//alert(data);
						$window.location=data;
					}).error(function(reason){
					
				});
			}
			$scope.moveAllToDrive=function()
			{
				$scope.moveToDrive({data:$scope.allAlbumList});
			}
			$scope.moveOneToDrive=function(albumID,albumName){
				//alert(albumID+" "+albumName)
				$scope.moveToDrive({data:[{"albumID":albumID+"","albumName":albumName}]});

			};

			$scope.moveMultipleToDrive=function(){
				$scope.moveToDrive({data:$scope.selected});
				
			}
			$scope.moveToDrive=function(data){
				$scope.progressHideShow=true;
				$http({method:"post",url:"moveToDrive.php",data:data, 
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
					}).success(function(data){
						$scope.progressHideShow=false;
						//alert(data);
						//$window.location=data;
					}).error(function(reason){
					
				});
			};
			$scope.add=function(albumID,albumName,key){
				
					$scope.view[key]=true;
					$scope.selected.push({"albumID":albumID+"","albumName":albumName});
					$scope.buttonEnable=false;

			}
			
			$scope.remove=function(albumID,key){
				$scope.view[key]=false;
				
				for(i=0;i<$scope.selected.length;i++)
					if($scope.selected[i].albumID===albumID+""){
					$scope.selected.splice(i,1);
					break;
				}
				if($scope.selected.length==0)
					$scope.buttonEnable=true;
			}

		});
