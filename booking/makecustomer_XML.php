<?PHP
			include 'dbconnect.php';

			$name=getpostAJAX("name");
			$ID=getpostAJAX("ID");
			$firstname=getpostAJAX("firstname");
			$lastname=getpostAJAX("lastname");
			$address=getpostAJAX("address");
			$email=getpostAJAX("email");

			if (notset($ID) || notset($firstname) || notset($lastname) || notset($address) || notset($email)) err("Missing Form Data: (ID/firstname/lastname/address/email)");

			$querystring="INSERT INTO customer(lastvisit,ID, firstname,lastname,address,email) values (NOW(),'".$ID."','".$firstname."','".$lastname."','".$address."','".$email."');";
			$innerresult=mysql_query($querystring);								

			if (!$innerresult){
				err("Insert of Customer Error: ".mysql_error());
			}else{
					header ("Content-Type:text/xml; charset=utf-8");  
					echo '<created status="OK"/>';
			}

?>