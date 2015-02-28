<?php 

class Artist
{
	public $name;
	public $spId;
	public $imgUrls;
	public $genre;
	public $songs;

	function __construct($n, $id, $img, $g, $s) {
		$name = $n;
		$spId = $id;
		$imgUrls = $img;
		$genre = $g;
		$songs = $s;
	}

	function setSongs($songArr) {
		$songs = $songArr;
	}

}

class Song
{
	public $name;
	public $album;
	public $artist;
	public $freq;
}

class Album 
{
	public $title;
	public $artist;
}
?>