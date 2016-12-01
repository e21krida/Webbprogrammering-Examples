<?PHP
		include 'dbconnect.php';	
    		
		$ID=getpostAJAX("ID");
		$firstname=getpostAJAX("firstname");
		$lastname=getpostAJAX("lastname");
		$address=getpostAJAX("address");
		$email=getpostAJAX("email");
		$auxdata=getpostAJAX("auxdata");

		
		if ($ID=="UNK" || $firstname=="UNK" || $lastname=="UNK" || $address=="UNK" || $email=="UNK") err("Missing Form Data: (ID/firstname/lastname/address/email)");

		try{
				$querystring="INSERT INTO customer(lastvisit,ID, firstname,lastname,address,email,auxdata) values (NOW(),:ID,:FIRSTNAME,:LASTNAME,:ADDRESS,:EMAIL,:AUXDATA);";

				$stmt = $pdo->prepare($querystring);
				$stmt->bindParam(':ID',$ID );
				$stmt->bindParam(':FIRSTNAME',$firstname );
				$stmt->bindParam(':LASTNAME',$lastname );
				$stmt->bindParam(':ADDRESS',$address );
				$stmt->bindParam(':EMAIL',$email );
				$stmt->bindParam(':AUXDATA',$auxdata );
				$stmt->execute();
				
				header ("Content-Type:text/xml; charset=utf-8");  
				echo '<created status="OK"/>';

		} catch (PDOException $e) {
				err("Error!: ".$e->getMessage()."<br/>");
				die();
		}

?>