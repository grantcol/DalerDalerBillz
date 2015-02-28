<?php 
class Artist
{
	public $mName;
	public $mSpId;
	public $mImgUrls;
	public $mGenre;
	public $mSongs;

	function __construct($name, $id, $img, $genre, $songs) {
		$mName = $name;
		$mSpId = $id;
		$mImgUrls = $img;
		$mGenre = $genre;
		$mSongs = $songs;
	}

	public function setSongs($songArr) {
		$songs = $songArr;
	}

}

class Song
{
	public $mName;
	public $mAlbum;
	public $mArtist;
	public $mLyrics;
	public $mWords;

	private $mBlackList = array(
									'fuck', 'shit', 'ass', 'bitch', 'damn', 'nigga',
									'I', 'as', 'a', 'the'
								);

	public function __construct($name, $artist) {
		$mName = $name;
		$mArtist = $artist;
	}

	public function setLyrics($lyrics) {
		$mLyrics = $lyrics;
	}

	public function parseLyrics() {
		$fileContents = file_get_contents("../tests/legend.txt");
		if($fileContents != FALSE) {
			$mLyrics = explode(" ", $fileContents);
			foreach($mLyrics as $l) {
				if(!in_array($l, $mBlackList)) {
					$mWords[$l] += 1;
				}
			}
		}
		else {
			echo "failed";
			$mLyrics = NULL;
			$mWords  = NULL;
		}
	}

	public function getFrequency($word) {
		return $mWords[$word];
	}
}

class Album 
{
	public $mTitle;
	public $mArtist;
	public $mCoverImg;
}

class Word 
{
	public $mWord;
	public $mSize;
	public $mFreq;
	public $mSong;
}

class Cloud
{
	public $mArtist;
	public $mWords;

	//Loop through mArtist's list of songs and find the most frequent words
	public function findMostFrequent() {
		$words = $mArtist->$mSongs->$mWords
		$sorted = arsort($words);
		if( $sorted ) {
			//arr sorted by value choose top 
			if( count($words) > 250 ) {
				//slice the array to be 250 or less
				array_slice($words, 0, 250);
			}
			$mWords = $words;
			return TRUE;
		}
		return FALSE;
	}

	public function generateCloud() {
		if(findMostFrequent()) {
			//$mWords is now sorted and cleared for use
			//Loop through each pair and find out the freq
			//Choose a size falue based on the freq of the word
			//For now, until css is complete just add the word at standard size
			foreach( $mWords as $word => $freq ) {
				echo "<span class='cloudWord'><a href="'#'">".$word."</a></span>";
			}
		}
	}
}
?>
<!--<!DOCTYPE html>
<html>
<head>
<title> LyricsCloud </title>
</head>
	<body>
		<?php 
			$testSong = new Song();
			$testSong->parseLyrics();
		?>
	</body>
</html>-->