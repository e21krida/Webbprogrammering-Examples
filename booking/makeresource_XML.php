<?PHP
			header ("Content-Type:text/xml; charset=utf-8");  

			include 'dbconnect.php';
			
			$ID=getpostAJAX("ID");
			$name=getpostAJAX("name");
			$type=getpostAJAX("type");
			$company=getpostAJAX("company");
			$location=getpostAJAX("location");
			$category=getpostAJAX("category");
			$size=getpostAJAX("size");
			$cost=getpostAJAX("cost");
      
			if (empty($ID) || empty($name) || empty($type) || empty($company) || empty($location) || empty($category) || empty($size) || empty($cost)) err("Missing Form Data");

			$querystring="INSERT INTO resource(ID,name, type,company,location,category,size,cost) values ('".$ID."','".$name."','".$type."','".$company."','".$location."','".$category."','".$size."','".$cost."');";
			$innerresult=mysql_query($querystring);								

			if (!$innerresult){
				err("Insert of Resource Error");
			}else{
					echo '<created status="OK"/>';
			}

?>