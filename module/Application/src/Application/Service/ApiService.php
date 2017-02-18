<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 06.03.2016
 * Time: 13:44
 */

namespace Application\Service;


class ApiService {

    protected $entityManager = null;
    const FOLDER_THUMBNAIL = "thumbnail";

    public function createThumbnail($filename, $folder)
    {
        if(!is_dir($folder . DIRECTORY_SEPARATOR . self::FOLDER_THUMBNAIL))
        {
            mkdir($folder . DIRECTORY_SEPARATOR . self::FOLDER_THUMBNAIL);
        }

        $img = $folder . DIRECTORY_SEPARATOR . $filename;
        // get image size of img
        $x = @getimagesize($img);

        // image dimensions
        $sw = $x[0];
        $sh = $x[1];

        //dest size
        $dSize = 140;

        //find smallerst part and get needed scale and offset
        $yOff = 0;
        $xOff = 0;
        if($sw < $sh) {
            $scale = $dSize / $sw;
            $yOff = $sh/2 - $dSize/$scale/2;
        } else {
            $scale = $dSize / $sh;
            $xOff = $sw/2 - $dSize/$scale/2;
        }

        $im = @ImageCreateFromJPEG ($img) or // Read JPEG Image
            $im = @ImageCreateFromPNG ($img) or // or PNG Image
                $im = @ImageCreateFromGIF ($img) or // or GIF Image
                    $im = false; // If image is not JPEG, PNG, or GIF

        if (!$im) {
            return false;
        } else {
            // Create the resized image destination
            $thumb = @ImageCreateTrueColor ($dSize,$dSize);
            // Copy from image source, resize it, and paste to image destination
            imagecopyresampled($thumb, $im,
                0, 0,
                $xOff,$yOff,
                $dSize, $dSize,
                $dSize / $scale ,$dSize / $scale);
        }

        return imagejpeg($thumb, $folder . DIRECTORY_SEPARATOR . self::FOLDER_THUMBNAIL . DIRECTORY_SEPARATOR . $filename, 100);
    }

    public function setEntityManager($em){
        $this->entityManager = $em;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
	
	public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
	
} 