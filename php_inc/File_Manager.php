<?php
	include_once 'core.inc.php';
	
	class File_Manager{
		/*
			$dir is the folder directory for the user's media
		*/
		public function upload_File_To_Dir($file){
			include_once 'ak_img_lib.php';
			$fulldir = UPLOAD_MEDIA_DIR;
			$result = "";
			if(!file_exists($fulldir)){
				//create media folder for the user if it hasn't existed yet
				mkdir($fulldir);	
			}
			//each files is wrapped in a random folder
			do{
				$result = getRandomString(); //random wrapper folder for the media file
				$randomFolderDir = $fulldir.$result;
			}while(file_exists($randomFolderDir));
			if(mkdir($randomFolderDir)){
				//upload file here 
				$extension = getMediaFileExtension($file);
				$filename = getRandomString().'.'.$extension; //rename the file
				$large_destination_path = $randomFolderDir.'/'.$filename;
					$target_file = $file["tmp_name"];
					$resized_file = $large_destination_path;
					$wmax = 640;
					$hmax = 640;
					ak_img_resize($target_file, $resized_file, $wmax, $hmax, $extension);
					return $result.'/'.$filename;
			}
			return false;
		}
		
		
		
		
		
		
		
		
		
		//$dir is where the media file located
		
		
		public static function rrmdir($dir){
			if (is_dir($dir)) { 
				 $objects = scandir($dir); 
				 foreach ($objects as $object) { 
				   if ($object != "." && $object != "..") { 
					 if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
				   } 
				 } 
				 reset($objects); 
				 rmdir($dir); 
			}
		}
		
		
	
		
		public function getNewRandomNonRepeatedFolderNameInDir($dir){
			do{
				$random_name = getRandomString();
				$folder_path = $dir.'/'.$random_name;
			}while(file_exists($folder_path));
			return $random_name;
		}
	}




?>