<?PHP
		include 'dbconnect.php';

		$resid=getpostAJAX("resid");	
		$name=getpostAJAX("name");
		$location=getpostAJAX("location");
		$company=getpostAJAX("company");
		$type=getpostAJAX("type");

		if(notset($type)){
				err("Missing Form Data: (type)");					
		}

		$querystring="SELECT DATE_FORMAT(date,'%Y-%m-%d %H:%i') as date,DATE_FORMAT(dateto,'%Y-%m-%d %H:%i') as dateto,resourceID,name,location,company,size,cost,category FROM resource,resourceavailability where resourceavailability.resourceID=resource.ID ";

		if(isset($resid)){
				$querystring.="and resource.ID='".$resid."'";		
		}else{
				// Search i.e. if either name location or company are set
				if(cntparam(isset($name),isset($location),isset($company),0)>1){	
						$querystring.=" and (";
						if(isset($name)){
								$querystring.="resource.name like '%".$name."%'";
								if(isset($location)) $querystring.="or resource.location like '%".$location."%'";
								if(isset($company)) $querystring.="or resource.company like '%".$company."%'";
						}else{
								if(isset($location)){
										// Location is set, perhaps also company
										$querystring.="resource.location like '%".$location."%'";						
										if(isset($company)) $querystring.="or resource.company like '%".$company."%'";
								}else{
										// Neither name or location, must be company
										$querystring.="resource.company like '%".$company."%'";						
								}
						}
						$querystring.=")";					
				}else if(isset($name)){
						$querystring.="and resource.name like '%".$name."%'";
				}else if(isset($location)){
						$querystring.="and resource.location like '%".$location."%'";
				}else if(isset($company)){
						$querystring.="and resource.company like '%".$company."%'";
				}
				if(isset($type)){
						$querystring.="and resource.type='".$type."'";
				}		
		}
				
		$querystring.=" order by resourceID,date ";
		$innerresult=mysql_query($querystring);
		if (!$innerresult) err("SQL Query Error: ".mysql_error());

		$output="<avail>\n";
		while ($innerrow = mysql_fetch_assoc($innerresult)) {

				$output.="<availability \n";
				
				$querystring="SELECT count(*) as counted FROM booking where resourceid='".$innerrow['resourceID']."' and date='".$innerrow['date']."'";
				$sinnerresult=mysql_query($querystring);
				if (!$sinnerresult) err("SQL Query Error: ".mysql_error()."Counting Error");
				while ($sinnerrow = mysql_fetch_assoc($sinnerresult)) {
						$counted=$sinnerrow['counted'];
				}
				$size=$innerrow['size']; 
				$remaining=$size-$counted;

				$output.="    resourceID='".presenthtml($innerrow['resourceID'])."'\n";
				$output.="    name='".presenthtml($innerrow['name'])."'\n";
				$output.="    location='".presenthtml($innerrow['location'])."'\n";
				$output.="    company='".presenthtml($innerrow['company'])."'\n";
				$output.="    size='".$innerrow['size']."'\n";
				$output.="    cost='".$innerrow['cost']."'\n";
				$output.="    category='".$innerrow['category']."'\n";
				$output.="    date='".$innerrow['date']."'\n";
				$output.="    dateto='".$innerrow['dateto']."'\n";
				$output.="    bookingcount='".$counted."'\n";
				$output.="    remaining='".$remaining."'\n";

				$output.=" />\n";
		}				
		$output.="</avail>\n";
		
	  header ("Content-Type:text/xml; charset=utf-8");  
		echo $output;
?>