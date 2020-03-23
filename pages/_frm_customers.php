<?php

    //$uri = $_SERVER['REQUEST_URI'];

    if(isset($_POST['btn_addCustomer']) && ($_POST['randcheck'] == $_SESSION['random']) ) :

        if(!empty($_POST['customer_name']) && !empty($_POST['customer_address'])
          && !empty($_POST['customer_phone']))
        {
            $fullname = explode(" ", strip_tags($_POST['customer_name']));

            $fname = ucfirst($fullname[0]);

            if(count($fullname) > 1) :
                $lname = ucfirst($fullname[1]);
            else :
                $lname = '';
            endif;

            $address = strip_tags($_POST['customer_address']);
            $phone = strip_tags($_POST['customer_phone']);

            $result = mysqli_query($link, 
                "CALL Customers(
                    'insert', 
                    '$fname', 
                    '$lname',
                    '$address',
                    '$phone',
                    ''
                    )");

            if($result) :
                $message = "Customer successfully added!";
                $alert = "success";
            endif;


        } else {
            $message = "All fields are required!";
            $alert = "warning";
        }

        //mysqli_free_result($result);

    endif;

    if(isset($_POST['btn_editCustomer']) && ($_POST['randcheck'] == $_SESSION['random']) ) :
        if(!empty($_POST['edit_customer_name']) && !empty($_POST['edit_customer_address'])
            && !empty($_POST['edit_customer_phone']))
        {
            $edited_fullname = explode(" ", strip_tags($_POST['edit_customer_name']));

            $edited_fname = ucfirst($edited_fullname[0]);

            if(count($edited_fullname) > 1) :
                $edited_lname = ucfirst($edited_fullname[1]);
            else :
                $edited_lname = '';
            endif;

            $edited_address = strip_tags($_POST['edit_customer_address']);
            $edited_phone = strip_tags($_POST['edit_customer_phone']);

            $update_result = mysqli_query($link, 
                "CALL Customers(
                    'update', 
                    '$edited_fname', 
                    '$edited_lname',
                    '$edited_address',
                    '$edited_phone',
                    '".$_GET['customerId']."'
                    )") or die(mysqli_error($link));
        
            if($update_result) :
                $message = "Client information updated successfully!";
                $alert = "success";
            endif;

        } else {
            $message = "Service fields cannot be empty!";
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
                
                ?>

                <p class="card-description">Add new customer</p>
                <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <?php $random = rand(); $_SESSION['random'] = $random; ?>
                <input type="hidden" value="<?= $random; ?>" name="randcheck" />

                    <div class="form-group">
                        <label for="customer_name">Customer name</label>
                        <input type="text" class="form-control" name="customer_name" aria-label="Name" placeholder="Customer name">
                    </div>

                    <div class="form-group">
                        <label for="customer_address">Customer address & phone number</label>
                        <div class="customer_group">
                        <input type="text" class="form-control" name="customer_address" aria-label="Address" placeholder="Customer address" style="width:50%; float: left;">
                        <input type="text" class="form-control" name="customer_phone" aria-label="Phone number" placeholder="Phone number: xxx-xxx-xxx" style="width:50%">
                        </div>
                    </div>                    

                    <input type="submit" name="btn_addCustomer" class="btn btn-gradient-primary mr-2" value="Add new customer" />
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
        $customers = mysqli_query($link, "CALL Customers('selectall', '', '', '', '', '')");

        if(!isset($_GET['customerId'])) 
        {
?>
    <p class="card-description">Edit/modify customers</p>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th> # </th>
                <th>Customer name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($customer = mysqli_fetch_object($customers)) : ?>
            <tr>
                <td><?= $i ?></td>
                <td><strong><?= $customer->cli_fname . " " . $customer->cli_lname  ?></strong></td>
                <td><?= $customer->cli_address ?></td>
                <td><?= $customer->cli_phone ?></td>
                <td>
                    <a href="dashboard.php?page=customers&action=edit&customerId=<?= $customer->cli_id ?>" class="btn btn-outline-secondary btn-icon-text"> 
                        Edit <i class="mdi mdi-file-check btn-icon-append"></i>
                    </a>
                </td>
            </tr>
            <?php $i++; endwhile; mysqli_free_result($customers); ?>
        </tbody>
    </table>
<?php

        } else {

            //Edit-modify service, if serviceId is set
            mysqli_next_result($link);

            $currentCustomer = mysqli_query($link, "CALL Customers('selectCurrent', '', '', '', '', '".$_GET['customerId']."')");
            $customer = mysqli_fetch_object($currentCustomer);
            

    ?>
    
    <p class="card-description">Edit customer</p>
        <form class="forms-sample" method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
        <?php $random = rand(); $_SESSION['random'] = $random; ?>
                <input type="hidden" value="<?= $random; ?>" name="randcheck" />

                    <div class="form-group">
                        <label for="edit_customer_name">Customer name</label>
                        <input type="text" class="form-control" name="edit_customer_name" aria-label="Name" 
                            value="<?= $customer->cli_fname." ".$customer->cli_lname ?>">
                    </div>

                    <div class="form-group">
                        <label for="edit_customer_address">Customer address & phone number</label>
                        <div class="customer_group">
                        <input type="text" class="form-control" name="edit_customer_address" aria-label="Address" 
                            value="<?= $customer->cli_address ?>" style="width:50%; float: left;">
                        <input type="text" class="form-control" name="edit_customer_phone" aria-label="Phone number" 
                            value="<?= $customer->cli_phone ?>" style="width:50%">
                        </div>
                    </div>

            <input type="submit" name="btn_editCustomer" class="btn btn-gradient-primary mr-2" value="Edit customer" />
            <a style="float: right;" href="dashboard.php?page=customers&action=edit" class="btn btn-gradient-primary mr-2 btn-icon-text deleteService"> 
                Back <i class="mdi mdi-arrow-left-bold-circle-outline btn-icon-append"></i>
            </a>
        </form>    

    <?php
        }

    endif;
    /*********************************************
    ************ END EDITING SECTION *************
    **********************************************/  
    mysqli_close($link);
?>

            </div>
        </div>
    </div>
</div>