<?php 

/* 
	Author: David I. Martinez
	Email: dhavhid@gmail.com
	Source: http://www.bandsintown.com/api/overview
	- This script is used to get a full list of events from Bandsintown API 
*/
class Bandsintown {

	private $api_url = "http://api.bandsintown.com/artists/";
	private $format = "json"; //preferred format as usuall :)
	private $api_version = "2.0"; // this is the latest version.
	private $app_id = "SOCIAL"; // app_id can be any string.
	
	/*
		Objetive: This method is the one that queries the API service. 
		Paramas: It receive a name of an artist.
		Return: It returns a JSON object if succesful query or FALSE on failure
	*/
	public function eventsByArtist( $artist_name, $degub_mode = FALSE ){
		
		// We validate first that we received an artist name, if not return FALSE
		if( empty($artist_name) )return FALSE;
		// It is important to avoid all strange characters in the artist's name
		$artist_name = $this->parseName($artist_name);
		
		$url = "{$this->api_url}{$artist_name}/events.{$this->format}?api_version={$this->api_version}&app_id={$this->app_id}";
		if( $degub_mode )echo $url;
		
		// Connect through cURL
		$link = curl_init();
		curl_setopt($link, CURLOPT_URL, $url );
		curl_setopt($link, CURLOPT_HEADER, FALSE);
		curl_setopt($link, CURLOPT_RETURNTRANSFER, true); 
		$response = curl_exec($link);
		curl_close($link);
		
		
		if( !empty($response) || isset($response) )return $response;
		else return FALSE;
		
		
	}
	
	
	// This function parses the artist's name to avoid non standard characters.
	public function parseName( $name ){
		$name = chop($name);
		$name = strtolower($name);
		$name = str_replace(array('á','é','í','ó','ú','ä','ë','ï','ö','ü','ñ','"',' ','-'), array('a','e','i','o','u','a','e','i','o','u','n','','%20',''), $name);
		return $name;
	}
	
}

/*
	Implementation
	We can send a GET request with an artist name as a param.
*/

$band = new Bandsintown;

// If not an artist available we set one for testing.
if( isset($_GET['artist_name']) ){ //when running from web browser vi HTTP GET request
	$artist_name = $_GET['artist_name'];
}elseif( isset($argv[1]) ){ // when running via CLI with arguments like: Selena Gomez, Justin Bieber, etc. ( Feel free to use names with spaces )
	unset($argv[0]);
	$artist_name = implode(' ', $argv);
}else{
	$artist_name = 'Alicia%20Keys';
}
	
header('Content-Type: application/json');	
print_r($band->eventsByArtist($artist_name));

?>