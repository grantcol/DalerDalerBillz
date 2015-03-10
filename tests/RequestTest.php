<?php 

require '../php/request.php';

class RequestTest extends PHPUnit_Framework_TestCase 
{
	/*
	 * @dataProvider searchProvider
	 */
	public function testSpQuerySearchArtist( $searchStr ) {
		$query = "https://api.spotify.com/v1/search?q=".$searchStr."*&type=artist";
		$this->assertEquals($query, spQuerySearchArtist($searchStr));
	}

	//Data Providers

	public function searchProvider() {
		return array(
			array("drake"),
			array("bon"),
			array("cool"),
			array("sam")
		);
	}
}

?>