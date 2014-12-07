<?PHP
			include 'dbconnect.php';
			
			// Get and escape the variables from post
			$resource=getpostAJAX("resourceID");
			$date=getpostAJAX("date");
			$dateto=getpostAJAX("dateto");
			$user=getpostAJAX("customerID");
			$rebate=getpostAJAX("rebate");
			$status=getpostAJAX("status");
			$position=getpostAJAX("position");
			$auxdata=getpostAJAX("auxdata");
			
			// Delete temp bookings for this user
			$querystring="DELETE FROM booking WHERE status=1 and customerID='".$user."'";
			
			$innerresult=mysql_query($querystring);		
			if (!$innerresult) err("Could not delete temp bookings for customer $user ".$querystring."\n",""); 

			// Compute current price
			$querystring="SELECT count(*) as counted FROM booking where resourceid='".$resource."' and date='".$date."'";
			$sinnerresult=mysql_query($querystring);
			if (!$sinnerresult) err("Resource error".mysql_error(),"");

			while ($sinnerrow = mysql_fetch_assoc($sinnerresult)) {
					$counted=$sinnerrow['counted'];
			}
			
			// Collate data for booking
			$querystring="SELECT * FROM resource where id='".$resource."'";
			$sinnerresult=mysql_query($querystring);
			if (!$sinnerresult) err("Resource error".mysql_error(),"");
			if(  mysql_num_rows($sinnerresult)==0) err("Invalid resource ID ".$resource);
			// Fetch category, size and cost.
			while ($sinnerrow = mysql_fetch_assoc($sinnerresult)) {
					$category=$sinnerrow['category'];
					$size=$sinnerrow['size'];
					$cost=$sinnerrow['cost'];
			}

			// Compute real cost
			$remaining=$size-$counted;
			$bookingclass=0;
			$bookingcost=$cost;

			// Save booking.
			$querystring="INSERT INTO booking(customerID,resourceID,position,date,dateto,cost,rebate,status,auxdata) values ('".$user."','".$resource."','".$position."',DATE_FORMAT('".$date."','%Y-%m-%d %H:%i'),DATE_FORMAT('".$dateto."','%Y-%m-%d %H:%i'),'".$bookingcost."','".$rebate."','".$status."','".$auxdata."');";
			$innerresult=mysql_query($querystring);	
			if (!$innerresult) err("MySql Error ".mysql_error(),"");

			// Successfull booking
			header ("Content-Type:text/xml; charset=utf-8");  
			echo "<result category='".$category."' size='".$size."' bookingcost='".$bookingcost."' bokingclass='".$bookingclass."' remaining='".$remaining."'   />";		
?>