<?php

    if(isset($_POST['btn_productSales']) && ($_POST['randcheck'] == $_SESSION['random']) ) :
        
        $product = isset($_POST['show_product']) ? $_POST['show_product'] : '1';
        $result = mysqli_query($link, 
            "SELECT COUNT(*) AS total FROM tbl_sales WHERE pro_id = '$product'");

        $row = mysqli_fetch_object($result);

        if($result) :
            $total = true;
        endif;

        //echo $row->total;

    endif;  

    if(isset($_POST['btn_mostSoldServices'])) :
        
        $mostSold = mysqli_query($link, 
            "SELECT 
            sales.ser_id AS id, 
            services.ser_desc AS name 
        FROM 
            tbl_sales AS sales 
        INNER JOIN 
            tbl_services AS services ON
            sales.ser_id = services.ser_id
        GROUP BY sales.ser_id 
        ORDER BY sales.sal_id DESC LIMIT 1");

        $m = mysqli_fetch_object($mostSold);

        if($mostSold) :
            $most = true;
        endif;

        //echo $row->total;

    endif;


    if(isset($_POST['btn_perClient'])) :
        
        $customer = isset($_POST['show_customer']) ? $_POST['show_customer'] : '1';
        $resultPerClient = mysqli_query($link, 
            "SELECT 
            product.pro_desc AS productName, 
            service.ser_desc AS serviceName 
        FROM 
            tbl_sales AS sales 
        LEFT JOIN 
            tbl_products AS product ON
            sales.pro_id = product.pro_id
        LEFT JOIN 
            tbl_services AS service ON
            sales.ser_id = service.ser_id    
        WHERE sales.cli_id = '$customer' 
        ORDER BY sales.sal_id DESC");

        if($resultPerClient) :
            $perClient = true;
        endif;

    endif; 
    
    if(isset($_POST['btn_outOfStock'])) :
        
        $resultoutOfStock = mysqli_query($link, 
            "SELECT pro_desc AS productName FROM tbl_products WHERE pro_state = '0'");

        if($resultoutOfStock) :
            $outOfStock = true;
        endif;

    endif;
    
    if(isset($_POST['btn_perShopAssistant'])) :
        
        $assistant = isset($_POST['show_shop_assistants']) ? $_POST['show_shop_assistants'] : '1';
        $resultPerShopAssistant = mysqli_query($link, 
            "SELECT 
            products.pro_desc AS productName 
        FROM 
            tbl_sales AS sales 
            INNER JOIN 
            tbl_products AS products ON
            products.pro_id = sales.pro_id   
        WHERE sales.cli_id = '$assistant' 
        ORDER BY sales.sal_id DESC");

        if($resultPerShopAssistant) :
            $perShopAssistant = true;
        endif;

    endif; 

    if(isset($_POST['btn_ShopSales'])) :
        
        $shop = isset($_POST['show_shop_sales']) ? $_POST['show_shop_sales'] : '1';
        $resultShopSales = mysqli_query($link, 
            "SELECT 
            products.pro_desc AS productName 
        FROM 
            tbl_sales AS sales 
        INNER JOIN 
            tbl_shops AS shops ON
            shops.sho_id = sales.sho_id
        LEFT JOIN
            tbl_products AS products ON
            products.pro_id = sales.pro_id       
        WHERE sales.sho_id = '$shop' 
        ORDER BY sales.sal_id DESC");

        if($resultShopSales) :
            $perShopSales = true;
        endif;

    endif;    


    if(isset($_POST['btn_BestWorst'])) :
        
        $resultBestWorst = mysqli_query($link, 
            "SELECT 
            assistant.sas_fullname AS fullname,
            COUNT(sales.sas_id) AS count     
        FROM 
            tbl_sales AS sales 
        LEFT JOIN 
            tbl_shopassistants AS assistant ON
            assistant.sas_id = sales.sas_id    
        GROUP BY sales.sas_id 
        ORDER BY count DESC");

        if($resultBestWorst) :
            $bestWorst = true;
        endif;

    endif;
    


    

?>

<div class="row">

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" <?= isset($total) ? 'style="border: 2px solid purple;"' : '' ?>>
                <p class="card-description">Show the product sales</p>


                <pre class="queryCodeProductSales" style="font-size:11px;">SELECT COUNT(<span style="color: red;">*</span>) AS total FROM tbl_sales 
WHERE pro_id = '<span style="color: red;"><?= isset($product) ? $product : "?"; ?></span>'</pre>
                <a href="#" class="showQuery" data-extra="ProductSales" onclick="return false;" style="font-size:11px;">Query</a>

                <br /><hr />

                <div class="form-data">
                    <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <?php $random = rand(); $_SESSION['random'] = $random; ?>
                    <input type="hidden" value="<?= $random; ?>" name="randcheck" />

                    <?php 

                        $products = mysqli_query($link, "CALL getProducts()");
                    ?>

                    <div class="form-group">
                        <label for="show_product">Choose product</label>
                        <select name="show_product" class="form-control form-control-sm" id="show_product">
                            <option disabled="disabled" selected="selected">Please choose a product</option>
                            <?php while($p = mysqli_fetch_object($products)) : ?>
                                <option 
                                <?= (isset($product) && ($product == $p->pro_id)) ? 'selected="selected"' : '';?>
                                value="<?= $p->pro_id ?>"><?= $p->pro_desc ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <input type="submit" name="btn_productSales" class="btn btn-gradient-primary mr-2" value="Show product sales" />
                    </form>
                </div>

                <?php if(isset($total)) : ?>
                <div class="form-result" style="font-size: 12px; margin-top:20px; border: 1px solid #8e8e8e; background: #ebedf2; padding: 10px;">
                    Total number of sales for chosen product are: <strong><?= $row->total; ?></strong>
                </div>
                <?php endif; ?>


            </div>
        </div>
    </div>

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" <?= isset($most) ? 'style="border: 2px solid purple;"' : '' ?>>
                <p class="card-description">Show top sold services</p>

                <pre class="queryCodeSoldServices" style="font-size:11px;">SELECT 
    <strong>sales.ser_id</strong> AS <strong>id,</strong> 
    <strong>services.ser_desc</strong> AS <strong>name</strong> 
FROM 
    <strong>tbl_sales</strong> AS <strong>sales</strong> 
INNER JOIN 
    <strong>tbl_services</strong> AS <strong>services</strong> ON
    <strong>sales.ser_id = services.ser_id</strong>
GROUP BY <strong>sales.ser_id</strong> 
ORDER BY <strong>sales.sal_id</strong> DESC LIMIT 1</pre>
                <a href="#" class="showQuery" data-extra="SoldServices" onclick="return false;" style="font-size:11px;">Query</a>

                <br /><hr />


                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <input type="submit" name="btn_mostSoldServices" class="btn btn-gradient-primary mr-2" value="Show top sold services" />
                </form>

                <?php if(isset($most)) : ?>
                <div class="form-result" style="font-size: 12px; margin-top:20px; border: 1px solid #8e8e8e; background: #ebedf2; padding: 10px;">
                    Most sold service: <strong><?= $m->name; ?></strong> with database ID: <strong><?= $m->id; ?></strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" <?= isset($perClient) ? 'style="border: 2px solid purple;"' : '' ?>>
                <p class="card-description">Show products and services per client</p>

                <pre class="queryCodePerClient" style="font-size:11px;">SELECT 
    <strong>product.pro_desc</strong> AS <strong>productName,</strong> 
    <strong>service.ser_desc</strong> AS <strong>serviceName</strong> 
FROM 
    <strong>tbl_sales</strong> AS <strong>sales</strong> 
LEFT JOIN 
    <strong>tbl_products</strong> AS <strong>product</strong> ON
    <strong>sales.pro_id = product.pro_id</strong>
LEFT JOIN 
    <strong>tbl_services</strong> AS <strong>service</strong> ON
    <strong>sales.ser_id = service.ser_id</strong>    
WHERE <strong>sales.cli_id = '<span style="color: red;"><?= isset($customer) ? $customer : "?"; ?></span>'</strong> 
ORDER BY <strong>sales.sal_id</strong> DESC</pre>
                <a href="#" class="showQuery" data-extra="PerClient" onclick="return false;" style="font-size:11px;">Query</a>

                <br /><hr />
                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <?php 
                        mysqli_next_result($link);
                        $customers = mysqli_query($link, "CALL Customers('selectall','','','','','')");
                    ?>
                    <div class="form-group">
                        <label for="show_customer">Choose client</label>
                        <select name="show_customer" class="form-control form-control-sm" id="show_customer">
                            <option disabled="disabled" selected="selected">Please choose a client</option>
                            <?php while($c = mysqli_fetch_object($customers)) : ?>
                                <option 
                                <?= (isset($customer) && ($customer == $c->cli_id)) ? 'selected="selected"' : 'aa';?>
                                value="<?= $c->cli_id ?>"><?= $c->cli_fname . " " . $c->cli_fname ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    
                    <input type="submit" name="btn_perClient" class="btn btn-gradient-primary mr-2" value="Show products/services per client" />
                </form>

                <?php if(isset($perClient)) : ?>
                <div class="form-result" style="font-size: 12px; margin-top:20px; border: 1px solid #8e8e8e; background: #ebedf2; padding: 10px;">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Product name</th>
                            <th>Service name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pc = mysqli_fetch_object($resultPerClient)) : ?>
                        <tr>
                            <td><?= $pc->productName ?></td>
                            <td><?= $pc->serviceName ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                    
                </div>
                <?php endif; ?>                

            </div>
        </div>
    </div>
    
    
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" <?= isset($outOfStock) ? 'style="border: 2px solid purple;"' : '' ?>>
                <p class="card-description">Show products out of stock</p>


                <pre class="queryCodeOutOfStock" style="font-size:11px;">SELECT <strong>pro_desc</strong> AS <strong>productName</strong> 
FROM <strong>tbl_products</strong> WHERE <strong>pro_state = '<span style="color: red;">0</span>'</strong></pre>
                <a href="#" class="showQuery" data-extra="OutOfStock" onclick="return false;" style="font-size:11px;">Query</a>

                <br /><hr />

                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <input type="submit" name="btn_outOfStock" class="btn btn-gradient-primary mr-2" value="Show products out of stock" />
                </form>

                <?php if(isset($outOfStock)) : ?>
                <div class="form-result" style="font-size: 12px; margin-top:20px; border: 1px solid #8e8e8e; background: #ebedf2; padding: 10px;">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($out = mysqli_fetch_object($resultoutOfStock)) : ?>
                            <tr>
                                <td><?= $out->productName ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>


            </div>
        </div>
    </div>


    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" <?= isset($perShopAssistant) ? 'style="border: 2px solid purple;"' : '' ?>>
                <p class="card-description">Show sales for the Shop Assistant</p>

                <pre class="queryCodeShopAssistant" style="font-size:11px;">SELECT 
    <strong>products.pro_desc</strong> AS <strong>productName</strong> 
FROM 
    <strong>tbl_sales</strong> AS <strong>sales</strong> 
INNER JOIN 
    <strong>tbl_products</strong> AS <strong>products</strong> ON
    <strong>products.pro_id = sales.pro_id</strong>   
WHERE <strong>sales.cli_id = '<span style="color: red;"><?= isset($assistant) ? $assistant : "?"; ?></span>'</strong> 
ORDER BY <strong>sales.sal_id</strong> DESC</pre>

                <a href="#" class="showQuery" data-extra="ShopAssistant" onclick="return false;" style="font-size:11px;">Query</a>

                <br /><hr />

                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <?php 
                        mysqli_next_result($link);
                        $assistants = mysqli_query($link, "CALL ShopAssistant('selectall','','','','','')");
                    ?>
                    <div class="form-group">
                        <label for="show_shop_assistants">Select shop assistant</label>
                        <select name="show_shop_assistants" class="form-control form-control-sm" id="show_shop_assistants">
                            <option disabled="disabled" selected="selected">Please choose a client</option>
                            <?php while($a = mysqli_fetch_object($assistants)) : ?>
                                <option 
                                <?= (isset($assistant) && ($assistant == $a->sas_id)) ? 'selected="selected"' : '';?>
                                value="<?= $a->sas_id ?>"><?= $a->sas_fullname ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    
                    <input type="submit" name="btn_perShopAssistant" class="btn btn-gradient-primary mr-2" value="Show sales per shop assistant" />
                </form>

                <?php if(isset($perShopAssistant)) : ?>
                <div class="form-result" style="font-size: 12px; margin-top:20px; border: 1px solid #8e8e8e; background: #ebedf2; padding: 10px;">
                    <?php 
                        $i = 1;
                        if(mysqli_num_rows($resultPerShopAssistant) > 0) : 
                            while ($rpsa = mysqli_fetch_object($resultPerShopAssistant)) : 
                                echo $i.'). <strong>'.$rpsa->productName.'</strong><br />';
                            $i++; endwhile;
                        else :
                            echo "No sales yet!";
                        endif;
                    ?>
                </div>
                <?php endif; ?>


            </div>
        </div>
    </div>
    
    
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" <?= isset($perShopSales) ? 'style="border: 2px solid purple;"' : '' ?>>
                <p class="card-description">Show sales for shop</p>

                <pre class="queryCodeShopSales" style="font-size:11px;">SELECT 
    <strong>products.pro_desc</strong> AS <strong>productName</strong> 
FROM 
    <strong>tbl_sales</strong> AS <strong>sales</strong> 
INNER JOIN 
    <strong>tbl_shops</strong> AS <strong>shops</strong> ON
    <strong>shops.sho_id = sales.sho_id</strong>
LEFT JOIN
    <strong>tbl_products</strong> AS <strong>products</strong> ON
    <strong>products.pro_id = sales.pro_id</strong>       
WHERE <strong>sales.sho_id = '<span style="color: red;"><?= isset($shop) ? $shop : "?"; ?></span>'</strong> 
ORDER BY <strong>sales.sal_id</strong> DESC</pre>
                <a href="#" class="showQuery" data-extra="ShopSales" onclick="return false;" style="font-size:11px;">Query</a>

                <br /><hr />

                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <?php 
                        mysqli_next_result($link);
                        $shopSales = mysqli_query($link, "CALL getShops()");
                    ?>
                    <div class="form-group">
                        <label for="show_shop_sales">Select shop</label>
                        <select name="show_shop_sales" class="form-control form-control-sm" id="show_shop_sales">
                            <option disabled="disabled" selected="selected">Please choose a client</option>
                            <?php while($ss = mysqli_fetch_object($shopSales)) : ?>
                                <option 
                                <?= (isset($shop) && ($shop == $ss->sho_id)) ? 'selected="selected"' : '';?>
                                value="<?= $ss->sho_id ?>"><?= $ss->sho_name ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    
                    <input type="submit" name="btn_ShopSales" class="btn btn-gradient-primary mr-2" value="Show sales for the shop" />
                </form>

                <?php if(isset($perShopSales)) : ?>
                <div class="form-result" style="font-size: 12px; margin-top:20px; border: 1px solid #8e8e8e; background: #ebedf2; padding: 10px;">
                    <?php 
                        $i = 1;
                        if(mysqli_num_rows($resultShopSales) > 0) : 
                            while ($sh = mysqli_fetch_object($resultShopSales)) : 
                                echo $i.'). <strong>'.$sh->productName.'</strong><br />';
                            $i++; endwhile;
                        else :
                            echo "No sales yet for this shop!";
                        endif;
                    ?>
                </div>
                <?php endif; ?>


            </div>
        </div>
    </div>   
    

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body" <?= isset($bestWorst) ? 'style="border: 2px solid purple;"' : '' ?>>
                <p class="card-description">Show the best and the worst selling shop assistant</p>

                <pre class="queryCodeBestWorst" style="font-size:11px;">SELECT 
    <strong>assistant.sas_fullname</strong> AS <strong>fullname,</strong>
    <strong>COUNT(sales.sas_id)</strong> AS <strong>count</strong>     
FROM 
    <strong>tbl_sales</strong> AS <strong>sales</strong> 
LEFT JOIN 
    <strong>tbl_shopassistants</strong> AS <strong>assistant</strong> ON
    <strong>assistant.sas_id = sales.sas_id</strong>    
GROUP BY <strong>sales.sas_id</strong> 
ORDER BY <strong>count</strong> DESC</pre>
                <a href="#" class="showQuery" data-extra="BestWorst" onclick="return false;" style="font-size:11px;">Query</a>

                <br /><hr />

                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                    <input type="submit" name="btn_BestWorst" class="btn btn-gradient-primary mr-2" value="Show best/worst shop assistant" />
                </form>

                <?php if(isset($bestWorst)) : ?>
                <div class="form-result" style="font-size: 12px; margin-top:20px; border: 1px solid #8e8e8e; background: #ebedf2; padding: 10px;">
                    <?php 
                        mysqli_data_seek($resultBestWorst, 0);
                        $first = mysqli_fetch_object($resultBestWorst);
                        echo $first->fullname . " with <strong>" . $first->count ."</strong> sales total<br />";

                        $numRows = mysqli_num_rows($resultBestWorst);

                        mysqli_data_seek($resultBestWorst,  ($numRows-1));
                        $last = mysqli_fetch_object($resultBestWorst);
                        echo $last->fullname . " with <strong>" . $last->count ."</strong> sales total<br />";
                    ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>       
    
    



</div>