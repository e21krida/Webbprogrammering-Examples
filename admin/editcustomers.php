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

			if($button=='Save'||$button=='Del'){
					echo "<form action='editcustomers.php' method='POST' >";
					echo "<table>";
					echo "<tr><td>ID:</td><td><input type='text' value='".$_POST['ID']."' name='ID'></td>";
					echo "<td>Last Visit Time:</td><td><input type='text' value='".$_POST['lastvisit']."' name='lastvisit'></td></tr>";
					echo "<tr><td>Firstname:</td><td><input type='text' value='".$_POST['firstname']."' name='firstname'></td>";
					echo "<td>Lastname:</td><td><input type='text' value='".$_POST['lastname']."' name='lastname'></td></tr>";
					echo "<tr><td>Address:</td><td><input type='text' value='".$_POST['address']."' name='address'></td></tr>";
					echo "<tr><td>Emal:</td><td><input type='text' value='".$_POST['email']."' name='email'></td></tr>";
					echo "<tr><td><input name='Button' type='submit' value='Save'></td></tr>";
					echo "</table>";
					echo "</form>";									
			}else{
					echo "<form action='editcustomers.php' method='POST' >";
					echo "<table>";
					echo "<tr><td>ID:</td><td><input type='text' value='ID' name='ID'></td>";
					echo "<td>Last Visit Time:</td><td><input type='text' value='lastvisit' name='lastvisit'></td></tr>";
					echo "<tr><td>Firstname:</td><td><input type='text' value='firstname' name='firstname'></td>";
					echo "<td>Lastname:</td><td><input type='text' value='lastname' name='lastname'></td></tr>";
					echo "<tr><td>Address:</td><td><input type='text' value='address' name='address'></td></tr>";
					echo "<tr><td>Email:</td><td><input type='text' value='email' name='email'></td></tr>";
					echo "<tr><td><input name='Button' type='submit' value='Save'></td></tr>";
					echo "</table>";
					echo "</form>";								
			}
			
			if($button=='Save'){
					$querystring="INSERT INTO customer(ID,lastvisit,firstname,lastname,address,email) values ('".$_POST['ID']."','".$_POST['lastvisit']."','".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['address']."','".$_POST['email']."');";
					$innerresult=mysql_query($querystring);								
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Insert error");
			}
			if($button=='Del'){			
					$querystring="DELETE FROM customer where ID='".$_POST['ID']."'";
					$innerresult=mysql_query($querystring);													
					if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Delete error");
			}
			
			//---------------------------------------------------------------------------------------------------------------
			// Search Query!
			//---------------------------------------------------------------------------------------------------------------

			$querystring="SELECT * FROM customer";
			$innerresult=mysql_query($querystring);
			
			if (!$innerresult) err("SQL Query Error: ".mysql_error(),"Querying error");

			echo "<table cellspacing=0>\n";
			echo "<tr><td style='border-left: 1px solid green;' class='head'>ID</td><td class='head'>Lastvisit</td><td class='head'>Firstname</td><td class='head'>Lastname</td><td class='head'>address</td><td class='head'>email</td></tr>";
			$i=0;
			while ($innerrow = mysql_fetch_assoc($innerresult)) {
					// At the moment do nothing!
					if($i%2==0){
							echo "<tr style='background-color:#fff8f8;'>\n";
					}else{
							echo "<tr style='background-color:#ffffff;'>\n";					
					}
					echo "    <td class='result'>".$innerrow['ID']."</id>\n";
					echo "    <td class='result'>".$innerrow['lastvisit']."</td>\n";
					echo "    <td class='result'>".$innerrow['firstname']."</td>\n";
					echo "    <td class='result'>".$innerrow['lastname']."</td>\n";
					echo "    <td class='result'>".$innerrow['address']."</td>\n";
					echo "    <td class='email'>".$innerrow['email']."</td>\n";
					echo "<td class='result'>";
					echo "<form action='editcustomers.php' method='POST'>";
					echo "<input type='hidden' name='ID' value='".$innerrow['ID']."'>";
					echo "<input type='hidden' name='lastvisit' value='".$innerrow['lastvisit']."'>";
					echo "<input type='hidden' name='firstname' value='".$innerrow['firstname']."'>";
					echo "<input type='hidden' name='lastname' value='".$innerrow['lastname']."'>";
					echo "<input type='hidden' name='address' value='".$innerrow['address']."'>";
					echo "<input type='hidden' name='email' value='".$innerrow['email']."'>";
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
