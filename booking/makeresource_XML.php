<?PHP
			include 'dbconnect.php';
			
			$ID=getpostAJAX("ID");
			$name=getpostAJAX("name");
			$type=getpostAJAX("type");
			$company=getpostAJAX("company");
			$location=getpostAJAX("location");
			$category=getpostAJAX("category");
			$size=getpostAJAX("size");
			$cost=getpostAJAX("cost");
      
			if (empty($ID) || empty($name) || empty($type) || empty($company) || empty($location) || empty($category) || empty($size) || empty($cost)) err("Missing Form Data (ID/Name/Type/Company/Location/Category/Size/Cost");

				try{
					$querystring="INSERT INTO resource(ID,name, type,company,location,category,size,cost) values (:ID,:NAME,:TYPE,:COMPANY,:LOCATION,:CATEGORY,:SIZE,:COST);";
					$stmt = $pdo->prepare($querystring);
					$stmt->bindParam(':ID',$ID );
					$stmt->bindParam(':NAME',$name );
					$stmt->bindParam(':TYPE',$type );
					$stmt->bindParam(':COMPANY',$address );
					$stmt->bindParam(':LOCATION',$email );
					$stmt->bindParam(':CATEGORY',$category );
					$stmt->bindParam(':SIZE',$size );
					$stmt->bindParam(':COST',$cost );
					$stmt->execute();

					header ("Content-Type:text/xml; charset=utf-8");  
					echo '<created status="OK"/>';
	
			} catch (PDOException $e) {
					err("Error!: ".$e->getMessage()."<br/>");
					die();
			}

			if (!$innerresult){
				err("Insert of Resource Error");
			}else{
			}

?>