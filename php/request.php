<?php
include 'core.php';
session_id('request');
session_start();
// Query Helpers

//Query type factory
function getQuery( $type,  $qArg1, $qArg2 = "none" ) {
	if( $type == "search" ) { return spQuerySearchArtist( $qArg1 ) ; }
	else if( $type == "artist" ) { return mmQuerySearchArtists( $qArg1 ); }
	else if( $type == "track" ) { return mmQueryGetArtistTracks( $qArg1 ); }
	else if( $type == "lyrics" ) { return mmQueryGetLyrics( $qArg1 ); }
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

function mmQuerySearchArtists( $artistName ) {
	$mmApiKey = "5a9df367bba4f12c95e7ba3111d410c6";
	$mmApiRootUrl = "http://api.musixmatch.com/ws/1.1/";
	$mmApiArtistSearchEndpoint = "artist.search?";
	$mmArtist = "q_artist=".$artistName;
	$mmPageSize = "page_size=1";
	$mmQuery = $mmApiRootUrl."apikey=".$mmApiKey."&".$mmApiArtistSearchEndpoint.$mmArtist."&".$mmPageSize;
	return $mmQuery;
}

function mmQueryGetArtistTracks( $artist ) {
	$mmApiKey = "5a9df367bba4f12c95e7ba3111d410c6";
	$mmApiRootUrl = "http://api.musixmatch.com/ws/1.1/";
	$mmArtistTracksEndpoint = "track.search?";
	$mmArtist = "q_artist=".$artist;
	$mmQuery = $mmApiRootUrl.$mmArtistTracksEndpoint."apikey=".$mmApiKey."&".$mmArtist."&f_has_lyrics=1";
	return $mmQuery;
}

function mmQueryGetLyrics( $trackId ) {
	$mmApiKey = "5a9df367bba4f12c95e7ba3111d410c6";
	$mmApiRootUrl = "http://api.musixmatch.com/ws/1.1/";
	$mmLyricsEndpoint = "track.lyrics.get?";
	$mmTrackId = "track_id=".$trackId;
	$mmQuery = $mmApiRootUrl.$mmLyricsEndpoint."apikey=".$mmApiKey."&".$mmTrackId;
	return $mmQuery;
}

function azlGetLyrics( $artist, $name ) {
	$retVal = null;
	$url = "http://www.azlryics.com/lyrics/".$artist."/".$name.".html";
	$contents = file_get_contents($url);
	//check if contents is valid or not 
	//contents should just be a string so we can cut out 
	//most of the DOM preceeding the meat and everything after
	$splitBody = explode("<!-- start of lyrics -->", $contents);
	if($splitBody[0] != $contents) {
		//we got good lyrics 
		echo $splitBody;
		//split it again on the end comment for just the lyrics
		$lyrics = explode("<!-- end of lyrics -->", $splitBody[1]);
		echo $lyrics;
		//clean up the br tags in the lyrics
		$lyricsClean = str_replace("<br>", "", $lyrics);
		$retVal = $lyricsClean;
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

function mmSanitize( $request ) {
	return str_replace(" ", "%20", $request);
}

function getParser( $type, $data ) {
	if( $type == "search" ) { parseSpResponse( $data ) ; }
	else if( $type == "artist" ) { parseEnResponse( $data ); }
	else if( $type == "track" ) { parseMmResponse( $data ); }
	else if( $type == "lyrics" ) { parseMmResponse( $data ); }
}

//Decode JSON response from external API. Expects a JSON encoded string
//Need this to return usable, HTML ready strings to asking js function. 
function parseSpResponse( $data ) {
	//Decode JSON obj returned from SWA. 
	//2nd param is true for assoc array ret val
	$dataArr = json_decode($data, true);
	foreach($dataArr["artists"]["items"] as $d) {
		$artistName = str_replace($_POST['hintStr'], '<b>'.$_POST['hintStr'].'</b>', $d["name"]);
		echo '<li id="'.$d["id"].'" onclick="setSelect(\''.str_replace("'", "\'", $d["name"]).'\')">'.$artistName.'</li>';
	}
}

function parseEnResponse( $data ) {
	$dataArr 		= json_decode($data, true);
	$catalog 		= $dataArr["catalog"];
	$lcForeignId 	= $dataArr["foreign_id"];
	if($catalog == "lyricfind-US") { return $lcForeignId; }
	return null;
}

function parseMmResponse( $data ) {
	$dataArr = json_decode($data, true);
	$artist = new Artist($_POST['hintStr']);
	$tracks = array();
	foreach( $dataArr['message']['body']['track_list'] as $track ) {
		$t = new Song($track['track']['track_name'], $artist, $track['track']['track_id']);
		$lyricsQuery = mmQueryGetLyrics($track['track']['track_id']);
		$response = execRequest($lyricsQuery);
		$response = json_decode($lyrics, true);
		$lyrics = $response["message"]["body"]["lyrics"]["lyrics_body"];
		$t->setLyrics($lyrics);
		$t->parseLyrics();
		$tracks[$t->mName] = $t;
	}
	$artist->setSongs($tracks);
	$cloud = new Cloud($artist);
	$_SESSION['artist'] = $artist;
	echo json_encode(array('cloud_string' => $cloud->mHtml, 'tracks' => json_encode($tracks)));
}

function parseMmLyricsResponse( $data ) {
	$dataArr = json_decode($data, true);
	$artist = $_SESSION['artist'];

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