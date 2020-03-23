<?php

    //$uri = $_SERVER['REQUEST_URI'];

    if(isset($_POST['add_newShop']) && ($_POST['randcheck'] == $_SESSION['random']) ) :

        if(!empty($_POST['shop_name']))
        {
            $shop_name = strip_tags($_POST['shop_name']);
            $shop_city = $_POST['shop_city'];

            $result = mysqli_query($link, "CALL Shop('insert', '$shop_name', '$shop_city', '')");

            if($result) :
                $message = "Shop successfully added!";
                $alert = "success";
            endif;

        } else {
            $message = "All fields are required!";
            $alert = "warning";
        }

        //mysqli_free_result($result);

    endif;

    if(isset($_POST['btn_editShop'])) :
        if(!empty($_POST['edit_shop_name']))
        {
            $shop_name = strip_tags($_POST['edit_shop_name']);
            $shop_city = $_POST['edit_shop_city'];

            $update_result = mysqli_query($link, "CALL Shop('update', '$shop_name', '$shop_city', '".$_GET['shopId']."')");
        
            if($update_result) :
                $message = "Shop updated successfully!";
                $alert = "success";
            endif;

        } else {
            $message = "Shop fields cannot be empty!";
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
                
                    $cities = mysqli_query($link, "CAll getCities()");
                ?>

                <p class="card-description">Add new shop</p>
                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <?php $random = rand(); $_SESSION['random'] = $random; ?>
                <input type="hidden" value="<?= $random; ?>" name="randcheck" />

                <div class="form-group">
                    <label for="shop_name">Shop name</label>
                    <input type="text" class="form-control" name="shop_name" id="shop_name" placeholder="Shop name" autocomplete="off">
                </div>

                <div class="form-group">
                <label for="shop_city">City</label>
                <select name="shop_city" class="form-control form-control-sm" id="shop_city">
                    <?php while($c = mysqli_fetch_object($cities)) : ?>
                        <option value="<?= $c->cit_id ?>"><?= $c->cit_name ?></option>
                    <?php endwhile; ?>
                </select>
                </div>                

                <input type="submit" name="add_newShop" class="btn btn-gradient-primary mr-2" value="Add new shop" />
                </form>
                <?php
                endif;
                //End new service

    /*****************************************
    ************* EDITING SECTION ************
    ** Dispaly section for editing services **
    ******************************************/

    if(isset($_GET['action']) && ($_GET['action'] == 'edit') ) :
        
        

        if(!isset($_GET['shopId'])) 
        {
            $i = 1;
            $shops = mysqli_query($link, "CALL getShops()");
?>
    <p class="card-description">Edit/modify shops</p>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th> # </th>
                <th>Shop name</th>
                <th>City</th>
                <th><span style="float: right;">Action</span></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($shop = mysqli_fetch_object($shops)) : ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $shop->sho_name ?></td>
                <td><?= getCity($shop->cit_id) ?></td>
                <td>
                    <a href="dashboard.php?page=shops&action=edit&shopId=<?= $shop->sho_id ?>" style="float: right;" class="btn btn-outline-secondary btn-icon-text"> 
                        Edit <i class="mdi mdi-file-check btn-icon-append"></i>
                    </a>
                </td>
            </tr>
            <?php $i++; endwhile; mysqli_free_result($shops); ?>
        </tbody>
    </table>
<?php

        } else {

            //Edit-modify service, if serviceId is set
            mysqli_next_result($link);

            $currentShop = mysqli_query($link, "CALL getCurrentShop(".$_GET['shopId'].")");
            $r = mysqli_fetch_object($currentShop);

            mysqli_next_result($link);
            $cities = mysqli_query($link, "CAll getCities()");
    ?>
    
    <p class="card-description">Edit shop</p>
        <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">

        <div class="form-group">
            <label for="edit_shop_name">Shop name</label>
            <input type="text" class="form-control" name="edit_shop_name" id="edit_shop_name" 
                value="<?= $r->shopName ?>">
        </div>

        <div class="form-group">
            <label for="edit_shop_city">City</label>
            <select name="edit_shop_city" class="form-control form-control-sm" id="edit_shop_city">
                <?php 
                    while($c = mysqli_fetch_object($cities)) { 
                        if($r->cityId == $c->cit_id) {
                            echo '<option value="'.$c->cit_id.'" selected="selected">'.$c->cit_name.'</option>';
                        } else {
                            echo '<option value="'.$c->cit_id.'">'.$c->cit_name.'</option>';
                        }
                    } 
                ?>
            </select>
        </div>  

        <input type="submit" name="btn_editShop" class="btn btn-gradient-primary mr-2" value="Edit shop" />
        <a style="float: right;" href="dashboard.php?page=shops&action=edit" class="btn btn-gradient-primary mr-2 btn-icon-text deleteService"> 
            Back <i class="mdi mdi-arrow-left-bold-circle-outline btn-icon-append"></i>
        </a>
        </form>    

    <?php
        }

    endif;
    /*********************************************
    ************ END EDITING SECTION *************
    **********************************************/  


    /*****************************************
    ************* DELETE SECTION *************
    ****** Section for deleting services *****
    ******************************************/

    if(isset($_GET['action']) && ($_GET['action'] == 'delete') ) :
    $i = 1;
    $services = mysqli_query($link, "CALL getServices()");
?>
    <p class="card-description">Delete services</p>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th> # </th>
                <th>Description</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($s = mysqli_fetch_array($services)) : ?>
            <tr>
                <td><?= $i ?></td>
                <td>
                    <?php
                        if(strlen($s['ser_desc']) > 50) {
                            echo ucfirst(substr($s['ser_desc'], 0, 50) . " ...");
                        } else {
                            echo ucfirst($s['ser_desc']);
                        }
                    ?>
                </td>
                <td><?= $s['ser_price'] ?></td>
                <td><?= ($s['ser_active'] == 1) ? 'Active' : 'Not Active'; ?></td>
                <td>
                    <a href="dashboard.php?page=services&action=delete&serviceId=<?= $s['ser_id'] ?>" 
                        onclick="if(confirm('Are you sure you want to delete current service?')) return true; else return false;" 
                        class="btn btn-gradient-danger btn-icon-text deleteService"> 
                        Delete <i class="mdi mdi-delete-forever btn-icon-append"></i>
                    </a>
                </td>
            </tr>
            <?php $i++; endwhile; mysqli_free_result($services); ?>
        </tbody>
    </table>

<?php
    endif;
    /*********************************************
    ************ END DELETING SECTION ************
    **********************************************/  
    mysqli_close($link);
?>



            </div>
        </div>
    </div>
</div>