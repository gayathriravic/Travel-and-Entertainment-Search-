<?php
	$a=10;
	$input_array=array();
	$latitude;$longitude;$radius;$type;$keyword;
	$jsonPlacesObject;
	$jsonObj="-1";
	$sub=0;
	$latitude=1;
	$longitude=1;
	global $keyword1;
	$keyword1="";
	global $location1;
	$location1="";
	global $flag_loc;
	$flag_loc=0;
	global $distance1;
	$distance1="";
	global $category1;
	$category1="default";
	//key=AIzaSyDRete1YEjQ4aRKgfcrhbdyge0wpfZtBM8


	 $sub = isset($_POST['chooselocation']);

	function googlePlaces($input_array)
	{
		global $jsonPlacesObject;
		global $latitude;
		global $longitude;
		global $keyword1;
		$PlacesKey="AIzaSyD10oZnvZuqjqAvHUw1VUDbNHowrumzToE";
		$latitude=$input_array[1];
		$longitude=$input_array[2];
		$radius=$input_array[3];
		$type=$input_array[4];
		$keyword=$input_array[5];
		$keyword1=$keyword;
		$googlePlacesURL="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$latitude.",".$longitude."&radius=".$radius."&type=".$type."&keyword=".$keyword."&key=".$PlacesKey;

			$jsonPlacesObject=json_decode(htmlspecialchars(@file_get_contents($googlePlacesURL),true),true);


		if(empty($jsonPlacesObject))
		{
			return;
		}
		$uniqueNames=$jsonPlacesObject['results'];
		$namesArray=array();
		$logoArray=array();
		$addressArray=array();
		foreach ($uniqueNames as $item) {
			array_push($namesArray, $item['name']);
			array_push($logoArray,$item['icon']);
			array_push($addressArray,$item['vicinity']);
		}
		return json_encode($jsonPlacesObject,JSON_UNESCAPED_SLASHES);
	}

	$jsonObj;

	function geoLoc()
	{
		global $jsonObj;
		global $keyword1;
		global $location1;
		global $flag_loc;
		global $distance1;
		global $category1;

		if(isset($_POST['searchbutton']))
		{
			$keyword=$_POST['keyword'];
			$keyword1=$keyword;
			$category=$_POST['category'];
			$category1=$category;
			if($_POST['distance'])
			{
				$distance=$_POST['distance'] * 1609.34;
				$distance1=$_POST['distance'];
		    	}
			else{
				$distance=10*1609.34;
				$distance1=10;
			    }

			if(isset($_POST['location']))
			{
				$location=$_POST['location'];
				$location1=$location;
				$flag_loc=1;
			}
			if(isset($_POST['chooselocation'])) // if here is chosen.
			{
				if($_POST['chooselocation']=="here"){
					$APIKey="AIzaSyDRete1YEjQ4aRKgfcrhbdyge0wpfZtBM8";
					$latitude=34.0093;
					$longitude=-118.2584;
					$input_array=array();
					array_push($input_array,$APIKey,$latitude,$longitude,$distance,$category,$keyword);
					$jsonObj=googlePlaces($input_array);
					return ($jsonObj);
			}
		}
	}

	
	if(isset($_POST['location']))  //IF LOCATION IS CHOSEN.
	{
		$location=$_POST['location'];
		$APIKey="AIzaSyDRete1YEjQ4aRKgfcrhbdyge0wpfZtBM8";
		$string = str_replace (" ", "+", urlencode($location));
		$url="https://maps.googleapis.com/maps/api/geocode/json?address=".$string."&key=$APIKey";
		//echo $url;
		$jsonData=json_decode(@file_get_contents($url),JSON_PRETTY_PRINT);
		// The following are input parameters to the google places api function.

		$latitude=$jsonData['results'][0]['geometry']['location']['lat'];
		$longitude=$jsonData['results'][0]['geometry']['location']['lng'];

		$radius=$distance;
		$type= $category;
		$input_array=array();

		array_push($input_array, $APIKey,$latitude,$longitude,$radius,$type,$keyword);
		$jsonObj=googlePlaces($input_array);  //call functio

		return ($jsonObj);
	}                                                                                  
	

}
		function test()
		{
			echo "inside test";
		}

		//$countofImages=0;
		if(isset($_GET['addressname'])){
			$address=$_GET['addressname'];
			$APIKey="AIzaSyDRete1YEjQ4aRKgfcrhbdyge0wpfZtBM8";
			$string = str_replace (" ", "+", urlencode($address));
			$url="https://maps.googleapis.com/maps/api/geocode/json?address=".$string."&key=$APIKey";
		//echo $url;
			$jsonData=json_decode(@file_get_contents($url),JSON_PRETTY_PRINT);
			echo json_encode($jsonData,JSON_UNESCAPED_SLASHES);
			die();

		}

		 if(isset($_POST['searchbutton']))
	    		{

	       		$jsonobj=geoLoc();
	    	    } 


		//$countofImages;
		if(isset($_GET['placeid']))
			{
				global $countofImages;
				$countofImages=0;
				$placeid= $_GET['placeid'];
				//print(gettype($placeid));
				$apikey="AIzaSyD10oZnvZuqjqAvHUw1VUDbNHowrumzToE";
				$url="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeid."&key=".$apikey;
				//$jsonPlace=json_decode(@file_get_contents($url),JSON_PRETTY_PRINT);
				$jsonPlacesObject=json_decode(htmlspecialchars(@file_get_contents($url),true),true);

					for($i=0;$i<5;$i++) //handle case when less than 5 images.
					{

						if(empty($jsonPlacesObject['result']['photos'][$i]))
							break;
						$countofImages=$countofImages+1;
						//print($countofReviews);
						$photo_width=$jsonPlacesObject['result']['photos'][$i]['width'];
						$photo_height=$jsonPlacesObject['result']['photos'][$i]['height'];
						$photo_reference=$jsonPlacesObject['result']['photos'][$i]['photo_reference'];
						if($photo_reference&& $photo_width && $photo_height){
						$makeCallUrl="https://maps.googleapis.com/maps/api/place/photo?maxwidth=".$photo_width."&photoreference=".$photo_reference."&key=".$apikey;
						//file_put_contents($img.$i, file_get_contents($makeCallUrl));
						$image="image".$i.".jpg";
						if($makeCallUrl){
						file_put_contents($image,file_get_contents($makeCallUrl));   //store images in the server.
				      }
			} 
		}
	echo json_encode($jsonPlacesObject,JSON_UNESCAPED_SLASHES);
	die();
  }

?>

	<script type="text/javascript">
		function f1(){	
			document.getElementById("searchbutton").disabled=false;
			document.getElementById("location").disabled=true;
		}

	function f2(){
					document.getElementById("searchbutton").disabled=false;
					document.getElementById("location").disabled=false;
	}

	function f3(){
		document.getElementById("location").disabled=false;
	}

	function clearvals()
	{
		document.getElementById("location").value="";
		document.getElementById("distance").value="";
		document.getElementById("keyword2").value="";
		var el=document.getElementById("sel");
		el.selectedIndex=2;
		document.getElementById("chooselocation1").checked=true;
		document.getElementById("chooselocation2").checked=false;
		document.getElementById("location").disabled=true;
		if(document.getElementById("demo").innerHTML)
		document.getElementById("demo").innerHTML= "";
		if(document.getElementById("displayname").innerHTML)
			document.getElementById("displayname").innerHTML="";
	}

</script>
<!--- ******************************************************************* HTML ***************************************************************** --> 
<!DOCTYPE html>
<html>
<head>
	  <meta charset="UTF-8">

<style type="text/css">
	#map{
		height: 400px;
        width: 100%;
	}

	 #mapbar {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      .arrow{
      	cursor: pointer;
      }

      .collapse{
  		cursor: pointer;
  		display: block;
		}
	  .collapse+input{
	  	display: none;
	  }

	  .collapse+input+div{
	  	  display:none;
	  }

	  .collapse + input:checked + div{
  			display:block;
		}	

		.demo2{
			width:700px;
			height:20px;
			border:1px solid #DCDCDC;
			margin-left: 0.1cm;
		}

		#emptyplaces{
			width:700px;
			height:20px;
			border:1px solid #DCDCDC;
			margin-left: 0.1cm;
		}
	.overlay {
	    position: absolute;
	    display: none;
	    width: 300px;
	    height: 200px;
	    z-index: 2;
	    cursor: pointer;
	}

	.text{
	    width: 400px;
	    height: 300px;
	    background-color:#DCDCDC; 
	}

div p{
	cursor: pointer;
}
    #walkUI{
    	margin-top: cm;
    	margin-left: -5.2cm;
    	padding-bottom: 30px;
    	padding-right:1.5cm;
    	background-color: #E8E8E8;	
    	border: 2px solid:#E8E8E8;
        border-radius: 3px;
        cursor: pointer;
        float: left;
        margin-bottom: 22px;
        text-align: center;
        padding-top: 7px;
    }

    #driveUI{
    	margin-left: -5.2cm;
    	background-color:#E8E8E8;	
        border: 2px solid:#E8E8E8;
        border-radius: 3px;
        padding-right: 1.5cm;
        cursor: pointer;
        float: left;
        margin-bottom: 22px;
        text-align: center;
        margin-top:2cm;
        padding-top: 20px;
    }

    #bikeUI{
    	padding-top: 7px;
    	margin-left: -5.2cm;
    	margin-top: 0.8cm;
    	padding-right: 1.5cm;
    	background-color:#E8E8E8;	
        border: 2px solid:#E8E8E8;
        border-radius: 3px;
        cursor: pointer;
        float: left;
        margin-bottom: 22px;
        text-align: center;
        padding-top:20px;
    }
   #walkText,#bikeText,#driveText{
        color: rgb(25,25,25);
        font-family: Roboto,Arial,sans-serif;
        font-size: 15px;
        line-height: 25px;
        padding-left:2cm;
        text-align: center;
        padding-left: 5px;
        padding-right: 5px;
        margin-left: 1cm;
      }

    #walkText{
    	margin-top:1cm;
    }

    #bikeText{
    	margin-top: 1cm;
    }
      #driveText{
    	margin-top: 5cm;
    }

    #driveText:hover{
    	background-color:#D3D3D3;
    }
    #walkText:hover{
    	background-color:#D3D3D3;
    }
    #bikeText:hover{
    	background-color:#D3D3D3;
    }
    #heading{
    	    font:sans-serif;
    	    font-style: italic; 
    	    font-size: 24pt; 
    	    margin-top: -0.05px;
    	    font-weight: 500
    }

    #box{
    	text-align: center;
    }
    #sel{
    	border-radius: 6px;
    }

    #demo{
    	margin-left: 6.5cm;
    }

    #displayname{
    	margin-left: 6cm;
    }

</style>
<title> Travel and Entertainment Search</title>
</head>
<body> 

<div id="box" style="border: 2px solid #B2BABB; text-align: center; width: 700px;height: 250px; margin-left: 7.5cm; background-color: #F2F4F4;">
	<p id="heading">Travel and Entertainment Search</p></font>
	<hr width="95%" style="margin-top: -0.5cm; color: #B2BABB;">

	<form id="myForm" id="form" action="place.php" method="post" style="margin-left: -6.5cm;">
	<b>Keyword <input type="text" id="keyword2" name="keyword" required="True">
	<br>
    <p style="margin-left:-0.7cm;"><b>Category<select id="sel" name="category">
		
		<option value="cafe">cafe</option>
		<option value="bakery">bakery</option>
		<option selected="true" value="default">default</option>
		<option value="restaurant">restaurant</option>
		<option value="beauty_salon">beauty salon</option>
		<option value="casino">casino</option> 
		<option value="movie_theater">movie theater</option>
		<option value="lodging">lodging</option>
		<option value="airport">airport</option>
		<option value="train_station">train station</option>
		<option value="subway_station">subway station</option>
		<option value="bus_station">bus station</option>
	</select>
	<br>
	<p style="margin-left: 140px; font-style: bold; id="><b>Distance(miles)</b> <input type="text" id="distance" name="distance" placeholder="10">
	<b> from
	<input type="radio" name="chooselocation" required=True; value="here" id ="chooselocation1" checked onclick="f1()"><b>Here</b> <br> </p>
	<input type="radio" id="chooselocation2" name="chooselocation" style="margin-left: 14.5cm;" value="location" onclick="f2()"> <input type="text" id="location" required disabled name="location" placeholder="location" disabled onclick='f3()'>
	<br>
	<br>
	<input type="submit" id="searchbutton" name="searchbutton" value="Search" style="margin-left: -0.1cm; background-color: white;">
	
			<input type="button" value="Clear" name="clearbutton" onclick="clearvals()" style="background-color: white;">
	</form>
	<br><br><br>
	<div id="displayname"></div> <br>
	<div id="demo"> </div>
	<div id="demo1"></div>
	<div id="mapbarr"></div>
	<div id="map"></div>
</div>

<!-- ################################################################ JAVASCRIPT ################################################################### -->

<script src="https://apis.google.com/js/api.js"> </script>
<script type="text/javascript">

document.getElementById("keyword2").value="<?php echo $keyword1; ?>";
var check_loc_flag="<?php echo $flag_loc; ?>"
if(check_loc_flag==1){
	document.getElementById("location").value="<?php echo $location1; ?>";
	document.getElementById("location").disabled=false;
	document.getElementById("chooselocation2").checked=true;
}
document.getElementById("distance").value="<?php echo $distance1; ?>";
document.getElementById("sel").value="<?php echo $category1; ?>";

	 name_arr=[]
     place_id=[]
     address_name=[]
     map_var = null;

function sendAddressToPHP(address){   //get the destination lat and long.

		apikey="AIzaSyD10oZnvZuqjqAvHUw1VUDbNHowrumzToE";
		var url = "place.php?addressname="+address;
		var getJSONObj=function(url,callback){
	    var httpc = new XMLHttpRequest(); 
	    httpc.open("GET", url, true);
	    httpc.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");  
	    httpc.onload= function(){
    	var status=httpc.status;
    	if(status==200){
    		//alert(httpc.response);   
    		callback(null,httpc.response);
    	}
    	else{
    		callback(status,httpc.response);
    	     }
       };
    httpc.send();

}; 

 getJSONObj(url,function(err,jsonObjectReturned){

    	if(err!==null){
    		console.log("something went wrong"+ err);
    	}
    	else
    	{
    		
    		jsonObjectReturned=JSON.parse(jsonObjectReturned);
    		var destinationLatitude= jsonObjectReturned['results'][0]['geometry']['location']['lat'];
    		var destinationLongitude= jsonObjectReturned['results'][0]['geometry']['location']['lng'];
    	
    	getAllMapCoordinates(destinationLatitude,destinationLongitude); //send to this function.
    	}

    });

}

var review_text;   //review text
var photo_text;  // photo text


function expandAndCollapseData(result)
{
	printReviews="";
	flag=1 //down arrow
	printReviews+="Click to Show Reviews";
	imageText="<br><img class='arrow' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png' width='30' height='20'>"
	if(flag_zeroResults==1)
	printReviews+="<label class='collapse' for='_1'>"+ imageText+ "</p></label>"+"<input id='_1' type='checkbox'>"+"<div class='demo2'>"+"<b>No reviews found"+"</div>";
	else
		printReviews+="<label class='collapse' for='_1'>"+imageText+"</p></label>"+"<input id='_1' type='checkbox'>"+"<div>"+result[0]+"</div>";
	document.getElementById("demo").innerHTML=printReviews;
	printReviews1="<br>Click to Show Photos";
	imageText1="<br><img class='arrow' src='http://cs-server.usc.edu:45678/hw/hw6/images/arrow_down.png' width='30' height='20'>"
	if(flag_zeroPhotos==1)
	printReviews1+="<label class='collapse' for='_2'>"+ imageText1+"</label>"+"<input id='_2' type='checkbox'>"+"<div class='demo2'>"+"<b>No photos found"+"</div>";
	else
		printReviews1+="<label class='collapse' for='_2'>"+imageText1+"</label>"+"<input id='_2' type='checkbox'>"+"<div>"+result[1]+"</div>";
	document.getElementById("demo").innerHTML+=printReviews1;

}

var flag_zeroResults=0;
var flag_zeroPhotos=0;

function sendDatatoPHP(nameElement,placeid,flag) //ajax call
{
	apikey="AIzaSyD10oZnvZuqjqAvHUw1VUDbNHowrumzToE";
	var url = "place.php?placeid="+placeid;
	var getJSONObj=function(url,callback){
    var httpc = new XMLHttpRequest(); 
    httpc.open("GET", url, true);
    httpc.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");  
    httpc.onload= function(){
    	var status=httpc.status;
    	if(status==200){
    		//alert(httpc.response);
    		callback(null,httpc.response);
    	}
    	else{
    		callback(status,httpc.response);
    	     }
       };
    httpc.send();
}; 
    
    //httpc.send();
    getJSONObj(url,function(err,jsonObjectReturned){

    	if(err!==null){
    		console.log("something went wrong"+ err);
    	}
    	else
    	{
    		review_text="";
    		review_text+="<center><table border='1'>";
    		a=jsonObjectReturned;
    		console.log(a);
    		console.log(jsonObjectReturned);
    		if(jsonObjectReturned){
    		jsonObjectReturned=JSON.parse(jsonObjectReturned);
    		review_count=0;
    		flag_zeroResults=0;
    		flag_seroImages=0;
    		for(var i=0;i<5;i++)   //reviews.
    		{
    			if(typeof(jsonObjectReturned['result']['reviews'])=='undefined')
    			{
    				flag_zeroResults=1;
    				break;
	    		}

	    		if(!jsonObjectReturned['result']['reviews'][i])
	    		{
	    			break;
	    		}
    			review_count+=1;
    			if(typeof(jsonObjectReturned['result']['reviews'])!='undefined'){
    			reviews=jsonObjectReturned['result']['reviews'][i];
    			author_name=reviews['author_name'];   //author_name
    			profile_pic=reviews['profile_photo_url'];
    			//alert(profile_pic);
    			review_text+='<tr><td><img width="30" height="30" src="'+ reviews['profile_photo_url']+'">'+'<b>'+author_name+'</b></td></tr>'; 
    			comments=reviews['text'];
    			review_text+="<tr><td>"+comments+"</td></tr>";
    		}    		   //document.getElementById("demo").innerHTML=review_text;
    		   //return res_text;
    	}	
    		photo_text="<html><center><table border='1' cellpadding='10'>";
    		for(var i=0;i<5;i++){
    			if(typeof(jsonObjectReturned['result']['photos'])=='undefined')
    			{
    				//alert("undefined??")
    				flag_zeroPhotos=1;
    				break;
    			}

    			if(!jsonObjectReturned['result']['photos'][i])
    			{
    				break;
    			}
    			currentImage="image"+i+".jpg";
    			if(i==0)
    			photo_text+='<tr><td><img width="700" height="500" src="image0.jpg" onclick="window.open(this.src)">'+'</td></tr>';
    			if(i==1)
    				photo_text+='<tr><td><img width="700" height="500" src="image1.jpg" onclick="window.open(this.src)">'+'</td></tr>';
    			if(i==2)
    				photo_text+='<tr><td><img width="700" height="500" src="image2.jpg" onclick="window.open(this.src)">'+'</td></tr>';
    			if(i==3)
    				photo_text+='<tr><td><img width="700" height="500" src="image3.jpg" onclick="window.open(this.src)">'+'</td></tr>';
    			if(i==4)
    				photo_text+='<tr><td><img width="700" height="500" src="image4.jpg" onclick="window.open(this.src)">'+'</td></tr>';
    		}
    		//alert("after parsing photo!")
    		var resultArray=[];
    		//alert("pushing!");
    		resultArray.push(review_text,photo_text);
    		expandAndCollapseData(resultArray);
    	
    }
    }

    });

}
 
function redirectNames(el)
{ 
	row=el.parentNode.rowIndex;
	col=el.cellIndex;
	m=row-1
	nameOfPlace=name_arr[m]
	idOfPlace=place_id[m]
	name="<html><b>"+nameOfPlace+"</html>";
	document.getElementById("displayname").innerHTML=name;
	document.getElementById("demo").innerHTML="";
	document.getElementById("demo").innerHTML="<br>"
	sendDatatoPHP(nameOfPlace,idOfPlace);
}

function getAddress(el1) {
	row=el1.parentNode.rowIndex;
	m1=row-1;
	//alert("inside getaddress");
	addressId=address_name[m1];
	//alert(addressId);
	sendAddressToPHP(addressId);
}

var flag=0;
function on(number){
	//alert("inside on")
	 if(flag==0)
	 {
	 	document.getElementsByClassName("overlay")[number].style.display = "block";
		flag=1;
	 }
	 else
	 {
	 	document.getElementsByClassName("overlay")[number].style.display="none";
	 	flag=0;
	 }
	 map_var = number;
     //initMap();
}

function off(number){
	//alert("inside off!")
 	 document.getElementsByClassName("overlay")[number].style.display = "none";
}
</script>
<script type="text/javascript">

 var lat=<?php echo $latitude; ?>; //latitude
 var long=<?php echo $longitude; ?>; //longitude
 
 flag_displayMap=0
var destinationLatitudes;
var destinationLongitudes;
//document.getElementById("mapbarr").innerHTML=embedonMap;
function CenterControl(controlDiv, map, center,directionsService,directionsDisplay) {
        var control = this;

        control.center_ = center;
        controlDiv.style.clear = 'both';


        var walkUI = document.createElement('div6');
        walkUI.id = 'walkUI';
        //walkUI.title = 'Click to change the center of the map';
        controlDiv.appendChild(walkUI);

        var walkText = document.createElement('div6');
        walkText.id = 'walkText';
        walkText.innerHTML = 'Walk there';
        walkUI.appendChild(walkText);

        var bikeUI = document.createElement('div6');
        bikeUI.id = 'bikeUI';
       // bikeUI.title = 'Click to change the center of the map';
        controlDiv.appendChild(bikeUI);

        var bikeText = document.createElement('div6');
        bikeText.id = 'bikeText';
        bikeText.innerHTML = 'Bike there';
        bikeUI.appendChild(bikeText);

          var driveUI = document.createElement('div6');
        driveUI.id = 'driveUI';
        //driveUI.title = 'Click to recenter the map';
        controlDiv.appendChild(driveUI);

        var driveText = document.createElement('div6');
        driveText.id = 'driveText';
        driveText.innerHTML = 'Drive there';
        driveUI.appendChild(driveText);

        walkUI.addEventListener('click', function() {  // do for roadmaps

          var selectedMode = "WALKING";
        directionsService.route({
          origin: {lat: lat, lng: long},  // Haight.
          destination: {lat: destinationLatitudes, lng: destinationLongitudes},  // Ocean Beach.
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
            directionsDisplay.setDirections(response);
          } else {
           console.log('Directions request failed due to ' + status);
          }
        });
    });

        // Set up the click event listener for 'Set Center': Set the center of
        // the control to the current center of the map.
        bikeUI.addEventListener('click', function() {
         
         var selectedMode = "BICYCLING";
         directionsService.route({
          origin: {lat: lat, lng: long},
          destination: {lat:destinationLatitudes, lng: destinationLongitudes}, 
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            console.log('Directions request failed due to ' + status);
          }
        });
        });
      driveUI.addEventListener('click', function() {
         
         var selectedMode = "DRIVING";
         //alert("driving");
         directionsService.route({
          origin: {lat: lat, lng:long},
          destination: {lat:destinationLatitudes, lng: destinationLongitudes}, 
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            console.log('Directions request failed due to ' + status);
          }
        });
        });
      }

 function def(e){

 	if(e.stopPropagation){
 		e.stopPropagation();
 	}
 	else
 	{
 		e.cancelBubble=true;
 	}
 }

var directionsService;
var directionsDisplay;
 function initMap(){
 		//alert("inside init map");
       var uluru = {lat: destinationLatitudes, lng: destinationLongitudes};
        var map = new google.maps.Map(document.getElementById('text' + map_var), {
          zoom: 10,
          center: uluru,
          disableDefaultUI: true

        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });

        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService;
        directionsDisplay.setMap(map);

       var centerControlDiv = document.createElement('div6');
        var centerControl = new CenterControl(centerControlDiv, map, uluru,directionsService,directionsDisplay);

        centerControlDiv.index = 1;
        centerControlDiv.style['padding-top'] = '10px';
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);
    }


var countOfClicks=0;
function getAllMapCoordinates(destinationLatitude,destinationLongitude) // source and destination coordinates.
 {	 
 	 countOfClicks+=1;                          
 	 destinationLatitudes=destinationLatitude;
 	 destinationLongitudes=destinationLongitude;
 	 console.log("Destination coordinates" + destinationLatitude + "|" + destinationLongitude);
 	 console.log("Source coordinates" + lat + "|" + long);
 	 //alert(countOfClicks);
 	 //if(countOfClicks==1)
 	 //{
 	 initMap();
 	 //}

 }
 </script>
 <script type="text/javascript">
 	function createTable()   //need to handle case where the json object is empty.
{
  var jsonObject= <?php echo $jsonObj; ?>;
  if(jsonObject != -1) {
  	
  if(jsonObject['results'].length==0) //
  {
  	display_msg="<html><p id='emptyplaces' style='background-color:#F2F4F4;'>"+ "No Records have been found"+"</p></html>";
  	document.getElementById("demo").innerHTML=display_msg;
  	return;
  }

  console.log(jsonObject);
  var res_string="";
 
  // Headers.
   res_string+="<table style='margin-left:-2.5cm;' border='1' width='900'>";
   res_string+="<tr>";
   res_string+="<th>"+ "Category"+ "</th>";
   res_string+="<th>"+ "Name"+ "</th>";
   res_string+="<th>"+ "Address"+ "</th>";
   res_string+="</tr>";
   res_string+="<tr>";

   resultsCount=Object.keys(jsonObject['results']).length;
   for(var i=0;i<resultsCount;i++)
   {	
   		icon=jsonObject['results'][i]['icon'];
   	 	names=jsonObject['results'][i]['name'];
   	 	address=jsonObject['results'][i]['vicinity'];
   	 	placeid=jsonObject['results'][i]['place_id'];
   	 	name_arr[i]=names;
   	 	place_id[i]=placeid;
   	 	address_name[i]=address;
   	 	res_string+="<td><img src='"+icon+"'height='30' width='30'></td>";
   	 	name_str="<td onclick=redirectNames(this)>"+"<div><p>"+names+"</div></p></td>";
   	 	res_string+=name_str;
   	 	address_str="<td onclick=getAddress(this)>" + "<p onclick='on(" + i + ")'>"+ address+"</p></div>" + "<div class='overlay'>"+"<div onclick='def(event)' class='text' id='text" + i +"'></div></div><div style='padding:2px'>"+"</td>";
   	 	res_string+=address_str;
   	 	res_string+="<tr>";
   }

   document.getElementById("demo").innerHTML=res_string;
}
 }
 createTable();
</script>

 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD34j3on4cbwm6t7caNjqQA0L6dKIpuGTk">
 	</script>
</body>
</html>
