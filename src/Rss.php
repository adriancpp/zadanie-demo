<?php

namespace AdrianWitkowskiRekrutacjaHRtec\Rss;

include_once('Tools.php');

use SimpleXMLElement;
use AdrianWitkowskiRekrutacjaHRtec\Tools\Tools as Tools;

class Rss
{
    private $filename;
    private $url;
    private $mode;
    private $rss;

    private $tools;

    private $CLI_HELP_INFO = "Wybierz jedno z poleceń:
        csv:simple
        csv:extended";

    public function toCsv()
    {
        $rss = $this->rss;
        
        if( $this->mode == 0 )
            $fp = fopen( $this->filename, "w" );
        else if( $this->mode == 1 )
        {
            $current = file_get_contents( $this->filename );

            $fp = fopen( $this->filename, "w" );
            fwrite( $fp, $current );
        }

        $cnt = count($rss->channel->item);
        for( $i=0; $i < $cnt; $i++ )
        {
            $columns = array();
            
            // pobranie danych
            $title = $rss->channel->item[$i]->title;
            $description = strip_tags( $rss->channel->item[$i]->description );
            $link = $rss->channel->item[$i]->link;
            $pubDate = $this->tools->dateV('j f Y H:m:s',strtotime( $rss->channel->item[$i]->pubDate ));
            $creator = $rss->channel->item[$i]->children('http://purl.org/dc/elements/1.1/')->creator;

            // dodanie do kolumny
            $columns[] = $title;
            $columns[] = $description;
            $columns[] = $link;
            $columns[] = $pubDate;
            $columns[] = $creator;

            fputcsv($fp, $columns);
        }

        fclose($fp);
    }

    public function init()
    {
        // przygotowanie pod kodowanie daty według PL
        date_default_timezone_set('Europe/Warsaw');
        $this->tools = new Tools();

        try
        {
            $this->rss = new SimpleXMLElement(file_get_contents($this->url));
        }
        catch( Exception $e ) 
        {
            echo "Wystąpił błąd przy pobieraniu rss: ".$e; 
        }
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
        else
        {
            if($argv[1] == "csv:simple")
                $this->mode = 0;
            else if($argv[1] == "csv:extended")
                $this->mode = 1;
        }

        if(isset($argv[2]) )
            $this->url = $argv[2];
        else
            $this->url = "https://blog.nationalgeographic.org/rss";

        if(isset($argv[3]) )
            $this->filename = $argv[3];
        else
            $this->filename = "simple_export.csv";

        return true;
    }
};