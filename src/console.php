<?php 

include_once('Rss.php');

$rss = new Rss();
echo $rss->checkParameters( $argc, $argv );
echo $rss->init();