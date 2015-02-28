<?php

// Query Helpers

//Query type factory
function getQuery( $type,  $str ) {
	if( $type == "search" ) { return spQuerySearchArtist( $str ) ; }
	else if( $type == "artist" ) { return spQueryGetArtist( $str ); }
} 

function spQuerySearchArtist( $searchStr ) {
	//sp web API limits queries to 2 wildcard (*) ops per call
	//so we'll append just one to end string just in case
	$spSearchEndpoint = "https://api.spotify.com/v1/search?q=";
	return $spSearchEndpoint.$searchStr."*&type=artist";
}

function spQueryGetArtist( $artistId ) {
	$spSearchArtistEndPoint = "https://api.spotify.com/v1/artists/";
	return $spSearchArtistEndPoint.$artistId;
}  

//Santize the hint string given by ajax.
//Making this a reusable function just in case
//This implementation formats for spotify web api
function spSanitize( $request ) {
	//spotify uses '+' to escape white space
	return str_replace(" ", "+", $request);
}

//Decode JSON response from external API. Expects a JSON encoded string
//Need this to return usable, HTML ready strings to asking js function. 
function parseSpResponse( $data ) {
	//Decode JSON obj returned from SWA. 
	//2nd param is true for assoc array ret val
	$dataArr = json_decode($data, true);
	foreach($dataArr["artists"]["items"] as $d) {
		$artistName = str_replace($_POST['hintStr'], '<b>'.$_POST['hintStr'].'</b>', $d["name"]);
		echo '<li id="'.$d["id"].'" onclick="setSelect(\''.str_replace("'", "\'", $d["name"]).'\')"><a href="html/cloudpage.html?artistId='.$d["id"].'">'.$artistName.'</a></li>';
	}
}

$hintStr = spSanitize($_POST['hintStr']);
$reqType = "search";//$_POST["reqType"];
$spQueryStr = getQuery($reqType, $hintStr);

//Set up a cURL resource for the semantic request
$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => $spQueryStr
));

$spArtistData = curl_exec($curl);

curl_close($curl);

parseSpResponse($spArtistData);


?>