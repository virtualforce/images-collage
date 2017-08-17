<?php

define('X',18);
define('Y',18);

function getRandomEmptyPosition()
{
    global $conn;
    $result_set = $conn->query("SELECT * FROM collage_images_positons where is_occupied = 0 ORDER BY RAND() limit 1");
    $results    = $result_set->fetch_assoc();
    return $results;
}

function getOccupiedPositions()
{
    global $conn;
		$images = array();
    $result = $conn->query("SELECT id as image_number FROM collage_images_positons where is_occupied = 1");

     while($row = $result->fetch_assoc()) {
        $images[] = $row['image_number'];
    }
    return $images;
}

function getPastedImages()
{
    global $conn;
    $result = $conn->query("SELECT id as image_number,image FROM collage_images_positons where is_occupied = 1");

    $images = array();

    while($row = $result->fetch_assoc()) {
        $images[$row['image_number']] = $row['image'];
    }

    return $images;
}

function insertPosition($params)
{
    global $conn;
    $conn->query("INSERT INTO collage_images_positons (x1, y1, x2,y2,image_number) VALUES (".$params['x1'].",".$params['y1'].",".$params['x2'].",".$params['y2'].",".$params['image_number'].")");
}

function saveNewImage($params)
{
    global $conn;
    $conn->query("update collage_images_positons set image = '".$params['image_name']."',is_occupied = 1,update_at = '".date('Y-m-d H:i:s')."' where id = '".$params['id']."'");
}

function lastedImages()
{
    global $conn;
		$images = array();
    $result = $conn->query("SELECT image FROM collage_images_positons ORDER BY update_at DESC");
		$counter = 1;
     while($row = $result->fetch_assoc()){
			 if($counter<8){
				  if(!in_array($row['image'],$images)){
        		$images[] = $row['image'];
						$counter++;
					}
			 }else{
				 break;
			 }

    }
    return $images;
}

function readMasterImageAndSetPositionsInDb(){
    $imgPath = 'master.jpg';
    $img     = imagecreatefromjpeg($imgPath);

    list($width,$height) = getimagesize($imgPath);

    $x1 = 0;
    $y1 = 0;

    $x2 = X;
    $y2 = Y;

    $new_width  = round($width/$x2);
    $new_height = round($height/$y2);

    $skipimags_x = 1;

    $occupied_positions = array();

    $pasted_images  = getPastedImages();

    for($y=1;$y<=$new_height;$y++){
        $x1 = 0;
        $x2 = X;

        for($x=1;$x<=$new_width;$x++){

            insertPosition(['x1'=>$x1,'y1'=>$y1,'x2'=>$x2,'y2'=>$y2,'image_number'=> $skipimags_x]);

            $x1 = $x1+X;
            $x2 = $x1+X;

            $skipimags_x++;
        }

        $y1 = $y1+Y;
        $y2 = $y2+Y;

        //$skipimags_x++;
    }
}

function makeCollage(){
    $imgPath = 'master.jpg';
    $img     = imagecreatefromjpeg($imgPath);

    list($width,$height) = getimagesize($imgPath);

    //$color = imagecolortransparent($img,imagecolorallocate($img,255,255,255));

    $x1 = 0;
    $y1 = 0;

    $x2 = X;
    $y2 = Y;

    $new_width  = round($width/$x2);
    $new_height = round($height/$y2);

    $skipimags_x = 1;

    $occupied_positions = getOccupiedPositions();
		if(empty($occupied_positions)){
			$occupied_positions = array();
		}
    $pasted_images      = getPastedImages();

    $not_done = 1;
    for($y=1;$y<=$new_height;$y++){
        $x1 = 0;
        $x2 = X;

        for($x=1;$x<=$new_width;$x++){

            if(!in_array($skipimags_x,$occupied_positions)){
								$transparent = imagecolorallocatealpha($img, 255, 255, 255, 30);
								imagefilledrectangle($img, $x1,$y1, $x2,$y2, $transparent);
            }else{
                $extension = getFileExtension('pics/'.$pasted_images[$skipimags_x]);
                if(strtolower($extension)==='jpg'){
                    $src = imagecreatefromjpeg('pics/'.$pasted_images[$skipimags_x]);
                }else{
                    $src = imagecreatefrompng('pics/'.$pasted_images[$skipimags_x]);
                }

                imagecopymerge($img, $src, $x1, $y1, 0, 0, 18, 18, 17);
            }

            $x1 = $x1+X;
            $x2 = $x1+X;

            $skipimags_x++;
        }

        $y1 = $y1+Y;
        $y2 = $y2+Y;

        //$skipimags_x++;
    }

    return $img;
}

function getFileExtension($path){
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return $ext;
}

function scanFolderAndAddTheImagesInDb(){
    $log_directory = 'pics';
    $results_array = array();
    if (is_dir($log_directory))
    {
        if ($handle = opendir($log_directory))
        {

            while(($file = readdir($handle)) !== FALSE)
            {
                if($file!='.' && $file!='..'){
									for($i=1;$i<=4;$i++){
										$new_postions = getRandomEmptyPosition();
										saveNewImage(['image_name'=>$file,'id'=>$new_postions['id']]);
									}
                }

            }
            closedir($handle);
        }
    }
}

function resizeImage($file,$dest,$w, $h, $crop=FALSE){
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
       /* if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }*/

				$newheight = $h;
        $newwidth  = $w;
    }
    $extension = getFileExtension($file);
		if(strtolower($extension)==='jpg'){
				$src = imagecreatefromjpeg($file);
		}else{
				$src = imagecreatefrompng($file);
		}

    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		if(strtolower($extension)==='jpg'){
				imagejpeg($dst,$dest);
		}else{
				imagepng($dst,$dest);
		}

    return $dst;
}


//scanFolderAndAddTheImagesInDb();
//readMasterImageAndSetPositionsInDb();

// Call the function that makes the collage
$img = makeCollage();
imagepng($img,'collage.png');
imagedestroy($img);
