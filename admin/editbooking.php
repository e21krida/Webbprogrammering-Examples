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

			$button=getpost("Button");
			$filter=getpost("filter");

			$position=getpost("position");
			$cost=getpost("cost");
			$rebate=getpost("rebate");
			$auxdata=getpost("auxdata");
			$resourceid=getpost("resourceID");

			echo "<form action='editbooking.php' method='POST' >";
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

			$querystring="SELECT ID,name,company,location FROM resource WHERE type='".$filter."' ORDER BY name,company,location";
			$sinnerresult=mysql_query($querystring);
			if (!$sinnerresult) err("SQL Query Error: ".mysql_error(),"Filter database querying error");
			echo "<tr><td>ResourceID</td><td><SELECT NAME='resourceID'>";
			while ($sinnerrow = mysql_fetch_assoc($sinnerresult)) {
					if(isset($_POST['resourceID'])){
							if($resourceid==$sinnerrow['ID']){
									echo "<OPTION selected='selected' value='".$sinnerrow['ID']."'>&lt;".$sinnerrow['name']."&gt; ".$sinnerrow['company']." - ".$sinnerrow['location'];
							}else{
									echo "<OPTION value='".$sinnerrow['ID']."'>&lt;".$sinnerrow['name']."&gt; ".$sinnerrow['company']." - ".$sinnerrow['location'];
							}
					}else{
							echo "<OPTION value='".$sinnerrow['ID']."'>&lt;".$sinnerrow['name']."&gt; ".$sinnerrow['company']." - ".$sinnerrow['location'];
					}
			}
			echo "</SELECT></td></tr>";

			$querystring="SELECT * FROM customer order by ID";
			$innerresult=mysql_query($querystring);
			if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Filter database querying error");
			echo "<tr><td>Customer:&nbsp;</td><td><SELECT NAME='customer'>";
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					if(isset($_POST['customer'])){
							if($_POST['customer']==$innerrow['ID']){
									echo "<OPTION selected='selected' value='".$innerrow['ID']."'>&lt;".$innerrow['ID']."&gt; ".$innerrow['firstname']." ".$innerrow['lastname'];							
							}else{
									echo "<OPTION value='".$innerrow['ID']."'>&lt;".$innerrow['ID']."&gt; ".$innerrow['firstname']." ".$innerrow['lastname'];							
							}
					}else{
							echo "<OPTION value='".$innerrow['ID']."'>&lt;".$innerrow['ID']."&gt; ".$innerrow['firstname']." ".$innerrow['lastname'];
					}
			}
			
			if(isset($_POST['resourceID'])){
					$querystring="SELECT DATE_FORMAT(date,'%Y-%m-%d %H:%i') as date FROM resourceavailability where resourceID='".$resourceid."' order by date";
					$sinnerresult=mysql_query($querystring);
					if (!$sinnerresult) err("SQL Query Error: ".mysql_error(),"Filter database querying error");
					echo "<tr><td>Date: </td><td><SELECT NAME='date'>";
					while ($sinnerrow = mysql_fetch_assoc($sinnerresult)) {
						echo "<OPTION value='".$sinnerrow['date']."'>".$sinnerrow['date'];
					}
					echo "</SELECT></td></tr>";					
			}else{
					echo "<tr><td>Date: </td><td><SELECT NAME='date'><OPTION>None!</SELECT></td></tr>";														
			}

			if(isset($_POST['position'])){
					echo "<tr><td>Position:</td><td><input type='text' value='".$position."' name='position'></td><tr>";
			}else{
					echo "<tr><td>Position:</td><td><input type='text' value='position' name='position'></td><tr>";
			}

			if(isset($_POST['cost'])){
					echo "<tr><td>Cost:</td><td><input type='text' value='".$cost."' name='cost'></td><tr>";
			}else{
					echo "<tr><td>Cost:</td><td><input type='text' value='cost' name='cost'></td><tr>";
			}

			if(isset($_POST['rebate'])){
					echo "<tr><td>Rebate:</td><td><input type='text' value='".$rebate."' name='rebate'></td><tr>";
			}else{
					echo "<tr><td>Rebate:</td><td><input type='text' value='rbate' name='rebate'></td><tr>";
			}

			if(isset($_POST['auxdata'])){
					echo "<tr><td>Aux Data:</td><td><input type='text' value='".$auxdata."' name='auxdata'></td><tr>";
			}else{
					echo "<tr><td>Aux Data:</td><td><input type='text' value='auxdata' name='auxdata'></td><tr>";
			}
			
			echo "<tr><td>Status:</td><td>";
			echo "<SELECT name='status'>";
			echo "<OPTION value='0'>Preliminary";
			echo "<OPTION value='1'>Booked";
			echo "</SELECT>";
			echo "</td></tr>";

			echo "<tr><td><input name='Button' type='submit' value='Save'></td></tr>";							
			echo "</table>";
			echo "</form>";								

				
			if($button=='Save'){
					$querystring="INSERT INTO booking(customerID,resourceID,position,date,cost,rebate,status,auxdata) values ('".$_POST['customer']."','".$_POST['resourceID']."','".$_POST['position']."',DATE_FORMAT('".$_POST['date']."','%Y-%m-%d %H:%i'),'".$_POST['cost']."','".$_POST['rebate']."','".$_POST['status']."','".$_POST['auxdata']."');";
					$innerresult=mysql_query($querystring);								
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Insert Error!");
			}
			if($button=='Del'){			
					$querystring="DELETE FROM booking WHERE resourceID='".$_POST['resourceID']."' and date='".$_POST['date']."' and position='".$_POST['position']."';";
					$innerresult=mysql_query($querystring);													
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Delete Error!");
			}
			
			//---------------------------------------------------------------------------------------------------------------
			// Search Query!
			//---------------------------------------------------------------------------------------------------------------

			$querystring="SELECT * FROM booking,resourceavailability,resource where booking.resourceID=resourceavailability.resourceID and resource.ID=resourceavailability.resourceID and booking.date=resourceavailability.date";
			if(isset($_POST['filter'])) $querystring.=" and type='".$_POST['filter']."'";
			$querystring.=" order by name,company,location,booking.date,position";
			$innerresult=mysql_query($querystring);
			
			if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Query Error!");

			echo "<p>Press filter to update the resource list and the available booking list.</p>";

			echo "<table cellspacing=0>\n";
			echo "<tr><td style='border-left: 1px solid green;' class='head'>Customer</td><td class='head'>Resource</td><td class='head'>Name</td><td class='head'>Company</td><td class='head'>Location</td><td class='head'>Date</td><td class='head'>Date To</td><td class='head'>Position</td><td class='head'>Cost</td><td class='head'>Rebate</td><td class='head'>Status</td><td class='head'>Aux</td></tr>";
			$i=0;
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					// At the moment do nothing!
					if($i%2==0){
							echo "<tr style='background-color:#fff8f8;'>\n";
					}else{
							echo "<tr style='background-color:#ffffff;'>\n";					
					}
					echo "    <td class='result'>".$innerrow['customerID']."</id>\n";
					echo "    <td class='result'>".$innerrow['resourceID']."</id>\n";
					echo "    <td class='result'>".$innerrow['name']."</id>\n";
					echo "    <td class='result'>".$innerrow['company']."</id>\n";
					echo "    <td class='result'>".$innerrow['location']."</id>\n";
					echo "    <td class='result'>".$innerrow['date']."</td>\n";
					echo "    <td class='result'>".$innerrow['dateto']."</td>\n";
					echo "    <td class='result'>".$innerrow['position']."</td>\n";
					echo "    <td class='result'>".$innerrow['cost']."</td>\n";
					echo "    <td class='result'>".$innerrow['rebate']."</td>\n";
					echo "    <td class='result'>".$innerrow['status']."</td>\n";
					echo "    <td class='result'>".$innerrow['auxdata']."</td>\n";
					echo "<td class='result'>";
					echo "<form action='editbooking.php' method='POST'>";
					echo "<input type='hidden' name='customer' value='".$innerrow['customerID']."'>";
					echo "<input type='hidden' name='resourceID' value='".$innerrow['resourceID']."'>";
					echo "<input type='hidden' name='date' value='".$innerrow['date']."'>";
					echo "<input type='hidden' name='dateto' value='".$innerrow['dateto']."'>";
					echo "<input type='hidden' name='position' value='".$innerrow['position']."'>";
					echo "<input type='hidden' name='cost' value='".$innerrow['cost']."'>";
					echo "<input type='hidden' name='rebate' value='".$innerrow['rebate']."'>";
					echo "<input type='hidden' name='status' value='".$innerrow['status']."'>";
					echo "<input type='hidden' name='auxdata' value='".$innerrow['auxdata']."'>";

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
