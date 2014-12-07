<html>
<style>
		td.result{border-bottom: 1px solid green;}
		td.head{background-color:#ffeedd;border-top: 1px solid green;border-right: 1px solid green;border-bottom: 2px solid black;}
		table {border: 1px solid red;}
</style>
<body>
<?PHP

			include '../booking/dbconnect.php';
															
			dbConnect();			
			
			if(isset($_POST['Button'])){
					$button=$_POST['Button'];
			}else{
					$button="None!";			
			}

			if(isset($_POST['filter'])){
					$filter=$_POST['filter'];
			}else{
					$filter="None!";			
			}

			if($button=='Save'){
					echo "<form action='editavailability.php' method='POST' >";
					echo "<table>";

					$querystring="SELECT DISTINCT type FROM resource order by type;";
					$innerresult=mysql_query($querystring);
							
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Filter database querying error");
					echo "<tr><td>Application:&nbsp;&nbsp;</td><td><SELECT NAME='filter'>";
					while ($innerrow = mysql_fetch_assoc($innerresult)) {
							if($filter==$innerrow['type']){
									echo "<OPTION selected='selected'>".$innerrow['type'];
							}else{
									echo "<OPTION>".$innerrow['type'];
							}
					}
					echo "</SELECT><input name='Button' type='submit' value='Filter'></td></tr>";					
				
					$querystring="SELECT ID,name,company,location FROM resource";
					if($filter!="None!") $querystring.=" where type='".$filter."'";
					$querystring.=" order by name,company,location";
					$sinnerresult=mysql_query($querystring);
					if (!$sinnerresult) err("SQL Query Error: ".mysql_error(),"Filter database querying error");
					echo "<tr><td>ResourceID</td><td><SELECT NAME='resourceID'>";
					while ($sinnerrow = mysql_fetch_assoc($sinnerresult)) {
							if($_POST['resourceID']==$sinnerrow['ID']) echo "<OPTION selected='selected' value='".$sinnerrow['ID']."'>&lt;".$sinnerrow['name']."&gt; ".$sinnerrow['company']." - ".$sinnerrow['location'];
							else echo "<OPTION value='".$sinnerrow['ID']."'>&lt;".$sinnerrow['name']."&gt; ".$sinnerrow['company']." - ".$sinnerrow['location'];
					}
					echo "</SELECT></td></tr>";

					echo "<td>Date:</td><td><input type='text' value='".$_POST['date']."' name='date'></td></tr>";

					echo "<td>Date to:</td><td><input type='text' value='".$_POST['dateto']."' name='dateto'></td></tr>";
					echo "<tr><td><input name='Button' type='submit' value='Save'></td></tr>";
					echo "</table>";
					echo "</form>";									
			}else{

					echo "<form action='editavailability.php' method='POST' >";
					echo "<table>";

					$querystring="SELECT DISTINCT type FROM resource order by type";
					$innerresult=mysql_query($querystring);

					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Filter database querying error");
					echo "<tr><td>Application:&nbsp;&nbsp;</td><td><SELECT NAME='filter'>";
					while ($innerrow = mysql_fetch_assoc($innerresult)) {
							if($filter==$innerrow['type']){
									echo "<OPTION selected='selected'>".$innerrow['type'];
							}else{
									echo "<OPTION>".$innerrow['type'];
							}
					}
					echo "</SELECT><input name='Button' type='submit' value='Filter'></td></tr>";

					$querystring="SELECT ID,name,company,location FROM resource";
					if($filter!="None!") $querystring.=" where type='".$filter."'";
					$querystring.=" order by name,company,location";
					
					$sinnerresult=mysql_query($querystring);
					if (!$sinnerresult) err("SQL Query Error: ".mysql_error(),"Filter database querying error");
					echo "<tr><td>ResourceID</td><td><SELECT NAME='resourceID'>";
					while ($sinnerrow = mysql_fetch_assoc($sinnerresult)) {
							echo "<OPTION value='".$sinnerrow['ID']."'>&lt;".$sinnerrow['name']."&gt; ".$sinnerrow['company']." - ".$sinnerrow['location'];
					}
					echo "</SELECT></td></tr>";

					echo "<tr><td>Date:</td><td><input type='text' value='' name='date'></td></tr>";
					echo "<tr><td>Date to:</td><td><input type='text' value='' name='dateto'></td></tr>";

					echo "<tr><td><input name='Button' type='submit' value='Save'></td></tr>";							
					echo "</table>";
					echo "</form>";								
			}
				
			if($button=='Save'){
					$querystring="INSERT INTO resourceavailability(resourceID,date,dateto) values ('".$_POST['resourceID']."',DATE_FORMAT('".$_POST['date']."','%Y-%m-%d %H:%i'),DATE_FORMAT('".$_POST['dateto']."','%Y-%m-%d %H:%i'));";
					$innerresult=mysql_query($querystring);								
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Insert Error!");
			}
			if($button=='Del'){			
					$querystring="DELETE FROM resourceavailability WHERE resourceID='".$_POST['resourceID']."' and date='".$_POST['date']."'";
					$innerresult=mysql_query($querystring);													
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Delete Error!");
			}
			
			//---------------------------------------------------------------------------------------------------------------
			// Search Query!
			//---------------------------------------------------------------------------------------------------------------

			$querystring="SELECT resourceID,DATE_FORMAT(date,'%Y-%m-%d %H:%i') as date,DATE_FORMAT(dateto,'%Y-%m-%d %H:%i') as dateto,name,company,location FROM resourceavailability,resource where resource.ID=resourceavailability.resourceID";
			if(isset($_POST['filter'])) $querystring.=" and type='".$_POST['filter']."'";
			$querystring.=" order by name,company,location,date";
			$innerresult=mysql_query($querystring);
			
			if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Query Error!");

			echo "<p>Note: Date and to-Detes are given as yyyy mm dd hh:mm</p><p>Press filter button with your application seleted to reduce resource list size.</p><br/><table cellspacing=0>\n";
			echo "<tr><td style='border-left: 1px solid green;' class='head'>Resource</td><td class='head'>Name</td><td class='head'>Company</td><td class='head'>Location</td><td class='head'>Date</td><td class='head'>To Date</td></tr>";
			$i=0;
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					// At the moment do nothing!
					if($i%2==0){
							echo "<tr style='background-color:#fff8f8;'>\n";
					}else{
							echo "<tr style='background-color:#ffffff;'>\n";					
					}
					echo "    <td class='result'>".$innerrow['resourceID']."</td>\n";
					echo "    <td class='result'>".$innerrow['name']."</td>\n";
					echo "    <td class='result'>".$innerrow['company']."</td>\n";
					echo "    <td class='result'>".$innerrow['location']."</td>\n";
					echo "    <td class='result'>".$innerrow['date']."</td>\n";
					echo "    <td class='result'>".$innerrow['dateto']."</td>\n";
					echo "<td class='result'>";
					echo "<form action='editavailability.php' method='POST'>";
					echo "<input type='hidden' name='resourceID' value='".$innerrow['resourceID']."'>";
					echo "<input type='hidden' name='date' value='".$innerrow['date']."'>";
					if (isset($_POST['filter']))echo "<input type='hidden' name='filter' value='".$_POST['filter']."'>";
					echo "<input name='Button' type='submit' value='Del'>";
					echo "</form>";
					echo "</td>";
					echo "</tr>\n";
					$i++;
			}				
			echo "</table>\n";
			
?>
</body>
</html>
