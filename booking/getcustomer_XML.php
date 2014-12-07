<?PHP
			include 'dbconnect.php';
			
			$customerID=getpostAJAX("customerID");

			if(notset($customerID)){
					err("Missing Form Data: (customerID)");					
			}
			
			$querystring="SELECT * FROM customer";
			if(isset($customerID)){
					$querystring.=" WHERE ID='".$customerID."'";
			}
			
			$innerresult=mysql_query($querystring);	
			if (!$innerresult) err("Customer Query Error: ".mysql_error());

			$output="<customers>\n";
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					// At the moment do nothing!
					$output.="<customer \n";
					$output.="    id='".presenthtml($innerrow['ID'])."'\n";
					$output.="    firstname='".presenthtml($innerrow['firstname'])."'\n";
					$output.="    lastname='".presenthtml($innerrow['lastname']." ")."'\n";
				  $output.="    address='".presenthtml($innerrow['address'])."'\n";
					$output.="    lastvisit='".presenthtml($innerrow['lastvisit'])."'\n";
					$output.="    email='".presenthtml($innerrow['email'])."'\n";
					$output.=" />\n";
			}				
			$output.="</customers>";
			
			// Update first so if it crashes we have not printed the data first
			if(isset($customerID)){
					$querystring="UPDATE customer SET lastvisit=now() WHERE ID='".$customerID."'";
					$innerresult=mysql_query($querystring);
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Customer Last Visit Update Error");
			}

			header ("Content-Type:text/xml; charset=utf-8");  
			echo $output;								
?>