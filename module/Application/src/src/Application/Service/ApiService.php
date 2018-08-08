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

	public function reduceImageSize($filename, $destPath, $destSize)
    {
        $img = $filename;
        if(!filesize($img) < 300000){
                    // get image size of img
            $x = @getimagesize($img);
    
            // image dimensions
            $sw = $x[0];
            $sh = $x[1];
    
            //dest size
            $dSize = $destSize;
    
            //find smallerst part and get needed scale and offset
            $yOff = 0;
            $xOff = 0;
            if($sw < $sh) {
                /*$scale = $dSize / $sw;
                $yOff = $sh/2 - $dSize/$scale/2;*/
    			$dhSize = $destSize;
    			$dwSize = $sw * $destSize / $sh;
            } else {
                /*$scale = $dSize / $sh;
                $xOff = $sw/2 - $dSize/$scale/2;*/
    			$dwSize = $destSize;
    			$dhSize = $sh * $destSize / $sw;
            }
            $im = @ImageCreateFromJPEG ($img) or // Read JPEG Image
                $im = @ImageCreateFromPNG ($img) or // or PNG Image
                    $im = @ImageCreateFromGIF ($img) or // or GIF Image
                        $im = false; // If image is not JPEG, PNG, or GIF
    
            if (!$im) {
                return false;
            } else {
    			
                // Create the resized image destination
                $imgNew = @ImageCreateTrueColor ($dwSize,$dhSize);
                // Copy from image source, resize it, and paste to image destination
                imagecopyresampled(
    				$imgNew, $im,
                    0, 0,
                    $xOff, $yOff,
                    $dwSize, $dhSize,
                    $sw, $sh);
            }
    		
    		unlink($filename);
    		imagejpeg($imgNew, $destPath, 100);
        }
            return $destPath;//imagejpeg($imgNew, $destPath, 100);
    }
	
	public function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true) 
	{
	  if ($zip = zip_open($src_file)) 
	  {
		if ($zip) 
		{
		  $splitter = ($create_zip_name_dir === true) ? "." : "/";
		  if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
		  
		  // Create the directories to the destination dir if they don't already exist
		  $this->create_dirs($dest_dir);

		  // For every file in the zip-packet
		  while ($zip_entry = zip_read($zip)) 
		  {
			// Now we're going to create the directories in the destination directories
			
			// If the file is not in the root dir
			$pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
			if ($pos_last_slash !== false)
			{
			  // Create the directory where the zip-entry should be saved (with a "/" at the end)
			  $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
			}

			// Open the entry
			if (zip_entry_open($zip,$zip_entry,"r")) 
			{
			  
			  // The name of the file to save on the disk
			  $file_name = $dest_dir.zip_entry_name($zip_entry);
			  
			  // Check if the files should be overwritten or not
			  if ($overwrite === true || $overwrite === false && !is_file($file_name))
			  {
				// Get the content of the zip entry
				$fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

				$zipSize = file_put_contents($file_name, $fstream );

				// Set the rights
				chmod($file_name, 0777);
				echo "save: ".$file_name."<br />";
			  }
			  
			  // Close the entry
			  zip_entry_close($zip_entry);
			}       
		  }
		  // Close the zip-file
		  zip_close($zip);
		}
	  } 
	  else
	  {
		return false;
	  }
	  
	  return true;
	}
	
	public function create_dirs($path){
		if (!is_dir($path)){
			$directory_path = "";
			$directories = explode("/",$path);
			array_pop($directories);

			foreach($directories as $directory){
				$directory_path .= $directory."/";
				if (!is_dir($directory_path))
				{
					mkdir($directory_path);
					chmod($directory_path, 0777);
				}
			}
		}
	}
	
    public function setEntityManager($em){
        $this->entityManager = $em;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
	
	public function remove($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
	
	public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
	
} 