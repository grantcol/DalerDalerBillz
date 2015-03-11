<?php 
session_start();

function azlGetLyrics( $artist, $name ) {
	$retVal = "NO LYRICS AT ";
	$url = "http://www.azlyrics.com/lyrics/".azlSanitize($artist)."/".azlSanitize($name).".html";
	$retVal .= $url;
	$contents = file_get_contents($url);
	$splitBody = explode("<!-- start of lyrics -->", $contents);
	if($splitBody[0] != $contents) {
		$retVal = "";
		$lyrics = explode("<!-- end of lyrics -->", $splitBody[1]);
		$lyricsClean = preg_replace("((<br>)|(<br \/>)|(<i>)|(<\/i>)|(\"))", '', $lyrics[0]);
		$lyricsCleanTrim = trim($lyricsClean, "\r\n");
		$retVal = $lyricsCleanTrim;
	}
	return $retVal;
} 

function azlSanitize( $str ) {
	$str = strtolower($str);
	return str_replace(" ", "", $str);
}

$t_id = $_GET['track_id'];
$t_title = $_GET['track_title'];
$artist = $_GET['artist'];
$word = $_GET['word'];
$body = azlGetLyrics(azlSanitize($artist), azlSanitize($t_title));
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
		.highlight
		{
			background-color: #F3F315;
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
</body>
</html>