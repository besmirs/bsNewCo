<?php
function convertpage($page)
{	
	switch ($page)
	{
		case "services":
			$page = "_frm_services";
			break;
		case "products":
				$page = "_frm_products";
				break;
		case "shops":
				$page = "_frm_shops";
				break;	
		case "shopassistants":
				$page = "_frm_shopassistants";
			break;
		case "customers":
			$page = "_frm_customers";
			break;
		case "sell":
			$page = "_frm_sell";
			break;													
		case "home":
			$page = "default";
			break;
	}
	return $page;
}

function breadcrumb($action)
{	
	switch ($action)
	{
		case "home":
			$bread = "Dashboard";
			break;
		case "shops":
			$bread = "Shops";
			break;
		case "shopassistants":
			$bread = "Shop Assistants";
			break;	
		case "services":
			$bread = "Services";
			break;
		case "products":
			$bread = "Products";
			break;
		case "customers":
			$bread = "Customers";
			break;													
		case "sell":
			$bread = "Sell the product";
			break;
	}
	return $bread;
}


function getCity($cityId)
{
	include('mysqli.config.php');
	$city = mysqli_query($link, "CALL getCity($cityId)");
	$c = mysqli_fetch_object($city);
	return $c->cit_name;
}

function getShop($shopId)
{
	include('mysqli.config.php');
	$shop = mysqli_query($link, "CALL getCurrentShop($shopId)");
	$s = mysqli_fetch_object($shop);
	return $s->shopName;
}

?>