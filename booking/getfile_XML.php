<?PHP
		include 'dbconnect.php';
		
		$filename=getpostAJAX("filename");
		
		if($filename=="UNK"){
				err("Must give a file name!");					
		}
		if(!endsWith($filename,"html")){
				err("File: ".$filename." must be of type html.");					
		}

		if(file_exists($filename) && is_readable ($filename)){
				try {
						$filecont = file_get_contents($filename);
				}catch (ErrorException $e) {
						err("File read error for ".$filename." : ".$e->getMessage());									
				}
		
				if(!$filecont){
						err("File read error or empty file: ".$filename);									
				}	
		
				header ("Content-Type:text/xml; charset=utf-8");  
				echo $filecont;						
		}else{
				err("Local fie ".$filename." does not exist or is not readable.");											
		}

?>
