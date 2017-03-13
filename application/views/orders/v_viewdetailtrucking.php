<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

$date = date('Y-m-d H:i:s');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Detail Trucking <a href="<?php echo site_url('Order/index'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <table>
                        <tr>
                            <td><strong>Work Order Number</strong></td>
                            <td style="padding: 0px 10px">:</td>
                            <td><?php echo $work_order_number; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Customer</strong></td>
                            <td style="padding: 20px 10px">:</td>
                            <td><?php echo $customer_name; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped" id="tableTrucking">
                <thead>
                    <tr>
                        <th class="text-center">Container Number</th>
                        <th class="text-center">DO Number</th>
                        <th class="text-center">DO Date</th>
                        <th class="text-center">Truck Number</th>
                        <th class="text-center">Truck Owner</th>
                        <th class="text-center">Chassis Number</th>
                        <th class="text-center">Driver Name</th>
                        <th class="text-center">Est. Arrived</th>
                        <th class="text-center">Location Detail</th>
                        <th class="text-center">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($data_container as $key => $value) {
                            ?>
                                <tr>
                                    <td><?php echo $value->CONTAINER_NUMBER; ?></td>
                                    <td><?php echo $value->DELIVERY_ORDER_NUMBER; ?></td>
                                    <td><?php echo $value->DOCUMENT_DATE; ?></td>
                                    <td><?php echo $value->TRUCK_ID_NUMBER; ?></td>
                                    <td><?php echo $value->TRUCK_OWNER_NAME; ?></td>
                                    <td><?php echo $value->CHASIS_ID_NUMBER; ?></td>
                                    <td><?php echo $value->DRIVER_NAME; ?></td>
                                    <td><?php echo $value->ESTIMATION_ARRIVED; ?></td>
                                    <td><?php echo $value->FINAL_LOCATION_DETAIL; ?></td>
                                    <td><?php echo $value->REMARKS; ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- end of content -->

<!-- js -->
<?php
    $this->load->view('layouts/js.php');  
?>

<!-- script datatables -->
<script type="text/javascript">
   $(document).ready(function (){
        $('#tableTrucking').DataTable({
                responsive: true
        });
   });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
