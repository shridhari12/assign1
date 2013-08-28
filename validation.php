<?php
	$send_form = true;
	$valmsg = "";
	
	//if (isset($_GET['btnSearch']))
	//{
		//echo $_SERVER['HTTP_REFERER'];
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
		
		if (!$wine_name)
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
			
	//}
	
//echo "Post Value = " . $send_form ."<br/>";

if (true == $send_form)
{
	header('Location : search_results.php');
	//echo "<form method='GET' action='search_results.php'>";
}
else
{
//echo "<form method='GET' action=''>";
	header('Location :' .$_SERVER['HTTP_REFERER']);
	//echo $valmsg;
}
?>