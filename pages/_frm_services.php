<?php

    //$uri = $_SERVER['REQUEST_URI'];

    if(isset($_POST['btn_addService']) && ($_POST['randcheck'] == $_SESSION['random']) ) :

        if(!empty($_POST['service_desc']) && !empty($_POST['service_price']))
        {
            $price = strip_tags($_POST['service_price']);
            $price = is_numeric($price) ? $price : '0.00';

            $desc = strip_tags($_POST['service_desc']);
            $active = isset($_POST['service_active']) ? 1 : 0;

            $result = mysqli_query($link, "CALL insertServices('$desc', '$price', '$active')");

            if($result) :
                $message = "Service successfully added!";
                $alert = "success";
            endif;

        } else {
            $message = "All fields are required!";
            $alert = "warning";
        }

        //mysqli_free_result($result);

    endif;

    if(isset($_POST['btn_editService'])) :
        if(!empty($_POST['edit_service_desc']) && !empty($_POST['edit_service_price']))
        {
            $editDesc = strip_tags(mysqli_real_escape_string($link, $_POST['edit_service_desc']));
            $editPrice = is_numeric($_POST['edit_service_price']) ? (float)($_POST['edit_service_price']) : '0.00';
            $editActive = isset($_POST['edit_service_active']) ? 1 : 0;

            $update_result = mysqli_query($link, "CALL updateService('$editDesc', '$editPrice', '$editActive', '".$_GET['serviceId']."')");
        
            if($update_result) :
                $message = "Service updated successfully!";
                $alert = "success";
            endif;

        } else {
            $message = "Service fields cannot be empty!";
            $alert = "warning";
        }

    endif;


    if(isset($_GET['action']) && ($_GET['action'] == 'delete') && 
        isset($_GET['serviceId']) ) :

        $delete_service = mysqli_query($link, "CALL deleteService('".$_GET['serviceId']."')");

        if($delete_service) :
            $message = "Service deleted successfully!";
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
                
                ?>

                <p class="card-description">Add new service</p>
                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <?php $random = rand(); $_SESSION['random'] = $random; ?>
                <input type="hidden" value="<?= $random; ?>" name="randcheck" />
                    <div class="form-group">
                        <label for="service_desc">Description</label>
                        <textarea class="form-control" rows="10" id="editor" required name="service_desc"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="service_price">Price</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">&euro;</span>
                            </div>

                            <div class="input-group-prepend">
                                <span class="input-group-text">0.00</span>
                            </div>

                            <input type="text" class="form-control" required name="service_price" aria-label="Price">
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="services_active">Active</label>
 
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" name="service_active" class="form-check-input" checked=""> Checked <i class="input-helper"></i>
                            </label>
                        </div>
                    </div>

                    <input type="submit" name="btn_addService" class="btn btn-gradient-primary mr-2" value="Add new service" />
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
        $services = mysqli_query($link, "CALL getServices()");

        if(!isset($_GET['serviceId'])) 
        {
?>
    <p class="card-description">Edit/modify services</p>
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
                    <a href="dashboard.php?page=services&action=edit&serviceId=<?= $s['ser_id'] ?>" class="btn btn-outline-secondary btn-icon-text"> 
                        Edit <i class="mdi mdi-file-check btn-icon-append"></i>
                    </a>
                </td>
            </tr>
            <?php $i++; endwhile; mysqli_free_result($services); ?>
        </tbody>
    </table>
<?php

        } else {

            //Edit-modify service, if serviceId is set
            mysqli_next_result($link);

            $currentService = mysqli_query($link, "CALL getCurrentService(".$_GET['serviceId'].")");
            $cs = mysqli_fetch_array($currentService);
            

    ?>
    
    <p class="card-description">Edit service</p>
        <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">

            <div class="form-group">
                <label for="service_desc">Description</label>
                <textarea class="form-control" rows="10" name="edit_service_desc"><?= trim($cs['ser_desc']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="service_price">Price</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">&euro;</span>
                    </div>

                    <div class="input-group-prepend">
                        <span class="input-group-text">0.00</span>
                    </div>

                    <input type="text" class="form-control" name="edit_service_price" value="<?= $cs['ser_price'] ?>" aria-label="Price">
                </div>
            </div>

            <div class="form-group">
                <label for="services_active">Active</label>
 
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="edit_service_active"
                         <?= ($cs['ser_active'] == 1) ? "checked=\"\"" : NULL; ?>
                        class="form-check-input"> Checked <i class="input-helper"></i>
                    </label>
                </div>
            </div>

            <input type="submit" name="btn_editService" class="btn btn-gradient-primary mr-2" value="Edit service" />
            <a style="float: right;" href="dashboard.php?page=services&action=edit" class="btn btn-gradient-primary mr-2 btn-icon-text deleteService"> 
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