<!DOCTYPE HTML PUBLIC
"-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Exploring Wines in a Region</title>
</head>
/*
The wine name, grape varieties, year, winery, and region.
2. The cost of the wine in the inventory.
3. The total number of bottles available at any price.
4. The total stock sold of the wine.
5. The total sales revenue for the wine.
*/
<body bgcolor="white">
<?php

	 function showerror() {
		die("Error " . mysql_errno() . " : " . mysql_error());
	}

	require_once('db.php');
 // Show all wines in a region in a <table>
  function displayWinesList($connection, $query, $regionName) {
    // Run the query on the server
	
	// (1) Open the database connection
	if (!($result = @ mysql_query ($query, $connection))) {
      showerror();
    }
	
	// Find out how many rows are available
    $rowsFound = @ mysql_num_rows($result);

    // If the query has results ...
    if ($rowsFound > 0) {
      // ... print out a header
      print "Wines of $regionName<br>";
	  
	   // Report how many rows were found
    print "{$rowsFound} records found matching your criteria<br>";

      // and start a <table>.
	print '<h2>Welcome to the Search Results Screen</h2>';
	print '<table cellpadding=0 cellspacing=0 border="1" width="100%">';
	print '<tr><td><b>Wine ID</b></td>'.'<td><b>Wine Name</b></td>'.'<td><b>Year</b></td>'.'<td><b>Winery Name</b></td>'.
		'<td><b>Region Name</b></td>'.'<td><b>Variety</b></td>'.'<td><b>Price</b></td>'.'<td><b>On Hand</b></td>'.
		'<td><b>Quantity</b></td>'.'<td><b>Sales Revenue</b></td></tr>'; 
		
	// Fetch each of the query rows
	while ($row = @ mysql_fetch_array($result)) {
	// Print one row of results
		print "<br><tr><td>{$row["wine_id"]}</td>".
		"<td>{$row["wine_name"]}</td>".
		"<td>{$row["year"]}</td>".
		"<td>{$row["winery_name"]}</td>".
		"<td>{$row["region_name"]}</td>".
		"<td>{$row["variety"]}</td>".
		"<td>{$row["price"]}</td>".
		"<td>{$row["on_hand"]}</td>".
		"<td>{$row["qty"]}</td>".
		"<td>{$row["sales_revenue"]}</td></tr>";
		}
	
	// Finish the <table>	
	echo '</table>';	
	
	}// end if $rowsFound body
	else {
	
		print "<br /><td colspan=10><b>No records match your search criteria</b></td><br />";
	
	}
	
	} // end of function
	
	// Connect to the MySQL server
	if (!($connection = @ mysql_connect(DB_HOST, DB_USER, DB_PW))) {
    die("Could not connect");
  }
  
	// get the user data
  $wine_name = $_GET['txtWineName'];
$winery_name = $_GET['txtWineryName'];
$region_name = $_GET['drpRegion'];
$grape_variety_name = $_GET['drpGrapeVariety'];
$from_yr = $_GET['drpFromYear'];
$to_yr = $_GET['drpToYear'];
$min_wines_stocked = $_GET['txtMinWinesInStock'];
$min_wines_ordered = $_GET['txtMinWinesOrdered'];
$min_cost = $_GET['txtMinCost'];
$max_cost = $_GET['txtMaxCost'];
  
   if (!mysql_select_db(DB_NAME, $connection)) {
    showerror();
  }
	
	// (2) Run the query on the winestore through the connection
	
	$query = 	"select distinct wn.wine_name,wn.wine_id,wn.year,wnry.winery_name,reg.region_name,grpv.variety,itm.price,inv.on_hand,
				itm.qty,(itm.price*itm.qty) as 'sales_revenue'
				from wine wn,winery wnry,region reg,grape_variety grpv,
				wine_variety wnvty,
				items itm,inventory inv
				where wn.winery_id = wnry.winery_id
				and wnry.region_id = reg.region_id
				and wn.wine_id = inv.wine_id
				and wn.wine_id = wnvty.wine_id
				and wnvty.variety_id = grpv.variety_id
				and wn.wine_id = itm.wine_id";
				
	$query .= 	" AND wn.wine_name LIKE  '{$wine_name}%'";
	$query .= 	" AND wnry.winery_name LIKE '" . mysql_real_escape_string($winery_name) . "%'";
	
	// ... then, if the user has specified a region, add the regionName
	// as an AND clause ...
	if (isset($region_name) && $region_name != "All")
	{
		$query .= 	" AND reg.region_name = '{$region_name}'";
	}
	
	$query .= 	" AND grpv.variety = '{$grape_variety_name}'";
	$query .= 	" AND ((wn.year >= {$from_yr})";
	$query .= 	" AND (wn.year <= {$to_yr}))";
	$query .= 	" AND inv.on_hand >= {$min_wines_stocked}";
	$query .= 	" AND itm.qty >= {$min_wines_ordered}";
	$query .=   " AND ((itm.price >= {$min_cost})";
	$query .=   " AND (itm.price <= {$max_cost}))";
	$query .= 	" GROUP BY wn.wine_name,wn.year,wnry.winery_name,reg.region_name,grpv.variety,inv.on_hand,itm.qty,(itm.price*itm.qty)";
	$query .=   " HAVING ((wn.year > {$from_yr} and wn.year < {$to_yr}));";
				
	// run the query and show the results
	displayWinesList($connection, $query, $regionName);

	// (4) Close the database connection
	mysql_close($connection);
					
?>
</body>
</html>