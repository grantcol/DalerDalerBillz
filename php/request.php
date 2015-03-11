<?php
include 'core.php';
session_id('request');
session_start();
// Query Helpers

//Query type factory
function getQuery( $type,  $qArg1, $qArg2 = "none" ) {
	if( $type == "search" ) { return spQuerySearchArtist( $qArg1 ); }
	else if( $type == "track" ) { return spQueryGetArtist( $qArg1 ); }
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

function azlGetLyrics( $artist, $name ) {
	$retVal = null;
	$url = "http://www.azlyrics.com/lyrics/".azlSanitize($artist)."/".azlSanitize($name).".html";
	//echo $url;
	$contents = file_get_contents($url);
	//check if contents is valid or not 
	//contents should just be a string so we can cut out 
	//most of the DOM preceeding the meat and everything after
	$splitBody = explode("<!-- start of lyrics -->", $contents);
	if($splitBody[0] != $contents) {
		//we got good lyrics 
		//echo $splitBody;
		//split it again on the end comment for just the lyrics
		$lyrics = explode("<!-- end of lyrics -->", $splitBody[1]);
		//echo $lyrics;
		//clean up the br tags in the lyrics
		//var_dump($lyrics[0]);
		$lyricsClean = preg_replace("((<br>)|(<br \/>)|(<i>)|(<\/i>)|(\"))", '', $lyrics[0]);
		$lyricsCleanTrim = trim($lyricsClean, "\r\n");
		/*if($lyricsCleanTrim == $lyricsClean) { echo "same"; }
		else { echo $lyricsCleanTrim; echo 'r....'; echo $lyricsClean; }*/
		//echo $lyricsClean;
		$retVal = $lyricsCleanTrim;
	}
	return $retVal;
} 

//Santize the hint string given by ajax.
//Making this a reusable function just in case
//This implementation formats for spotify web api
function spSanitize( $request ) {
	//spotify uses '+' to escape white space
	return str_replace(" ", "+", $request);
}

function azlSanitize( $str ) {
	$str = strtolower($str);
	return str_replace(" ", "", $str);
}

function getParser( $type, $data ) {
	if( $type == "search" ) { parseSpResponse( $data ) ; }
	else if( $type == "track" ) { parseSpTopTracksResponse( $data ); }
}

//Decode JSON response from external API. Expects a JSON encoded string
//Need this to return usable, HTML ready strings to asking js function. 
function parseSpResponse( $data ) {
	//Decode JSON obj returned from SWA. 
	//2nd param is true for assoc array ret val
	$dataArr = json_decode($data, true);
	foreach($dataArr["artists"]["items"] as $d) {
		$artistName = str_replace($_POST['hintStr'], '<b>'.$_POST['hintStr'].'</b>', $d["name"]);
		echo '<li id="'.$d["id"].'" onclick="setSelect(\''.str_replace("'", "\'", $d["name"]).'\', \''.$d["id"].'\')">'.$artistName.'</li>';
	}
}

//It through the list of top tracks and query azl for lyrics to each song
//add song object to artists mSongs list for future reference
//save the artist to $_SESSION for access on later pages
function parseSpTopTracksResponse( $data ) {
	$dataArr = json_decode($data, true);
	$artist = new Artist($_POST["hintStr"]);
	$tracks = array();
	//var_dump($artist);
	//echo $dataArr["tracks"][0]["name"];
	//var_dump($dataArr["tracks"][0]);
	foreach( $dataArr["tracks"] as $track ) {
		$t = new Song($track["name"], $artist, null);
		$lyrics = azlGetLyrics(azlSanitize($artist->mName), azlSanitize($t->mName));
		//echo $lyrics;
		if($lyrics != null) {
			//explode lyrics string into array
			// but before exploding we need to strip carriage returns, newlines, and commas
			$cr_nl_c_pattern = "((\n)|(\r)|(\t)|(,))";
			$t_lyrics = preg_replace($cr_nl_c_pattern, " ", $lyrics);
			$s = explode(" ", $t_lyrics);
			$t->setLyrics($s);
			$t->parseLyrics();
			$tracks[$t->mName] = $t;
		}
	}
	$artist->setSongs($tracks);
	$cloud = new Cloud($artist);
	$_SESSION['artist'] = $artist;
	echo json_encode(array('cloud_string' => $cloud->mHtml, 'tracks' => json_encode($tracks)));
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

$DEBUG = false;
$hintStr;
$reqType;
$artistId;
if(!$DEBUG){
	$hintStr = spSanitize($_POST['hintStr']);
	$reqType = $_POST["reqType"];
	$artistId = ($reqType == "track") ? $_POST["artistId"] : $hintStr;	
}
else{
	$hintStr = spSanitize($_GET['hintStr']);
	$reqType = "track";//$_POST["reqType"];
	$artistId = ($reqType == "track") ? $_GET["artistId"] : $hintStr;
} 

//this is a bit hacky. sorry
//if we have the track request type we dont need the hintstr since that is only for autocomplete
//so we will set artistid to the posted artist id since the getQuerymethod doesnt have context of it's second param
//else we can just set artistid to be hintstr.
$query = getQuery($reqType, $artistId);
$responseData = execRequest($query);
getParser($reqType, $responseData);
?>