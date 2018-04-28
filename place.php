<!DOCTYPE html>
<html>
<head>
	<title> Travel and Entertainment Search</title>
</head>
<body>

<script type="text/javascript">
	
function clearFields()  //reset fields in the form.
{
	document.getElementById("myForm").reset();
}

</script>
<?php


//key=AIzaSyBa4ASUHfQqXdxDHJShuIm5MM5N4R4R4q4
if(isset($_POST['searchbutton']))
{
	$keyword=$_POST['keyword'];
	$category=$_POST['category'];
	$distance=$_POST['distance'];
	if(isset($_POST['location']))
	{
		$location=$_POST['location'];
		echo $location." ";
	}
	elseif (isset($_POST['chooselocation'])) {
		$here=$_POST['chooselocation'];
		echo  "here";
	}
	echo $category." ";
	echo $keyword." ";
	echo $distance." ";
	//echo $chosenRadioButton." ";
}


function googlePlaces($input_array)
{
	//echo "inside the arrau!";
	//var_dump($input_array);

	$APIKey=$input_array[0];
	$latitude=$input_array[1];
	$longitude=$input_array[2];
	$radius=$input_array[3];
	$type=$input_array[4];
	$keyword=$input_array[5];
	$googlePlacesURL="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$latitude,$longitude&radius=$radius&type=$type&keyword=$keyword&key= $APIKey";
	echo $googlePlacesURL;



} 


if(isset($_POST['location']))  // IF LOCATION IS CHOSEN.
{
	$location=$_POST['location'];
	$APIKey="AIzaSyBa4ASUHfQqXdxDHJShuIm5MM5N4R4R4q4";
	//echo $APIKey;
	$string = str_replace (" ", "+", urlencode($location));
	//header('Content-Type: application/json');
	$url="https://maps.googleapis.com/maps/api/geocode/json?address=".$string."+CA&key=$APIKey";
	echo $url;
	$jsonData=json_decode(@file_get_contents($url),JSON_PRETTY_PRINT);


	// The following are input parameters to the google places api function.

	$latitude=$jsonData['results'][0]['geometry']['location']['lat'];
	$longitude=$jsonData['results'][0]['geometry']['location']['lng'];
	$radius=$distance;
	$type= $category;

	//inputs are: key,latitude, longitude,radius,type, keyword.

	//var_dump( $latitude);
	//var_dump($longitude);
	//var_dump($jsonData);
	$input_array=array();
	array_push($input_array, $APIKey,$latitude,$longitude,$radius,$type,$keyword);
	var_dump($input_array);
	googlePlaces($input_array);


}

?>
<div style="border: 1px solid #000; text-align: center; width: 700px;height: 250px; margin-left: 7cm; background-color: #E8E8E8;	">
	<font face="verdana"><p style="font-family:sans-serif;font-style: italic; font-size: 18pt; margin-top: -0.05px;">Travel and Entertainment Search</p></font>

	<form id="myForm" action="place.php" method="post" style="margin-left: -6.5cm;">

	Keyword <input type="text" name="keyword" required="True"> 
	<br>
    <p style="margin-left:-1.5cm;">Category <select name="category" placeholder="default">
		<option> default</option>
		<option> cafe </option>
		<option> bakery </option>
		<option>  restaurant</option>
		<option>beauty salon </option>
		<option> casino </option>
		<option> movie theatre </option>
		<option> lodging </option>
		<option> airport </option>
		<option> train station </option>
		<option> subway station</option>
		<option>bus station</option>
		
	</select>
	<br>
	<p style="margin-left: 140px; font-style: bold;">Distance (miles) <input type="text" name="distance" placeholder="10">
	from 
	<input type="radio" name="chooselocation" required=True; value="here">Here <br> </p>
	<input type="radio" name="chooselocation" style="margin-left: 15.7cm;" value="location"> <input type="text" name="location" placeholder="location">
	<br>
	<input type="submit" name="searchbutton" value="Search" style="margin-left: -0.1cm" onclick="validateform()">
	<input type="button" name="clearbutton" value="Clear" onclick="clearFields()"> 



	</form>
</div>
</body>
</html>