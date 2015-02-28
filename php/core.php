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

	public function parseLyrics() {}
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