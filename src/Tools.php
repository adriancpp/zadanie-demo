<?php

namespace AdrianWitkowskiRekrutacjaHRtec\Tools;

class Tools
{
    public function dateV($format,$timestamp=null){
        $to_convert = array(
            'l'=>array('dat'=>'N','str'=>array('Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota','Niedziela')),
            'F'=>array('dat'=>'n','str'=>array('styczeń','luty','marzec','kwiecień','maj','czerwiec','lipiec','sierpień','wrzesień','październik','listopad','grudzień')),
            'f'=>array('dat'=>'n','str'=>array('stycznia','lutego','marca','kwietnia','maja','czerwca','lipca','sierpnia','września','października','listopada','grudnia'))
        );
        if ($pieces = preg_split('#[:/.\-, ]#', $format)){	
            if ($timestamp === null) { $timestamp = time(); }
            foreach ($pieces as $datepart){
                if (array_key_exists($datepart,$to_convert)){
                    $replace[] = $to_convert[$datepart]['str'][(date($to_convert[$datepart]['dat'],$timestamp)-1)];
                }else{
                    $replace[] = date($datepart,$timestamp);
                }
            }
            $result = strtr($format,array_combine($pieces,$replace));
            return $result;
        }
    }
};