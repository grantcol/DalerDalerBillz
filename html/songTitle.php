<?php 
include '../php/core.php';
session_start();

function azlSanitize( $str ) {
	$str = strtolower($str);
	return str_replace(" ", "", $str);
}

$word = $_GET['wordRef'];
$freq = null;
$artist = $_SESSION['artist'];
$freq = $artist->getFrequency($word);
var_dump($freq);
?>
<!DOCTYPE html>
<html>
<head>
<link href="../css/lyricscloud.css" rel="stylesheet" type="text/css"/>
	<style>
		body {
			background-color: #bfbfbf;
		}
		h3{
			font-family: Helvetica;

		}
		table {
   			 width: 100%;
   			 font-family: Helvetica;
		}
		th {
    		height: 50px;
    		font-family: Helvetica; 
		}
		button{
			font-family:Helvetica;
			background-color:#bf55ec;
			border-color:#bf55ec;
			height: 30px;
			width: 70px;
		}

	</style>
	<title>Song Title Page</title>
		<center><h3>Search Word</h3></center>
</head>

<body>
	<table>
		<tr>
			<th>Song Name</th>
			<th>Word Frequency</th>
		</tr>
		<!--<tr>
			<th>Song 1</th>
			<th>Word Frequency</th>
		</tr>
		<tr>
			<th>Song 2</th>
			<th>Word Frequency</th>
		</tr>
		<tr>
			<th>Song 3</th>
			<th>Word Frequency</th>
		</tr>
		<tr>
			<th>Song 4</th>
			<th>Word Frequency</th>
		</tr>-->
		<?php 
		if(count($freq) > 0){
			foreach($freq as $key => $value){
				echo '<tr><th><a href="lyricPage.php?word='.$word.'&track_title='.$key.'&track_id='.$artist->getSongId($key).'&artist='.$artist->mName.'">'.$key.'</a></th><th>'.$value.'</th></tr>';
			}
		}
		?>
	</table>

<center><button>Back</button></center>
</body>
</html>