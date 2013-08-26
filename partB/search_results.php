<?php
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

/*
The wine name, grape varieties, year, winery, and region.
2. The cost of the wine in the inventory.
3. The total number of bottles available at any price.
4. The total stock sold of the wine.
5. The total sales revenue for the wine.
*/

	function showerror()
	{
		die("Error " . mysql_errno() . " : " . mysql_error());
	}

	require_once('db.php');

	// (1) Open the database connection
	if (!($connection = mysql_connect(DB_HOST, DB_USER, DB_PW)))
	{
		die("Could not connect to the database");
	}
	
	if (!(mysql_select_db("winestore", $connection)))
	{
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
				
	if (!($result = mysql_query($query, $connection)))
	{
		showerror();
	}
	
	echo '<h2>Welcome to the Search Results Screen</h2>';
	echo '<table cellpadding=0 cellspacing=0 border="1" width="100%">';
	echo '<tr><td><b>Wine ID</b></td>'.'<td><b>Wine Name</b></td>'.'<td><b>Year</b></td>'.'<td><b>Winery Name</b></td>'.
		'<td><b>Region Name</b></td>'.'<td><b>Variety</b></td>'.'<td><b>Price</b></td>'.'<td><b>On Hand</b></td>'.
		'<td><b>Quantity</b></td>'.'<td><b>Sales Revenue</b></td></tr>';

	while ($row = mysql_fetch_array($result)) {
		echo "<br><tr><td>{$row["wine_id"]}</td>".
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
		
	echo '</table>';

	// (4) Close the database connection
	mysql_close($connection);
					
?>