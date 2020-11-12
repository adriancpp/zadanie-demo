<?php 

include_once('Rss.php');

use AdrianWitkowskiRekrutacjaHRtec\Rss\Rss as Rss;

$rss = new Rss();
$rss->checkParameters( $argc, $argv );
$rss->init();
$rss->toCsv();

// php src/console.php csv:simple https://blog.nationalgeographic.org/rss simple_export.csv