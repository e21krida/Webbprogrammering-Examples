<?PHP
			include 'dbconnect.php';
			
			$resourceID=getpostAJAX("resourceID");
			$date=getpostAJAX("date");
			$type=getpostAJAX("type");

			if(notset($type)){
					err("Missing Form Data: (type)");					
			}

			$querystring="SELECT resource.size,resource.type,booking.customerID,booking.resourceID,resource.name,resource.company,resource.location,DATE_FORMAT(booking.date,'%Y-%m-%d %H:%i') as date,DATE_FORMAT(booking.dateto,'%Y-%m-%d %H:%i') as dateto,booking.cost,booking.rebate,booking.position,booking.status,auxdata FROM booking,resource where resource.ID=booking.resourceID ";

			if(isset($date)) $querystring.=" and date='".$date."'";
			if(isset($resourceID)) $querystring.=" and resourceID='".$resourceID."'";
			if(isset($type)) $querystring.=" and resource.type='".$type."'";

			$querystring.=" order by resourceid,position";
			$innerresult=mysql_query($querystring);
			
			if (!$innerresult) err("Select Bookings Error: ".mysql_error());
			
			$output="<bookings>\n";
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
				  $output.="<booking \n";
					$output.="    application='".presenthtml($innerrow['type'])."'\n";
					$output.="    customerID='".presenthtml($innerrow['customerID'])."'\n";
					$output.="    resourceID='".presenthtml($innerrow['resourceID'])."'\n";
					$output.="    name='".presenthtml($innerrow['name'])."'\n";
					$output.="    company='".presenthtml($innerrow['company'])."'\n";
					$output.="    location='".presenthtml($innerrow['location'])."'\n";
					$output.="    date='".$innerrow['date']."'\n";
					$output.="    dateto='".$innerrow['dateto']."'\n";
					$output.="    position='".$innerrow['position']."'\n";
					$output.="    status='".$innerrow['status']."'\n";
					$output.="    cost='".$innerrow['cost']."'\n";				
					$output.="    size='".$innerrow['size']."'\n";				
					$output.="    auxdata='".presenthtml($innerrow['auxdata'])."'\n";				
					$output.=" />\n";
			}				
			$output.="</bookings>\n";

			header ("Content-Type:text/xml; charset=utf-8");  
			echo $output;						
?>
