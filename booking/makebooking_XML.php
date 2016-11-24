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

			if($user=="UNK"||$resource=="UNK"||$date=="UNK"){
					err("Missing Form Data: (user/resource/date)");					
			}

			try{
										
					// Delete temp bookings for this user
					$querystring="DELETE FROM booking WHERE status=1 and customerID=:CUSTID;";
					$stmt = $pdo->prepare($querystring);
					$stmt->bindParam(':CUSTID',$user);
					$stmt->execute();
				
					
					// Retrieve size and cost from resource
					$size=0;
					$cost=0;
					$querystring="SELECT * FROM resource WHERE ID=:RESID";
					$stmts = $pdo->prepare($querystring);
					$stmts->bindParam(':RESID',$resource);
					$stmts->execute();
					foreach($stmts as $key => $row){
							$size=$row['size'];
							$cost=$row['cost'];
					}				
					
					// Count number of booked resources
					$querystring="SELECT count(*) as counted FROM booking where resourceid=:RESID and date=:DATE";
					$stmts = $pdo->prepare($querystring);
					$stmts->bindParam(':RESID',$row['ID']);
					$stmts->bindParam(':DATE',$row['Date']);
					$stmts->execute();
		
					// Compute Remaining Resources for Date (equals)
					foreach($stmts as $kkey => $row){
							$counted=$row['counted'];
					}	
					$remaining=$size-$counted;
		
					// Save booking.
					$querystring="INSERT INTO booking(customerID,resourceID,position,date,dateto,cost,rebate,status,auxdata) values (:USER,:RESID,:POSITION,DATE_FORMAT(:DATE,'%Y-%m-%d %H:%i'),DATE_FORMAT(:DATETO,'%Y-%m-%d %H:%i'),:COST,:REBATE,:STATUS,:AUXDATA);";
					$stmts = $pdo->prepare($querystring);
					$stmts->bindParam(':USER',$user);
					$stmts->bindParam(':RESID',$resource);
					$stmts->bindParam(':POSITION',$position);
					$stmts->bindParam(':DATE',$date);
					$stmts->bindParam(':DATETO',$dateto);
					$stmts->bindParam(':COST',$cost);
					$stmts->bindParam(':REBATE',$rebate);
					$stmts->bindParam(':STATUS',$status);
					$stmts->bindParam(':AUXDATA',$auxdata);
					$stmts->execute();
		
					// Successfull booking
					header ("Content-Type:text/xml; charset=utf-8");  
					echo "<result size='".$size."' bookingcost='".$cost."' remaining='".$remaining."'   />";		

		} catch (PDOException $e) {
				err("Error!: ".$e->getMessage()."<br/>");
				die();
		}

?>