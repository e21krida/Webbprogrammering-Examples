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
			$ID=getpost("ID");
			$name=getpost("name");
			$type=getpost("type");
			$company=getpost("company");
			$size=getpost("size");
			$cost=getpost("cost");
			$location=getpost("location");
			$category=getpost("category");
			
			echo "<form action='editresource.php' method='POST' >";
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
			
			echo "<tr><td>ID:</td><td><input type='text' value='".$ID."' name='ID'></td>";
			echo "<td>Name:</td><td><input type='text' value='".$name."' name='name'></td></tr>";
			echo "<tr><td>Application:</td><td><input type='text' value='".$type."' name='type'></td>";
			echo "<td>Company:</td><td><input type='text' value='".$company."' name='company'></td></tr>";
			echo "<tr><td>Size:</td><td><input type='size' value='".$size."' name='size'></td>";
			echo "<td>Cost:</td><td><input type='text' value='".$cost."' name='cost'></td></tr>";
			echo "<tr><td>Location:</td><td><input type='text' value='".$location."' name='location'></td>";
			echo "<td>Category:</td><td><input type='text' value='".$category."' name='category'></td></tr>";
			echo "<tr><td><input name='Button' type='submit' value='Save'></td></tr>";
			echo "</table>";
			echo "</form>";									
				
			if($button=='Save'){
					$querystring="INSERT INTO resource values ('".$_POST['ID']."','".$_POST['name']."','".$_POST['type']."','".$_POST['company']."','".$_POST['location']."','".$_POST['category']."','".$_POST['size']."','".$_POST['cost']."');";
					$innerresult=mysql_query($querystring);								
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Insert Error!");
			}
			if($button=='Del'){			
					$querystring="DELETE FROM resource where ID='".$_POST['ID']."'";
					$innerresult=mysql_query($querystring);													
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Delete Error!");
			}
			
			//---------------------------------------------------------------------------------------------------------------
			// Search Query!
			//---------------------------------------------------------------------------------------------------------------

			$querystring="SELECT * FROM resource";
			if(isset($_POST['filter'])) $querystring.=" where type='".$_POST['filter']."'";
			$innerresult=mysql_query($querystring);
			        
			if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Conference filter database querying error");

			echo "<table cellspacing=0>\n";
			echo "<tr><td style='border-left: 1px solid green;' class='head'>ID</td><td class='head'>Name</td><td class='head'>Type</td><td class='head'>Company</td><td class='head'>Location</td><td class='head'>Category</td><td class='head'>Size</td><td class='head'>Cost</td></tr>";
			$i=0;
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					// At the moment do nothing!
					if($i%2==0){
							echo "<tr style='background-color:#fff8f8;'>\n";
					}else{
							echo "<tr style='background-color:#ffffff;'>\n";					
					}
					echo "    <td class='result'>".$innerrow['ID']."</id>\n";
					echo "    <td class='result'>".$innerrow['name']."</td>\n";
					echo "    <td class='result'>".$innerrow['type']."</td>\n";
					echo "    <td class='result'>".$innerrow['company']."</td>\n";
					echo "    <td class='result'>".$innerrow['location']."</td>\n";
					echo "    <td class='result'>".$innerrow['category']."</td>\n";	
					echo "    <td class='result'>".$innerrow['size']."</td>\n";
					echo "    <td class='result'>".$innerrow['cost']."</td>\n";
					echo "<td class='result'>";
					echo "<form action='editresource.php' method='POST'>";
					echo "<input type='hidden' name='ID' value='".$innerrow['ID']."'>";
					echo "<input type='hidden' name='name' value='".$innerrow['name']."'>";
					echo "<input type='hidden' name='type' value='".$innerrow['type']."'>";
					echo "<input type='hidden' name='company' value='".$innerrow['company']."'>";
					echo "<input type='hidden' name='location' value='".$innerrow['location']."'>";
					echo "<input type='hidden' name='category' value='".$innerrow['category']."'>";
					echo "<input type='hidden' name='size' value='".$innerrow['size']."'>";
					echo "<input type='hidden' name='cost' value='".$innerrow['cost']."'>";
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
