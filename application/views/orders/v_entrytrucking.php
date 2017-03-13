<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');

// currency
$this->load->helper('currency_helper');

$attr_form = array(
    'name' => 'work_order'
 );

$date = date('Y-m-d');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3>Entry Trucking Container Data <a href="<?php echo site_url('Order/view_trucking'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo form_open(); ?>
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($sppb_error) && $sppb_error == "error") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo "Failed entry trucking data. Please entry SPPB!"; ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('failed')) { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('failed'); ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($trucking_error) && $trucking_error == "error") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo "Failed entry trucking data. Please don't inserted empty or more than one container!"; ?>
                            </div>
                        <?php } ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Header Trucking</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-5 col-md-offset-1">
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
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                        <div class="col-md-5 col-md-offset-1">
                                            <table>
                                                <tr>
                                                    <td><strong>Delivery Number</strong></td>
                                                    <td style="padding: 0px 10px">:</td>
                                                    <td>
                                                        <input type="text" name="do_number" class="form-control" readonly="true" value="<?php echo set_value('do_number', $do_number) ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Date</strong></td>
                                                    <td style="padding: 13px 10px">:</td>
                                                    <td>
                                                        <input type="text" name="document_date" id="document_date" class="form-control" value="<?php echo set_value('document_date', $do_date); ?>">
                                                    </td>
                                                </tr>
                                            </table>
                                            <?php
                                                if ($check_container > 0) {
                                                    echo anchor('Order/print_do/'.$work_order_number.'/'.$this->uri->segment(4), '<span class="glyphicon glyphicon-print"></span> Print', array('class' => 'btn btn-primary'));
                                                } else {
                                                    echo '<button type="button" class="btn btn-primary" disabled><span class="glyphicon glyphicon-print"></span> Print</button>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Trucking Data</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="tableTrucking">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Container Number</th>
                                                <th class="text-center">Container Detail</th>
                                                <th class="text-center">Commodity</th>
                                                <th class="text-center">From / To</th>
                                                <th class="text-center">Trucking by</th>
                                                <th class="text-center">Truck Number</th>
                                                <th class="text-center">Chassis Number</th>
                                                <th class="text-center">Driver</th>
                                                <th class="text-center">Est. Location Date</th>
                                                <th class="text-center">Detail To</th>
                                                <th class="text-center">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $loop_code = 0;
                                                foreach ($data_container as $key => $value) {
                                                    $tampung = $value->COMMODITY_DESCRIPTION;
                                                    $string = strip_tags($tampung);
                                                    $hasil = substr($string, 0, 7)."...";
                                                    ?>
                                                        <tr id="<?php echo $loop_code; ?>">
                                                            <?php /* <td>
                                                                <button type="button" class="btn btn-danger btn_remove" id="<?php echo $loop_code; ?>" ><span class="glyphicon glyphicon-remove"></span></button>
                                                            </td> */ ?>
                                                            <td><?php echo $value->CONTAINER_NUMBER; ?></td>
                                                            <td><?php echo $value->CONTAINER_SIZE_ID . " - " . $value->CONTAINER_TYPE_ID . " - " . $value->CONTAINER_CATEGORY_ID; ?></td>
                                                            <td><?php echo $hasil; ?></td>
                                                            <td><?php echo $value->FROM_NAME . " - " . $value->TO_NAME; ?></td>
                                                            <td>
                                                                <select class="form-control coba" data-id="<?php echo $loop_code; ?>" id="#select<?php echo $loop_code; ?>" name="container[<?php echo $loop_code; ?>][own_truck]">
                                                                    <option></option>
                                                                    <?php
                                                                        foreach ($data_own as $key1 => $value1) {
                                                                            ?>
                                                                                <option <?php if ($value->TRUCK_OWNER_ID == $value1->TRUCK_OWNER_ID ) echo 'selected' ; ?> value="<?php echo $value1->TRUCK_OWNER_ID ?>"><?php echo $value1->TRUCK_OWNER_NAME ?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="container[<?php echo $loop_code; ?>][truck_number]" class="form-control" onClick="search_truck(<?php echo $loop_code; ?>)" id="truck<?php echo $loop_code; ?>" value="<?php echo $value->TRUCK_ID_NUMBER; ?>">
                                                                <input type="hidden" name="container[<?php echo $loop_code; ?>][container_number]" class="form-control" value="<?php echo $value->CONTAINER_NUMBER; ?>" value="<?php echo $value->CONTAINER_NUMBER ?>">
                                                                <input type="hidden" name="container[<?php echo $loop_code; ?>][do_number]" class="form-control" value="<?php echo $do_number; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="container[<?php echo $loop_code; ?>][chasis_number]" class="form-control" onClick="search_chasis(<?php echo $loop_code; ?>)" id="chasis<?php echo $loop_code; ?>" value="<?php echo $value->CHASIS_ID_NUMBER; ?>" >
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    /* 
                                                                        <input type="text" id="#driver<?php echo $loop_code; ?>" name="container[<?php echo $loop_code; ?>][driver_name]" id="driver<?php echo $loop_code; ?>" class="form-control" onClick="search_driver(<?php echo $loop_code; ?>)" value="<?php echo $value->DRIVER_NAME; ?>">
                                                                        <input type="hidden" name="container[<?php echo $loop_code; ?>][driver_id]" id="driver_id<?php echo $loop_code; ?>" class="form-control" value="<?php echo $value->DRIVER_ID ?>">
                                                                    */
                                                                ?>
                                                                <select class="form-control js-example-basic-single js-states" name="container[<?php echo $loop_code; ?>][driver_id]">
                                                                    <option value=""></option>
                                                                    <?php 
                                                                        foreach ($data_driver as $key1 => $value1) {
                                                                            ?>
                                                                                <option <?php if ($value->DRIVER_ID == $value1->DRIVER_ID ) echo 'selected' ; ?> value="<?php echo $value1->DRIVER_ID; ?>" ><?php echo $value1->DRIVER_NAME; ?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="container[<?php echo $loop_code; ?>][est_date]" id="est_date" class="form-control est_date" value="<?php echo $value->EST_DATE; ?>">
                                                            </td>
                                                            <td>
                                                                <select class="form-control js-example-basic-single js-states" name="container[<?php echo $loop_code; ?>][detail_to]">
                                                                    <option value=""></option>
                                                                    <?php 
                                                                        foreach ($data_address as $value1) {
                                                                            ?>
                                                                                <option <?php if ($value->FINAL_LOCATION_DETAIL == $value1 ) echo 'selected' ; ?> value="<?php echo $value1; ?>" ><?php echo $value1; ?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" name="container[<?php echo $loop_code; ?>][remarks]"><?php echo $value->REMARKS; ?></textarea>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    $loop_code++;
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
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
    function search_commodity(id)
    {
        // autocomplete hoarding
        $('#commo'+id+'').autocomplete({
          source: "<?php echo site_url('Order/search_commodity'); ?>",
          minLength:1,
          select:function(event, data){
            $('#commo_id'+id+'').val(data.item.commodity_id);
          }
        });
    }

    function search_truck(id)
    {
        // autocomplete hoarding
        $('#truck'+id+'').autocomplete({
          source: "<?php echo site_url('Order/search_truck'); ?>",
          minLength:1
        });
    }

    function search_chasis(id)
    {
        // autocomplete hoarding
        $('#chasis'+id+'').autocomplete({
          source: "<?php echo site_url('Order/search_chasis'); ?>",
          minLength:1
        });
    }

    function search_driver(id)
    {
        // autocomplete hoarding
        $('#driver'+id+'').autocomplete({
          source: "<?php echo site_url('Order/search_driver'); ?>",
          minLength:1,
          select:function(event, data){
            $('#driver_id'+id+'').val(data.item.driver_id);
          }
        });
    }

    $(document).on('click', '.btn_remove', function(){
      var button_id = $(this).attr("id");
      $('#'+button_id+'').remove();
    });

    $(document).ready(function() {
        $(".js-example-basic-single").select2();
        var d = new Date();
        var a = d.setDate(d.getDate() - 5);
        $('#date').datetimepicker({
            timepicker:false,
            format: "Y-m-d",
            minDate: a
        });

        $('#document_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

        $('.est_date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

        $('#table-trucking').DataTable({
                responsive: true
        });

        // autocomplete hoarding
        $("#hoarding_name").autocomplete({
          source: "<?php echo site_url('Order/search_hoarding'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=hoarding_id]').val(data.item.hoarding_id);
          }
        });

        // // autocomplete hoarding
        // $('#').autocomplete({
        //   source: "<?php echo site_url('Order/search_nik'); ?>",
        //   minLength:1,
        //   select:function(event, data){
        //     $('#nik_isi'+id+'').val(data.item.pic_id);
        //   }
        // });

        // autocomplete importir
        $("#importir_name").autocomplete({
          source: "<?php echo site_url('Order/search_company'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=importir_id]').val(data.item.company_id);
          }
        });

        if($(".coba").attr("selectedIndex") != "TR001") {
            var but_id = $(this).attr("data-id");
            $('#driver'+but_id+'').prop('disabled', true);
        }
    });

</script>


<?php
    $this->load->view('layouts/footer.php');
?>
