<?php
//	WordPress Son Konular Cache (json.json)
//	13.05.2019 - github.com/zkcplk
	
$cfile = dirname (__FILE__) . '/json.json';
	
if (file_exists($cfile) && $_SERVER['REQUEST_TIME'] - $ctime < filemtime($cfile)) echo file_get_contents($cfile);
else {
	require_once("wp-config.php");
	require_once("wp-includes/wp-db.php");
		
	$konu = 20;
	$offset = 0;
	$ctime = 600;
	$karakter = 131;
	$posts = get_posts('numberposts='.$konu.'&offset='.$offset);

	$jsonPosts = array();
	foreach ($posts as $post) {
		$gecici = array();

		$gecici['title'] = $post->post_title;
		$gecici['ozet'] = mb_substr(wp_strip_all_tags($post->post_content, true),0,$karakter,'UTF-8');
		$gecici['link'] = get_permalink($post->ID);
		$gecici['tarih'] = $post->post_date;

		$tid = get_post_thumbnail_id($post->ID);
		if ($tid) $gecici['resim'] = wp_get_attachment_url($tid);
		else $gecici['resim'] = '';

		$jsonPosts[] = $gecici;
	}

	$content = json_encode($jsonPosts);
	$fp = fopen($cfile,'w');
	fwrite($fp,$content);
	fclose($fp);

	print($content);
}
?>
