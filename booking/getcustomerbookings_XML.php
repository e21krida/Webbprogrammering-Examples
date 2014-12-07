<?PHP
			include 'dbconnect.php';
			
			$customerID=getpostAJAX("customerID");
			$type=getpostAJAX("type");

			if(notset($customerID)){
					err("Missing Form Data: (customerID)");					
			}

			$querystring="SELECT resource.type,booking.customerID,booking.resourceID,resource.name,resource.company,resource.location,DATE_FORMAT(booking.date,'%Y-%m-%d %H:%i') as date,DATE_FORMAT(booking.dateto,'%Y-%m-%d %H:%i') as dateto,booking.cost,booking.rebate,booking.position,booking.status,resource.size,booking.auxdata FROM customer,booking,resource where resource.ID=booking.resourceID and booking.customerID=customer.ID ";

			if(isset($_POST['customerID'])) $querystring.=" and customer.ID='".$customerID."'";
			if(isset($_POST['type'])) $querystring.=" and resource.type='".$type."'";

			$querystring.=" order by booking.date";
			$innerresult=mysql_query($querystring);
			
			if (!$innerresult) err("SQL Query Error: ".mysql_error());

			header ("Content-Type:text/xml; charset=utf-8");  
			
			echo "<bookings>\n";
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					// At the moment do nothing!
					echo "<booking \n";
					echo "    application='".presenthtml($innerrow['type'])."'\n";
					echo "    customerID='".presenthtml($innerrow['customerID'])."'\n";
					echo "    resourceID='".presenthtml($innerrow['resourceID'])."'\n";
					echo "    name='".presenthtml($innerrow['name'])."'\n";
					echo "    company='".presenthtml($innerrow['company'])."'\n";
					echo "    location='".presenthtml($innerrow['location'])."'\n";
					echo "    date='".$innerrow['date']."'\n";
					echo "    dateto='".$innerrow['dateto']."'\n";					
					echo "    position='".$innerrow['position']."'\n";
					echo "    cost='".$innerrow['cost']."'\n";				
					echo "    size='".$innerrow['size']."'\n";				
					echo "    auxdata='".$innerrow['auxdata']."'\n";				
					echo " />\n";
					echo "\n";
			}				
			echo "</bookings>\n";
						
?>