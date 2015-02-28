<?php 

class Artist
{
	public $name;
	public $spId;
	public $imgUrls;
	public $genre;
	public $songs;
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