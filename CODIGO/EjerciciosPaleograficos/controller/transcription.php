<?php
include('../model/Rectangle.php');
class  Transcription{
    
    public static function getTranscription($transcriptionFile){
        $file=simplexml_load_file($transcriptionFile);
        $heigthImage = $file->facsimile->surface->graphic['height'];
        $rectangleList = Array();
        $i=0;
        foreach($file->facsimile->surface->zone as $zone){
            $width= $zone['lrx'] - $zone['ulx'];
            $heigth=$zone['lry'] - $zone['uly'];
            $line=(string)$zone['rendition'];
            $top=$zone['uly'] - $heigthImage;
            $left=$zone['ulx'];
            
            $rectangle= new Rectangle('rect'.$i,'transc',$left,$top,$width,$heigth,$line);
            $rectangleList[] = $rectangle;
            $i++;
        }
        return $rectangleList;
    }
}
?>
