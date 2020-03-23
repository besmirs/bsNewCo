<?php

    //$uri = $_SERVER['REQUEST_URI'];

    if(isset($_POST['add_newShopAssistant']) && ($_POST['randcheck'] == $_SESSION['random']) ) :

        if(!empty($_POST['as_name']) && !empty($_POST['as_username']) && !empty($_POST['as_password']))
        {
            $assistant_fname = ucfirst(strip_tags($_POST['as_name']));
            $assistant_user = strip_tags($_POST['as_username']);
            $assistant_pass = password_hash($_POST['as_username'], PASSWORD_DEFAULT);
            $assistant_shop = $_POST['as_shop'];

            $result = mysqli_query($link, 
                "CALL ShopAssistant(
                    'insert', 
                    '$assistant_fname', 
                    '$assistant_user',
                    '$assistant_pass',
                    '$assistant_shop',
                    ''
                    )");

            if($result) :
                $message = "Shop assistant successfully added!";
                $alert = "success";
            endif;

        } else {
            $message = "All fields are required!";
            $alert = "warning";
        }

        //mysqli_free_result($result);

    endif;

    if(isset($_POST['btn_editShopAssistants'])) :
        if(!empty($_POST['edit_as_name']))
        {
            if(isset($_POST['edit_as_username'])) {
                if($_POST['edit_as_username'] != $_SESSION['username']) {
                    $message = "Gotcha! Do not try to manipulate DOM!";
                    $alert = "danger";
                }
                
            } else {
            
                if(!empty($_POST['edit_as_password'])) {
                    if($_POST['edit_as_password'] != $_SESSION['password']) {
                        $password = password_hash($_POST['edit_as_password'], PASSWORD_DEFAULT);
                        echo "set: ".$password;
                    }
                } else {
                    $password = $_SESSION['password'];
                }
                    
                    $edit_assistant_fname = ucfirst(strip_tags($_POST['edit_as_name']));
                    $edit_assistant_shop = $_POST['edit_as_shop'];


                    $update_result = mysqli_query($link, 
                    "CALL ShopAssistant(
                        'update',
                        '$edit_assistant_fname', 
                        '".$_SESSION['username']."', 
                        '$password', 
                        '$edit_assistant_shop', 
                        '".$_GET['assistantId']."'
                    )") or die(mysqli_error($link));
            
                    if($update_result) :
                        $message = "Shop assistant updated successfully!";
                        $alert = "success";
                    endif;
                
            }

        } else {
            $message = "Shop assistants fields cannot be empty!";
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
                
                    $shops = mysqli_query($link, "CAll getShops()");
                ?>

                <p class="card-description">Add new shop assistant</p>
                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <?php $random = rand(); $_SESSION['random'] = $random; ?>
                <input type="hidden" value="<?= $random; ?>" name="randcheck" />

                <div class="form-group">
                    <label for="as_name">Assistant fullname</label>
                    <input type="text" class="form-control" name="as_name" id="as_name" placeholder="Shop assistant">
                </div>

                <div class="form-group">
                    <label for="as_username">Assistant username</label>
                    <input type="text" class="form-control" name="as_username" id="as_username">
                </div>

                <div class="form-group">
                    <label for="as_password">Assistant password <a href="#" id="generatePass" style="margin-left: 10px;">(Generate random password)</a></label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="as_password" id="as_password" aria-label="Assistant password">
                        <div class="input-group-append">
                            <span style="cursor: pointer;" class="input-group-text bg-gradient-primary text-white reveal">
                                <i class="mdi mdi-eye float-right"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                <label for="as_shop">Assign to shop</label>
                <select name="as_shop" class="form-control form-control-sm" id="as_shop">
                    <?php while($s = mysqli_fetch_object($shops)) : ?>
                        <option value="<?= $s->sho_id ?>"><?= $s->sho_name ?></option>
                    <?php endwhile; ?>
                </select>
                </div>                

                <input type="submit" name="add_newShopAssistant" class="btn btn-gradient-primary mr-2" value="Add new shop assistant" />
                </form>
                <?php
                endif;
                //End new service

    /*****************************************
    ************* EDITING SECTION ************
    ** Dispaly section for editing services **
    ******************************************/

    if(isset($_GET['action']) && ($_GET['action'] == 'edit') ) :
        

        //If variable assistant ID not set the following code will be executed
        if(!isset($_GET['assistantId'])) 
        {
            $i = 1;
            $shop_assistants = mysqli_query($link, "CALL ShopAssistant('selectall', '', '', '', '', '')");
?>
    <p class="card-description">Edit/modify shop assistants</p>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th> # </th>
                <th>Assistant name</th>
                <th>Username</th>
                <th>Assigned to shop</th>
                <th><span style="float: right;">Action</span></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($a = mysqli_fetch_object($shop_assistants)) : ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= ucfirst($a->sas_fullname) ?></td>
                <td><i class="mdi mdi-account icon-sm" style="color: #9a55ff;"></i> <span style="color: #9a55ff; "><?= $a->sas_username ?></span></td>
                <td><strong><?= getShop($a->sho_id) ?></strong></td>
                <td>
                    <a href="dashboard.php?page=shopassistants&action=edit&assistantId=<?= $a->sas_id ?>" style="float: right;" class="btn btn-outline-secondary btn-icon-text"> 
                        Edit <i class="mdi mdi-file-check btn-icon-append"></i>
                    </a>
                </td>
            </tr>
            <?php $i++; endwhile; mysqli_free_result($shop_assistants); ?>
        </tbody>
    </table>
<?php

        } else {

            //Edit-modify service, if serviceId is set
            mysqli_next_result($link);

            $currentShopAssistant = mysqli_query($link, "CALL ShopAssistant('selectCurrent', '', '', '', '', '".$_GET['assistantId']."')");
            $sa = mysqli_fetch_object($currentShopAssistant);

            // If username field is modified using DOM and "disabled" attribute is removed
            // we store username in a session beforehand
            $_SESSION['username'] = $sa->sas_username;
            $_SESSION['password'] = $sa->sas_password;

            mysqli_next_result($link);
            $shops = mysqli_query($link, "CAll getShops()");
    ?>
    
    <p class="card-description">Edit shop assistant</p>
        <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">

        <?php $random = rand(); $_SESSION['random'] = $random; ?>
        <input type="hidden" value="<?= $random; ?>" name="randcheck" />

            <div class="form-group">
                <label for="edit_as_name">Assistant fullname</label>
                <input type="text" class="form-control" name="edit_as_name" id="edit_as_name" value="<?= $sa->sas_fullname ?>">
            </div>

            <div class="form-group">
                <label for="edit_as_username">Assistant username</label>
                <input type="text" class="form-control" name="edit_as_username" id="edit_as_username" value="<?= $sa->sas_username ?>" disabled="disabled">
            </div>

            <div class="form-group">
                <label for="edit_as_password">Assistant password <a href="#" id="generatePass" style="margin-left: 10px;">(Generate random password)</a></label>
                    <p style="font-size: 12px;"><strong>(If left empty, password will not be changed)</strong></p>
                    <div class="input-group">
                        <input type="password" class="form-control" name="edit_as_password" id="edit_as_password" aria-label="Assistant password">
                        <div class="input-group-append">
                            <span style="cursor: pointer;" class="input-group-text bg-gradient-primary text-white reveal">
                                <i class="mdi mdi-eye float-right"></i>
                            </span>
                        </div>
                    </div>
            </div>

            <div class="form-group">
            <label for="edit_as_shop">Assigned to shop</label>
                <select name="edit_as_shop" class="form-control form-control-sm" id="edit_as_shop">
                <?php 
                    while($c = mysqli_fetch_object($shops)) { 
                        if($sa->sho_id == $c->sho_id) {
                            echo '<option value="'.$c->sho_id.'" selected="selected">'.$c->sho_name.'</option>';
                        } else {
                            echo '<option value="'.$c->sho_id.'">'.$c->sho_name.'</option>';
                        }
                    } 
                ?>
                </select>
            </div>                

        <input type="submit" name="btn_editShopAssistants" class="btn btn-gradient-primary mr-2" value="Edit shop assistant" />
        <a style="float: right;" href="dashboard.php?page=shopassistants&action=edit" class="btn btn-gradient-primary mr-2 btn-icon-text deleteService"> 
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