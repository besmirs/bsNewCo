<?php

    //$uri = $_SERVER['REQUEST_URI'];

    if(isset($_POST['btn_addProduct']) && ($_POST['randcheck'] == $_SESSION['random']) ) :

        if(!empty($_POST['product_desc']) && !empty($_POST['product_services'])
            && !empty($_POST['product_validity']) && !empty($_POST['product_state']))
        {

            $desc = strip_tags($_POST['product_desc']);
            $validity = $_POST['product_validity'];
            $state = is_numeric($_POST['product_state']) ? (int)$_POST['product_state'] : '0.00';

            $result = mysqli_query($link, "CALL insertProducts('$desc', '$validity', '$state', @rid)");
            $lastInsertId = mysqli_query($link, "SELECT @rid AS id");
            $last = mysqli_fetch_object($lastInsertId);

            if($result) :
                
                foreach($_POST['product_services'] as $p) : 
                    $resultServices = mysqli_query($link, "CALL insertServicesToProducts('$last->id', '$p')");
                endforeach;

                $message = "Service successfully added!";
                $alert = "success";
            endif;

        } else {
            $message = "All fields are required!";
            $alert = "warning";
        }
    endif;


    if(isset($_POST['btn_editService'])) :
        if(!empty($_POST['edit_product_desc']) && !empty($_POST['edit_product_validity']) 
          && !empty($_POST['edit_product_state']))
        {
            $editDesc = strip_tags(mysqli_real_escape_string($link, $_POST['edit_product_desc']));
            $editValidity = $_POST['edit_product_validity'];
            $editState = (int)$_POST['edit_product_state'];

            $update_result = mysqli_query($link, "CALL updateProduct('$editDesc', '$editValidity', '$editState', '".$_GET['productId']."')") or die(mysqli_error($link));
            
            if($update_result) :
                $pid = $_GET['productId'];
                $emptyTbl = mysqli_query($link, "CALL deleteServicesOnProduct('$pid')");

                if($emptyTbl) : 
                    foreach($_POST['edit_product_services'] as $p) :
                        $update_services2products = mysqli_query($link, "CALL insertServicesToProducts($pid, $p)");
                    endforeach;
                endif;

                $message = "Product updated successfully!";
                $alert = "success";
            endif;

        } else {
            $message = "Product fields cannot be empty!";
            $alert = "warning";
        }
    endif;


    if(isset($_GET['action']) && ($_GET['action'] == 'delete') && 
        isset($_GET['productId']) ) :

        $delete_service = mysqli_query($link, "CALL deleteProducts('".$_GET['productId']."')") or die(mysqli_error($link));

        if($delete_service) :
            $message = "Product and all associated services are deleted successfully!";
            $alert = "success";
        endif;

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
                    $i = 1;
                    $result = mysqli_query($link, "CALL getServices()");
                
                ?>

                <p class="card-description">Add new product</p>
                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <?php $random = rand(); $_SESSION['random'] = $random; ?>
                <input type="hidden" value="<?= $random; ?>" name="randcheck" />
                    <div class="form-group">
                        <label for="product_desc">Description</label>
                        <textarea class="form-control" rows="5" required name="product_desc"></textarea>
                    </div>

                    <div class="form-group">
                      <label for="selectServices">Services</label>
                      <select name="product_services[]" class="form-control form-control-sm" id="selectServices" multiple>
                      <?php while($s = mysqli_fetch_array($result)) : ?>
                        <option value=<?= $s['ser_id'] ?>><?= $i. ". ". $s['ser_desc'] ?></option>
                      <?php $i++; endwhile; ?>
                      </select>
                    </div>                    

                    <div class="form-group">
                        <label for="product_validity">Validity</label>
                        <input type="text" class="form-control" name="product_validity" id="product_validity" placeholder="Validity" autocomplete="off" style="width: 49%;">
                    </div>

                    <div class="form-group">
                        <label for="product_validity">State</label>
                        <input type="text" class="form-control" name="product_state" id="product_state" placeholder="State" style="width: 20%;">
                    </div>

                    <input type="submit" name="btn_addProduct" class="btn btn-gradient-primary mr-2" value="Add new product" />
                </form>
                <?php
                endif;
                //End new service

    /*****************************************
    ************* EDITING SECTION ************
    ** Dispaly section for editing services **
    ******************************************/

    if(isset($_GET['action']) && ($_GET['action'] == 'edit') ) :
        $i = 1;
        
        if(!isset($_GET['productId'])) 
        {
            $products = mysqli_query($link, "CALL getProducts()");
?>
    <p class="card-description">Edit/modify products</p>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th> # </th>
                <th>Description</th>
                <th>Validty</th>
                <th>State</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($s = mysqli_fetch_object($products)) : ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $s->pro_desc ?></td>
                <td><?= $s->pro_validity ?></td>
                <td><?= $s->pro_state ?></td>
                <td>
                    <a href="dashboard.php?page=products&action=edit&productId=<?= $s->pro_id ?>" class="btn btn-outline-secondary btn-icon-text"> 
                        Edit <i class="mdi mdi-file-check btn-icon-append"></i>
                    </a>
                </td>
            </tr>
            <?php $i++; endwhile; mysqli_free_result($products); ?>
        </tbody>
    </table>
<?php

        } else {

            //Edit-modify service, if serviceId is set
            mysqli_next_result($link);

            $currentProduct= mysqli_query($link, "CALL getCurrentProduct(".$_GET['productId'].")");

            if(mysqli_num_rows($currentProduct) > 0) {

                $c = mysqli_fetch_object($currentProduct);

                mysqli_next_result($link);
                $getServices = mysqli_query($link, "CALL getServices()");

                mysqli_next_result($link);
                $selected_services = mysqli_query($link, "CALL getProductServices('".$_GET['productId']."')");
                $array = [];

                while($q = mysqli_fetch_object($selected_services)) :
                    $array[] = $q->id;
                endwhile;
    ?>
    
    <p class="card-description">Edit product</p>
        <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">

            <div class="form-group">
                <label for="edit_product_desc">Description</label>
                <textarea class="form-control" rows="5" name="edit_product_desc" id="edit_product_desc"><?= trim($c->pro_desc) ?></textarea>
            </div>

            <div class="form-group">
                <label for="selectServices">Services</label>
                <select name="edit_product_services[]" class="form-control form-control-sm" id="selectServices" multiple>
                    <?php 
                    while($ps = mysqli_fetch_object($getServices)) : 
                        if(in_array($ps->ser_id, $array)) {
                            echo '<option value="'.$ps->ser_id.'" selected="selected">'.$ps->ser_desc.'</option>';
                        } else {
                            echo '<option value="'.$ps->ser_id.'">'.$ps->ser_desc.'</option>';
                        }
                    endwhile; 
                    ?>
                </select>
            </div> 

            <div class="form-group">
                <label for="product_validity">Validity</label>
                <input type="text" class="form-control" name="edit_product_validity" id="product_validity" value="<?= $c->pro_validity ?>" autocomplete="off" style="width: 49%;">
            </div>
 
            <div class="form-group">
                <label for="edit_product_state">State</label>
                <input type="text" class="form-control" name="edit_product_state" id="edit_product_state" value="<?= $c->pro_state ?>" style="width: 20%;">
            </div>

            <input type="submit" name="btn_editService" class="btn btn-gradient-primary mr-2" value="Edit product" />
            <a style="float: right;" href="dashboard.php?page=products&action=edit" class="btn btn-gradient-primary mr-2 btn-icon-text deleteService"> 
                Back <i class="mdi mdi-arrow-left-bold-circle-outline btn-icon-append"></i>
            </a>
        </form>    

    <?php
                mysqli_free_result($currentProduct);
                mysqli_free_result($getServices);
                mysqli_free_result($selected_services);
                
            } else {
                echo '
                    <div class="alert alert-secondary fade show" role="alert">
                        No product to show!
                    </div>
                    <a href="dashboard.php?page=products&action=edit" class="btn btn-gradient-primary mr-2 btn-icon-text deleteService"> 
                        Back <i class="mdi mdi-arrow-left-bold-circle-outline btn-icon-append"></i>
                    </a>';
            }
            

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
    $products = mysqli_query($link, "CALL getProducts()");
?>
    <p class="card-description">Delete products</p>
    <table class="table table-bordered table-hover">
    <thead>
            <tr>
                <th> # </th>
                <th>Description</th>
                <th>Validty</th>
                <th>State</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($s = mysqli_fetch_object($products)) : ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $s->pro_desc ?></td>
                <td><?= $s->pro_validity ?></td>
                <td><?= $s->pro_state ?></td>
                <td>
                    <a href="dashboard.php?page=products&action=delete&productId=<?= $s->pro_id ?>" 
                        onclick="if(confirm('Are you sure you want to delete current product and all associated services to this product?')) return true; else return false;" 
                        class="btn btn-gradient-danger btn-icon-text deleteService"> 
                        Delete <i class="mdi mdi-delete-forever btn-icon-append"></i>
                    </a>
                </td>
            </tr>
            <?php $i++; endwhile; mysqli_free_result($products); ?>
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