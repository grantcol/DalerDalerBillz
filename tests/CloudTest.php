<?php 

require 'core.php';

class CloudTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider arrayProvider
	 */
	public function testShuffleAssoc( $array ) {
		$arrCopy = $array;
		$cloud = new Cloud(new Artist("test"), TRUE);
		$this->assertNotEquals($arrCopy, $cloud->shuffle_assoc($array));
	}

	/**
	 * @dataProvider freqProvider
	 */
	public function testSetFrequency( $freq, $expected ) {
		$cloud = new Cloud(new Artist("test"), TRUE);
		$this->assertEquals($expected, $cloud->calcSize($freq));
	}

	//Data Providers

	public function arrayProvider() {
		return array(
			array(array("hi" => 2, "bye"=> 10, "low"=> 2,"high"=> 20,"coco"=> 5,"bread"=> 3, "fall"=> 7,"break"=> 1,
			"yolo"=> 4, "selfie"=> 21 )),
			array(array("blah" => 2, "prah"=> 14, "main" => 4, "kate"=> 33, "read"=> 2, "Frank"=> 2, "blue"=> 2,"red"=> 2,
			"flow"=> 23, "grow"=> 24)),
			array(array("selfish"=> 2, "cry"=> 2, "solo"=> 3, "human"=> 24,"clue"=> 12,"vein"=> 2, "valid"=> 5,"wrong"=> 3,
			"mobile"=> 2, "quote"=> 7))
		);
	}

	//This provider can be reused in the SongTest class
	public function freqProvider() {
		return array(
			array( 25, "cloud-word-big" ),
			array( 2, "cloud-word-small" ),
			array( 10, "cloud-word-medium" ),
			array( 15, "cloud-word-big" ),
			array( 5, "cloud-word-medium" ),
			array( 7, "cloud-word-medium" ),
			array( 0, "cloud-word-small" ),
			array( 1, "cloud-word-small" )
		);
	}
}

?>