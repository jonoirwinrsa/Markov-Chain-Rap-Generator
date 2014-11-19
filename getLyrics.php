<?php 
include 'rapgenius.php';

$albums = album_list('Kanye-West');
//print_r ($albums);

foreach($albums as $album){
	$tracklist[] = (tracklist($album['link']));
	//break;
	}
	
	$lyrics = '';
	
foreach($tracklist as $track){
	$lyrics .= lyrics($track[0]['link'], false).'<br>';
	//$idlist[] = $track[0]['id'];
	}

	$lyrics = preg_replace('/\[.*?\]/', '', $lyrics);
	$lyrics = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $lyrics);
	echo $lyrics;
	
?>