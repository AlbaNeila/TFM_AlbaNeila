<?php
include('../model/Rectangle.php');
/**
* Transcription is a class to access to the transcription data of the xml files
*
* @package  controller
* @author   Alba Neila Neila <ann0005@alu.ubu.es>
* @version  1.0
* @access   public
*/
class  Transcription{
    /**
     * Function to get the transcription of a paleography document.
     *
     * Parse the xml file and get all the transcription information.
     * 
     *  @author Alba Neila Neila <ann0005@alu.ubu.es>
     *  @package controller
     *  @version  1.0
     *  @access   public
     *  @param $transcriptionFile the url of the transcription file
     *  @return $rectangleList list of Rectangles to create the transcription exercise
     */
    public static function getTranscription($transcriptionFile){
        $file=@simplexml_load_file($transcriptionFile);
        if(!$file){
            return false;
        }
        $heigthImage = $file->facsimile->surface->graphic['height'];
        $rectangleList = Array();
        $i=0;
        $j=0;
        foreach($file->facsimile->surface->zone as $zone){
            $width= $zone['lrx'] - $zone['ulx'];
            $heigth=$zone['lry'] - $zone['uly'];
            $line=(string)$zone['rendition'];
            $top=$zone['uly'];
            $left=$zone['ulx'];
            
            $rectangle= new Rectangle('rect'.$i,'transc',$left,$top,$width,$heigth,$line);
            $rectangleList[] = $rectangle;
            $i++;
        }
        
        foreach($file->text->body->div->div as $div){
            $transc=(string)$div->head;

            $rectangleList[$j]->setTranscriptionRectangle($transc);
            $j++;
        }      
        return $rectangleList;
    }
}
?>
