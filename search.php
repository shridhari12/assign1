<html>
<body>
	<title>Search Winestore</title>
	<?php
	error_reporting(E_ALL); ini_set('display_errors','On');
	$send_form = true;
	$valmsg = "";
	if (isset($_GET['btnSearch']))
	{
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
		$search_result_url = 'http://54.252.202.20/assign1/partB/search_results.php?txtWineName='.$wine_name.'&txtWineryName='.$winery_name.'&drpRegion='.$region_name.'&drpGrapeVariety='.$grape_variety_name.'&drpFromYear='.$from_yr.'&drpToYear='.$to_yr.'&txtMinWinesInStock='.$min_wines_stocked.'&txtMinWinesOrdered='.$min_wines_ordered.'&txtMinCost='.$min_cost.'&txtMaxCost='.$max_cost;
		
		if (empty($wine_name))
		{
			$valmsg .= "Wine Name cannot be empty<br>";
			$send_form = false;
		}
		if (!$winery_name)
		{
			$valmsg .= "Winery Name cannot be empty<br>";
			$send_form = false;
		}	
		if (is_numeric($wine_name))
		{
			$valmsg .= "Wine Name cannot be numeric<br>";
			$send_form = false;
		}	
		if (is_numeric($winery_name))
		{
			$valmsg .= "Winery Name cannot be numeric<br>";	
			$send_form = false;
		}	
		if ($from_yr > $to_yr)
		{
			$valmsg .= "From Year cannot be greater than To Year<br>";
			$send_form = false;
		}	
		if (!is_numeric($min_wines_stocked))
		{
			$valmsg .= "Minimum Wines Stocked has to be numeric<br>";
			$send_form = false;
		}	
		if (!is_numeric($min_wines_ordered))
		{
			$valmsg .= "Minimum Wines Ordered has to be numeric<br>";
			$send_form = false;
		}
		if (!is_numeric($min_cost))
		{
			$valmsg .= "Minimum Cost has to be numeric<br>";
			$send_form = false;
		}	
		if (!is_numeric($max_cost))
		{
			$valmsg .= "Maximum Cost has to be numeric<br>";	
			$send_form = false;
		}

		if (($min_cost > $max_cost) && (is_numeric($min_cost) && (is_numeric($max_cost))))
		{
			$valmsg .= "Minimum cost cannot be greater than Maximum cost";
			$send_form = false;
		}
		print $valmsg;
		print $search_result_url;
		//exit();
		
		if ($valmsg != "")
			print "<font color='red'>".$valmsg."</font>";
		else 
		{
			print "send form = ". $send_form;
			header("Location: ".$search_result_url);
		}
		
	}
?>
	<form method="GET" id="frmSearch">
	<table cellpadding="0" cellspacing="0" border="1" width="70%">
		<tr>
			<td>Wine Name : </td>
			<td><input type="text" name="txtWineName" /></td>
		</tr>
		<tr>
			<td>Winery Name :</td>
			<td><input type="text" name="txtWineryName" /></td>
		</tr>
		<tr>
			<td>Region : </td>
			<td><select name="drpRegion">
					<?php
					require_once('db.php');

					// (1) Open the database connection
					$connection = mysql_connect(DB_HOST, DB_USER, DB_PW);
					mysql_select_db("winestore", $connection);

					// (2) Run the query on the winestore through the connection
					$query = "SELECT region_name FROM region";
					$result = mysql_query($query, $connection);

					// (3) While there are still rows in the result set
					while ($row = mysql_fetch_row($result)) {
						for ($i = 0; $i < mysql_num_fields($result); $i++) {
						echo "<option>".$row[$i] . "</option>";
					}
					// Print a carriage return to neaten the output
					echo "\n";
					}	

					// (4) Close the database connection
					mysql_close($connection);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Grape Variety : </td>
			<td><select name="drpGrapeVariety">
				<?php
					require_once('db.php');

					// (1) Open the database connection
					$connection = mysql_connect(DB_HOST, DB_USER, DB_PW);
					mysql_select_db("winestore", $connection);

					// (2) Run the query on the winestore through the connection
					$query = "SELECT variety FROM grape_variety";
					$result = mysql_query($query, $connection);

					// (3) While there are still rows in the result set
					while ($row = mysql_fetch_row($result)) {
						for ($i = 0; $i < mysql_num_fields($result); $i++) {
						echo "<option>".$row[$i] . "</option>";
					}
					// Print a carriage return to neaten the output
					echo "\n";
					}	

					// (4) Close the database connection
					mysql_close($connection);
					?>
			</select>
			</td>
		</tr>
		<tr>
			<td>From Year : </td>
			<td>
				<select name="drpFromYear">
				<?php
					require_once('db.php');

					// (1) Open the database connection
					$connection = mysql_connect(DB_HOST, DB_USER, DB_PW);
					mysql_select_db("winestore", $connection);

					// (2) Run the query on the winestore through the connection
					$query = "SELECT distinct year FROM wine ORDER BY year ASC";
					$result = mysql_query($query, $connection);

					// (3) While there are still rows in the result set
					while ($row = mysql_fetch_row($result)) {
						for ($i = 0; $i < mysql_num_fields($result); $i++) {
						echo "<option>".$row[$i] . "</option>";
					}
					// Print a carriage return to neaten the output
					echo "\n";
					}	

					// (4) Close the database connection
					mysql_close($connection);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>To Year : </td>
			<td>
				<select name="drpToYear">
					<?php
					require_once('db.php');

					// (1) Open the database connection
					$connection = mysql_connect(DB_HOST, DB_USER, DB_PW);
					mysql_select_db("winestore", $connection);

					// (2) Run the query on the winestore through the connection
					$query = "SELECT distinct year FROM wine ORDER BY year DESC";
					$result = mysql_query($query, $connection);

					// (3) While there are still rows in the result set
					while ($row = mysql_fetch_row($result)) {
						for ($i = 0; $i < mysql_num_fields($result); $i++) {
						echo "<option>".$row[$i] . "</option>";
					}
					// Print a carriage return to neaten the output
					echo "\n";
					}	

					// (4) Close the database connection
					mysql_close($connection);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Min # of Wines in Stock : </td>
			<td><input name="txtMinWinesInStock" type="text" value="0" /></td>
		</tr>
		<tr>
			<td>Min # of Wines Ordered : </td>
			<td><input name="txtMinWinesOrdered" type="text" value="0" /></td>
		</tr>
		<tr>
			<td>Min Cost : </td>
			<td><input name="txtMinCost" type="text" value="5" /></td>
		</tr>
		<tr>
			<td>Max Cost : </td>
			<td><input name="txtMaxCost" type="text" value="330" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="btnSearch" id="btnSearch" value="Search" /></td>
		</tr>
	</table> 
</form>
</body>
</html>
