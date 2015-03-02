<?php 
class Artist
{
	public $mName;
	public $mSpId;
	public $mImgUrls;
	public $mGenre;
	public $mSongs;

	function __construct() {
		$mName = "";//$name;
		$mSpId = "";//$id;
		$mImgUrls = "";//$img;
		$mGenre = "";//$genre;
		$mSongs = "";//$songs;
	}

	public function setSongs($songArr) {
		
		$this->mSongs = $songArr;
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
		$this->mName = $name;
		$this->mArtist = $artist;
	}

	public function setLyrics($lyrics) {
		$this->mLyrics = $lyrics;
	}

	public function parseLyrics() {
		$fileContents = file_get_contents("../tests/legend.txt");
		if($fileContents != FALSE) {
			$this->mLyrics = explode(" ", $fileContents);
			foreach($this->mLyrics as $l) {
				if(!in_array($l, $this->mBlackList)) {
					$this->mWords[$l] += 1;
				}
			}
			//var_dump($this->mWords);
		}
		else {
			echo "failed";
			$this->mLyrics = NULL;
			$this->mWords  = NULL;
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

	public function __construct($artist) {
		$this->mArtist = $artist;
		$this->generateCloud();
	}
	//Loop through mArtist's list of songs and find the most frequent words
	public function findMostFrequent() {
		$words = $this->mArtist->mSongs->mWords;
		$sorted = arsort($words);
		if( $sorted ) {
			//arr sorted by value choose top 
			if( count($words) > 250 ) {
				//slice the array to be 250 or less
				array_slice($words, 0, 250);
			}
			$this->mWords = $words;
			return TRUE;
		}
		return FALSE;
	}

	public function generateCloud() {
		if($this->findMostFrequent()) {
			//$mWords is now sorted and cleared for use
			//Loop through each pair and find out the freq
			//Choose a size falue based on the freq of the word
			//For now, until css is complete just add the word at standard size
			foreach( $this->mWords as $word => $freq ) {
				//echo $this->calcSize($freq);
				echo "<span class='dummy' style='font-size: ".$this->calcSize($freq).";'><a href='#'>".$word."</a></span>";
			}
		}
		else {
			echo "failed";
		}
	}

	public function calcSize( $wordFreq ) {
		$minSize = 10;
		$midSize = 15;
		$maxSize = 25;

		if($wordFreq < 5){
			return "cloud-word-big";
		}
		else if($wordFreq > 5 && $wordFreq < 10) {
			return "cloud-word-medium";
		}
		else {
			return "cloud-word-small";
		}

	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title> LyricsCloud </title>
<link rel="stylesheet" href="../css/lyricscloud.css">
</head>
	<body>
		<?php 
			$testSong = new Song();
			$testSong->parseLyrics();
			$testArtist = new Artist();
			$testArtist->setSongs($testSong);
			$testCloud = new Cloud($testArtist);
		?>
	</body>
</html>
