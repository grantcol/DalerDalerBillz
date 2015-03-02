<?php 
class Artist
{
	public $mName;
	public $mSpId;
	public $mImgUrls;
	public $mGenre;
	public $mSongs;

	function __construct($name) {
		$this->mName = $name;//$name;
		$this->mSpId = "";//$id;
		$this->mImgUrls = "";//$img;
		$this->mGenre = "";//$genre;
		$this->mSongs = "";//$songs;
	}

	public function setSongs($songArr) {
		$this->mSongs = $songArr;
	}

	public function getFrequency($word) {
		$freq = array();
		foreach($this->mSongs as $key => $value) {
			$freq[$key] = $value->getFrequency($word); 
		}
		return $freq;
	}

	public function getSongId($songName) {
		return $this->mSongs[$songName]->mId;
	}

}

class Song
{
	public $mName;
	public $mAlbum;
	public $mArtist;
	public $mLyrics;
	public $mWords;
	public $mId;

	private $mBlackList = array(
									'fuck', 'shit', 'ass', 'bitch', 'damn', 'nigga',
									'I', 'as', 'a', 'the'
								);

	public function __construct($name, $artist, $id) {
		$this->mName = $name;
		$this->mArtist = $artist;
		$this->mId = $id;
		$this->mLyrics = null;
	}

	public function setLyrics($lyrics) {
		$this->mLyrics = $lyrics;
	}

	public function parseLyrics() {
		if($this->mLyrics == null){
			$fileContents = file_get_contents("../tests/latch.txt");
			if($fileContents != FALSE) {
				$this->mLyrics = explode(" ", $fileContents);
			}
		}
		foreach($this->mLyrics as $l) {
			if(!in_array($l, $this->mBlackList)) {
				$this->mWords[$l] += 1;
			}
		}
	}

	public function getFrequency($word) {
		return $this->mWords[$word];
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
	public $mHtml;

	public function __construct($artist) {
		$this->mArtist = $artist;
		$this->mHtml = $this->generateCloud();
	}
	//Loop through mArtist's list of songs and find the most frequent words
	public function findMostFrequent() {
		$aWords = $this->mArtist->mSongs;
		$words = array();
		foreach($aWords as $key => $value){
			$amw = $value->mWords;
			foreach($amw as $key => $value) {
				$words[$key] = $value;
			}
		}
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
			$this->shuffle_assoc($this->mWords);
			foreach( $this->mWords as $word => $freq ) {
				//echo $this->calcSize($freq);
				$html .= "<span class='".$this->calcSize($freq)."'><a href='html/songTitle.php?wordRef=".$word."'>".$word."</a></span> ";
			}
		}
		else {
			echo "failed";
		}
		return $html;
	}

	public function shuffle_assoc(&$array) {
	    $keys = array_keys($array);
	    shuffle($keys);
	    foreach($keys as $key) {
	        $new[$key] = $array[$key];
	    }
	    $array = $new;
	    return true;
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