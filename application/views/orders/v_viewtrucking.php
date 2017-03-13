<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-12">
            <h3>Container Data</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <?php if($this->session->flashdata('stnk_error')) { ?>
                <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('stnk_error'); ?>
                </div>
            <?php } ?>


            <?php if($this->session->flashdata('truck_error')) { ?>
                <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('truck_error'); ?>
                </div>
            <?php } ?>


            <?php if($this->session->flashdata('driver_error')) { ?>
                <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('driver_error'); ?>
                </div>
            <?php } ?>


            <?php if($this->session->flashdata('driver_available_error')) { ?>
                <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('driver_available_error'); ?>
                </div>
            <?php } ?>


            <?php if($this->session->flashdata('chasis_error')) { ?>
                <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('chasis_error'); ?>
                </div>
            <?php } ?>


            <?php if($this->session->flashdata('chasis_available_error')) { ?>
                <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('chasis_available_error'); ?>
                </div>
            <?php } ?>

            <?php if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
            
            <table class="table table-striped table-bordered display" id="table-customer" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">WO Number</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">Container Number</th>
                        <th class="text-center">Container Detail</th>
                        <th class="text-center">Commodity</th>
                        <th class="text-center">Trade</th>
                        <th class="text-center">From/To</th>
                        <th class="text-center">DO Date</th>
                        <th class="text-center">Truck Number</th>
                        <th class="text-center">Trucking by</th>
                        <th class="text-center">Chasis Number</th>
                        <th class="text-center">Est. Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach ($data_trucking as $key => $value) {
                            $tampung = $value->COMMODITY_DESCRIPTION;
                            $string = strip_tags($tampung);
                            $hasil = substr($string, 0, 7)."...";
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $no; ?>
                                    </td>
                                    <td>
                                        <?php echo $value->WORK_ORDER_NUMBER; ?>
                                    </td>
                                    <td><?php echo $value->CUSTOMER_NAME; ?></td>
                                    <td><?php echo $value->CONTAINER_NUMBER; ?></td>
                                    <td><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?></td>
                                    <td><?php echo $hasil; ?></td>
                                    <td>
                                        <?php
                                            echo $value->TRADE_NAME;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            echo $value->FROM_NAME . " - " . $value->TO_NAME;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            if ($value->DOCUMENT_DATE == "") {
                                                echo anchor('Order/entry_trucking_detail/'.$value->WORK_ORDER_NUMBER.'/'.$value->CONTAINER_NUMBER, 'Entry', array('class' => 'text-center'));
                                            } else {
                                                echo anchor('Order/entry_trucking_detail/'.$value->WORK_ORDER_NUMBER.'/'.$value->CONTAINER_NUMBER, $value->DELIVERY_ORDER_NUMBER . " - " . $value->DOCUMENT_DATE, array('class' => 'text-center'));
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            echo $value->TRUCK_ID_NUMBER;
                                        ?>
                                    </td>
                                    <td><?php echo $value->TRUCK_OWNER_NAME; ?></td>
                                    <td><?php echo $value->CHASIS_ID_NUMBER; ?></td>
                                    <td><?php echo $value->EST_DATE; ?></td>
                                </tr>
                            <?php
                            $no++;
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
    $(document).ready(function() {
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            minDate: a
        });

        $('#table-customer').DataTable({
                responsive: true,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                "order": [[ 1, "desc" ]]
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
