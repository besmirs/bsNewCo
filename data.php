<?php 
	require('mysqli.config.php');

	if(isset($_POST['productId'])) :
                $array = [];
                $result = mysqli_query($link, 
                        "CALL getProductServices('".$_POST['productId']."')");
                while($services = mysqli_fetch_object($result)) :
                $array[] = $services->id."-".$services->description;
                endwhile;
                echo json_encode($array);
        endif;
        
	if(isset($_POST['shopId'])) :
                $array = [];
                $result = mysqli_query($link, 
                        "CALL getShopAssistantsFromCurrentShop('".$_POST['shopId']."')");
                while($shop = mysqli_fetch_object($result)) :
                $array[] = $shop->sas_id."-".$shop->sas_fullname;
                endwhile;

                if(count($array) < 1) :
                        $array[] = "0-No assistants to current shop";
                endif;

                echo json_encode($array);
	endif;        

 ?>