<?php

// Query Helpers

//Query type factory
function getQuery( $type,  $qArg1, $qArg2 = "none" ) {
	if( $type == "search" ) { return spQuerySearchArtist( $qArg1 ) ; }
	else if( $type == "artist" ) { return spQueryGetArtist( $qArg1 ); }
	//else if( $type == "songs" && $qArg2 != "none" ) { return enQuerySearchSong( $qArg1, $qArg2 ); }
} 

function spQuerySearchArtist( $searchStr ) {
	//sp web API limits queries to 2 wildcard (*) ops per call
	//so we'll append just one to end string just in case
	$spSearchEndpoint = "https://api.spotify.com/v1/search?q=";
	return $spSearchEndpoint.$searchStr."*&type=artist";
}

function spQueryGetArtist( $artistId ) {
	$spSearchArtistEndPoint = "https://api.spotify.com/v1/artists/";
	return $spSearchArtistEndPoint.$artistId."/top-tracks?country=US";
}  

function enQuerySearchSong( $artist, $song ) {
	$lyricFindBucketId 		= "bucket=id:lyricfind-US&limit=true&bucket=tracks";
	$enApiKey 				= "6DJYT8JMVXSOHNC92";
	$enResponseFormat 		= "&format=json";
	$enSearchArtist			= "artist=".$artist;
	$enSearchTitle 			= "title=".$song;
	$enSongSearchEndPoint 	= "http://developer.echonest.com/api/v4/song/search?api_key=";
	return $enSongSearchEndPoint.$enApiKey.$enResponseFormat."&".$enSearchArtist."&".$enSearchTitle."&".$lyricFindBucketId;
} 

//Santize the hint string given by ajax.
//Making this a reusable function just in case
//This implementation formats for spotify web api
function spSanitize( $request ) {
	//spotify uses '+' to escape white space
	return str_replace(" ", "+", $request);
}

function getParser( $type, $data ) {
	if( $type == "search" ) { parseSpResponse( $data ) ; }
	else if( $type == "artist" ) { parseEnResponse( $data ); }
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

function parseEnResponse( $data ) {
	$dataArr 		= json_decode($data, true);
	$catalog 		= $dataArr["catalog"];
	$lcForeignId 	= $dataArr["foreign_id"];
	if($catalog == "lyricfind-US") { return $lcForeignId; }
	return null;
}

function execRequest( $query ) {
	//Set up a cURL resource for the semantic request
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $query
	));
	$responseData = curl_exec($curl);
	curl_close($curl);
	return $responseData;
}

$hintStr = spSanitize($_POST['hintStr']);
$reqType = $_POST["reqType"];
$query = getQuery($reqType, $hintStr);
$responseData = execRequest($query);
getParser($reqType, $responseData);

?>