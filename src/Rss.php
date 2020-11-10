<?php

include_once('.\vendor\dg\rss-php\src\Feed.php');

class Rss
{
    private $filename;
    private $url;

    private $CLI_HELP_INFO = "Wybierz jedno z poleceń:
        csv:simple
        csv:extended";

    private function parseRSS($xml)
    {
        $myfile = fopen($this->filename, "w");

        $txt = "<strong>".$xml->channel->title."</strong>";
        fwrite($myfile, $txt);
        $cnt = count($xml->channel->item);
        for($i=0; $i<$cnt; $i++)
        {
        $url 	= $xml->channel->item[$i]->link;
        $title 	= $xml->channel->item[$i]->title;
        $desc = $xml->channel->item[$i]->description;

        $txt = '<a href="'.$url.'">'.$title.'</a>'.$desc.'';
        fwrite($myfile, $txt);
        }

        fclose($myfile);
    }

    public function init()
    {
        $this->filename = "test.xml";
        $this->url = "https://blog.nationalgeographic.org/rss";

        $doc = new SimpleXMLElement(file_get_contents($this->url));

        $this->parseRSS($doc);

    }

    public function checkParameters( $argc, $argv ): bool
    {
        if( $argc < 2 )
        {
            echo "Nie podano parametru!\n";
            echo $this->CLI_HELP_INFO;
            exit;
        }

        if( $argc > 4 )
        {
            echo "Zbyt wiele paramterów!\n";
            echo $this->CLI_HELP_INFO;
            exit;
        }

        if( !($argv[1] == "csv:simple" || $argv[1] == "csv:extended") )
        {
            echo "Nieprawidłowy parametr!\n";
            echo $this->CLI_HELP_INFO;
            exit;
        }

        return true;
    }
};