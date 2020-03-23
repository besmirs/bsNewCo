<?php

    //$uri = $_SERVER['REQUEST_URI'];

    if(isset($_POST['btn_addSale']) && ($_POST['randcheck'] == $_SESSION['random']) ) :

        if( isset($_POST['sell_product']) && isset($_POST['sell_services']) 
            && isset($_POST['sell_shop']) && isset($_POST['sell_shop_assistant'])
            && ($_POST['sell_shop_assistant'] != 0) 
            && isset($_POST['sell_customer']))
        {
            
            $chosen_product = $_POST['sell_product'];
            $chosen_service = $_POST['sell_services'];
            $chosen_shop = $_POST['sell_shop'];
            $chosen_shop_assistant = $_POST['sell_shop_assistant'];
            $chosen_customer = $_POST['sell_customer'];
            
            $result = mysqli_query($link, 
                "CALL SellProduct(
                    '$chosen_service', 
                    '$chosen_product',
                    '$chosen_shop_assistant',
                    '$chosen_shop',
                    '$chosen_customer'
                    )") or die(mysqli_error($link));

            if($result) :
                $message = "New sale added successfully!";
                $alert = "success";
            endif;

        
        } else {
            $message = "All fields are required!";
            $alert = "warning";
        }
    endif;

?>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <?php
                if(isset($message)) :
                ?>
                  <div class="alert alert-<?= $alert; ?> fade show" role="alert">
                    <?= $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                <?php
                endif;
                  
                if(!isset($_GET['action'])) : 

                    $products = mysqli_query($link, "CALL getProducts()");
                    mysqli_next_result($link);

                    $shops = mysqli_query($link, "CALL getShops()");
                    mysqli_next_result($link);

                    $customers = mysqli_query($link, "CALL Customers('selectall', '', '', '', '', '')");
                
                ?>

                <p class="card-description">Add new sale to customer</p>
                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <?php $random = rand(); $_SESSION['random'] = $random; ?>
                <input type="hidden" value="<?= $random; ?>" name="randcheck" />

                    <div class="form-group">
                        <label for="sell_product">Choose product</label>
                        <select name="sell_product" class="form-control form-control-sm" id="productsAll">
                            <option disabled="disabled" selected="selected">Please choose a product</option>
                            <?php while($p = mysqli_fetch_object($products)) : ?>
                                <option value="<?= $p->pro_id ?>"><?= $p->pro_desc ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sell_services">Selected services for the chosen product</label>
                        <select name="sell_services" class="form-control form-control-sm" id="servicesAll">
                            <option disabled="disabled" selected="selected">Please choose a product</option>
                        </select>
                    </div>                    

                    <div class="form-group">
                        <label for="sell_shop">Select shop</label>
                        <select name="sell_shop" class="form-control form-control-sm" id="sell_shop">
                            <option disabled="disabled" selected="selected">Please choose a shop</option>
                            <?php while($s = mysqli_fetch_object($shops)) : ?>
                                <option value="<?= $s->sho_id ?>"><?= $s->sho_name ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sell_shop_assistant">Available shop assistants for chosen shop</label>
                        <select name="sell_shop_assistant" class="form-control form-control-sm" id="shopAssistentAll">
                            <option disabled="disabled" selected="selected">Please choose a shop</option>
                        </select>
                    </div>   

                    <div class="form-group">
                        <label for="sell_customer">Choose customer</label>
                        <select name="sell_customer" class="form-control form-control-sm" id="sell_customer">
                        <option disabled="disabled" selected="selected">Please choose a customer</option>
                            <?php while($s = mysqli_fetch_object($customers)) : ?>
                                <option value="<?= $s->cli_id ?>"><?= $s->cli_fname . " " . $s->cli_lname ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>                        
               

                    <input type="submit" name="btn_addSale" class="btn btn-gradient-primary mr-2" value="Sell the product" />
                </form>
                <?php
                endif;
                //End new service  
    mysqli_close($link);
?>

            </div>
        </div>
    </div>
</div>