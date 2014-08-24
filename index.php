<?php

require_once './lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader, array(
   // 'cache' => './tmp/cache',
));

$couch_dsn = "http://localhost:5984/";
$couch_db = "posts";

require_once "./lib/PHPonCouch/couch.php";
require_once "./lib/PHPonCouch/couchClient.php";
require_once "./lib/PHPonCouch/couchDocument.php";


$client = new couchClient($couch_dsn,$couch_db);
// GET DB INFO
try {
	$info = $client->getDatabaseInfos();
}
catch (Exception $e) {
	echo "Error:".$e->getMessage()." (errcode=".$e->getCode().")\n";
	exit(1);
}
// GET POST INFO
try {
	$docs = $client->getAllDocs();
}
catch (Exception $e) {
	if ( $e->code() == 404 ) {
		echo "Document not found\n";
	} else {
		echo "Error: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
	}
	exit(1);
}



$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path == '/home') {
  $template = $twig->loadTemplate('default.phtml');
  $ids    = array();

  $nudocs = array();
  $nucats = array();

  foreach($docs->rows as $doc){
      $ids[] = $doc->id;
      $cats[] = $doc->value;
  }
  
  // now use another loop and getDoc(<id>) to retrieve content for each post
  foreach($ids as $id){
      $nudoc = $client->getDoc($id);
      $nudocs[] = $nudoc;
  }
  // get all categories only in this loop
   foreach($nudocs as $cat){
      $nucat = $cat->post_cat;
      //$nucats[] = $nucat;
      if(!in_array($nucat, $nucats)) $nucats[] = $nucat; 
  }
  $params = array(
      "all_posts" => $nudocs,
      "most_recent" => $nudocs[0],
      "all_cats" => $nucats
  );
  
 $template->display($params);
 //echo "<pre>";
 //print_r($params);
}

if ($path == '/home/single-post') {
    if(isset($_POST["single-post"]) && is_string($_POST["single-post"]) && strpos($_POST["single-post"],'_') !== false) {
        $single_post = $client->getDoc($_POST["single-post"]);
        print(json_encode($single_post));
    }
    else {
        print "Sorry, no post found";
    }

}

if ($path == '/preview-post') {

    // post content (with HTML presumably can be previewed at this URI) - create a base template using PureCSS
}

if ($path == '/test') {
  $template = $twig->loadTemplate('home.phtml');
 
  //echo "<pre>";
    echo "<pre>";
    $nudocs = array();
    $nucats = array();
  
    
  foreach($docs->rows as $doc){
      $ids[] = $doc->id;
      //$cats[] = $doc->value;
  }
  
  // now use another loop and getDoc(<id>) to retrieve content for each post
  foreach($ids as $id){
      $nudoc = $client->getDoc($id);
      $nudocs[] = $nudoc;
  }
  
  foreach($nudocs as $cat){
      $nucat = $cat->post_cat;
      //$nucats[] = $nucat;
      if(!in_array($nucat, $nucats)) $nucats[] = $nucat;
  }
    print_r($docs);
    //$params = array(
    // 'title' => $doc->post_title,
    // 'content' => $doc->post_content,
    // 'author' => $doc->post_author   
    //);
//$template->display($params);
}