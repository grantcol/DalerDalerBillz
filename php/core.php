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
	public $mLyrics;
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