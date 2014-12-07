<?PHP
			//---------------------------------------------------------------------------------------------------------------
			// Build Search Query!
			//---------------------------------------------------------------------------------------------------------------
			
			include 'dbconnect.php';
			$querystring="SELECT * FROM resource";

			$company=getpostAJAX("company");
			$type=getpostAJAX("type");
			$location=getpostAJAX("location");
			$name=getpostAJAX("name");
			$fulltext=getpostAJAX("fulltext");

			if(notset($type)){
					err("Missing Form Data: (type)");					
			}
			
			if(isset($type)||isset($name)||isset($company)||isset($location)||isset($fulltext)) $querystring.=" WHERE";
			if(isset($type)) $querystring.=" type='".$type."'";

			if(isset($name)||isset($company)||isset($location)){
					if(isset($type)){
							$querystring.=" and (";
					}else{
							$querystring.="(";
					}

					// Handle all the cases with 1/2/3 Parameters
					if(isset($name)){
							$querystring.="name like '%".$name."%'";
							if(isset($company)) $querystring.=" and company like '%".$company."%'";
							if(isset($location))$querystring.=" and location like '%".$location."%'";
					}else{
							if(isset($company)){
									$querystring.="company like '%".$company."%'";
									if(isset($location))$querystring.=" and location like '%".$location."%'";
							}else{
									if(isset($location)){
												$querystring.="location like '%".$location."%'";							
									}
							} 
					
					}	
					$querystring.=")";

			}else if(isset($fulltext)){
					if(isset($type)){
							$querystring.=" and (name like '%".$fulltext."%' or company like '%".$fulltext."%' or location like '%".$fulltext."%')";
					}else {
							$querystring.="(name like '%".$fulltext."%' or company like '%".$fulltext."%' or location like '%".$fulltext."%')";
					}
			}

			$innerresult=mysql_query($querystring);
			if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Resource Search Error (".$querystring.")");

			//---------------------------------------------------------------------------------------------------------------
			// Make Result!
			//---------------------------------------------------------------------------------------------------------------

			header ("Content-Type:text/xml; charset=utf-8");  
			echo "<resources>\n";
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					echo "<resource \n";
					echo "    id='".presenthtml($innerrow['ID'])."'\n";
					echo "    name='".presenthtml($innerrow['name'])."'\n";
					echo "    company='".presenthtml($innerrow['company'])."'\n";
					echo "    location='".presenthtml($innerrow['location'])."'\n";
					echo "    size='".$innerrow['size']."'\n";
					echo "    cost='".$innerrow['cost']."'\n";
					echo " />\n";
					echo "\n";
			}				
			echo "</resources>\n";						
			
?>