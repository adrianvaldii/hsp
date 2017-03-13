<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header');
$this->load->helper('currency_helper');
?>

<!-- content -->
<div class="container-fluid font_mini">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>Search PIC and Work Order Operational Cost <a href="<?php echo site_url('Order/view_operational_cost'); ?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search PIC</div>
                        <div class="panel-body">
                            <?php echo form_open('Order/entry_opr_cost'); ?>
                                <?php if(validation_errors()) { ?>
                                    <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>

                                <?php if($this->session->flashdata('success')) { ?>
                                    <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="form-group">
                                    <label>PIC Name</label>
                                    <input type="text" name="pic_name" class="form-control" value="<?php echo $pic_name; ?>" readonly="true">
                                    <input type="hidden" name="pic_id" class="form-control" value="<?php echo $pic_id; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Work Order Number</label>
                                    <select class="form-control js-example-basic-single js-states" name="work_order_number">
                                        <option <?php echo set_select('work_order_number', '', TRUE); ?> ></option>
                                        <?php 
                                            foreach ($data_wo as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value->WORK_ORDER_NUMBER; ?>" <?php echo set_select('work_order_number', $value->WORK_ORDER_NUMBER, FALSE); ?> ><?php echo $value->WORK_ORDER_NUMBER . "  -  " . $value->WORK_ORDER_DATE . "  -  " . $value->COMPANY_NAME; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Search</button>
                            </form>
                        </div>
                    </div>
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
    $(document).ready(function() {
        $(".js-example-basic-single").select2({
            theme: "bootstrap"
        });
        // autocomplete hoarding
        $("#pic_name").autocomplete({
          source: "<?php echo site_url('Order/search_nik'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=pic_id]').val(data.item.pic_id);
          }
        });
    });
</script>


<?php
    $this->load->view('layouts/footer.php');
?>
