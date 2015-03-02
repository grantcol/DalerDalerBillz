<?php 
session_start();

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

$t_id = $_GET['track_id'];
$t_title = $_GET['track_title'];
$artist = $_GET['artist'];
$word = $_GET['word'];
$mmApiKey = "5a9df367bba4f12c95e7ba3111d410c6";
$mmQuery = "http://api.musixmatch.com/ws/1.1/track.lyrics.get?apikey=".$mmApiKey."&track_id=".$t_id;
$response = execRequest($mmQuery);
$response = json_decode($response, true);
$body = $response["message"]["body"]["lyrics"]["lyrics_body"];
$bodyHigh = str_replace($word, "<span class='highlight'>".$word."</span>", $body);
?>
<!DOCTYPE html>
<html>
<head>
<link href="../css/lyricscloud.css" rel="stylesheet" type="text/css"/>

	<style>
		body {
			background-color: #bfbfbf;
		}
		h3,h2{
			font-family: Helvetica;
		}
		.lyrics{
			background-color: #ececec;
			height: 550px;
			width: 800px;
		}
		button{
			background-color: #bf55ec;
      		border-color: #bf55ec;
		    font-family: Helvetica;
		}


	</style>
	<title>Lyric Page</title>
		<h2><?php echo $t_title ?></h2>
		<h3><?php echo $artist ?></h3>
</head>

<body>
	<center><div class = "lyrics">
		<?php 
			echo '<p>'.$bodyHigh.'</p>';
		?>
	</div></center>
	<center></br></br>
		<button style = "width: 90px;height: 50px;">Song List</button>
		<button style = "width: 90px;height: 50px;">Word Cloud </button>
	</center>
	<script type="text/javascript" src="http://tracking.musixmatch.com/t1.0/AMa6hJCIEzn1v8RuOP"></script>
</body>
</html>