<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// load header
$this->load->view('layouts/header-forms');

$this->load->helper('currency_helper');

$attributes = array(
    'width'     =>  '1000',
    'height'    =>  '620',
    'screenx'   =>  '\'+((parseInt(screen.width) - 950)/2)+\'',
    'screeny'   =>  '\'+((parseInt(screen.height) - 700)/2)+\'',
);

?>

<!-- content -->
<div class="container-fluid font_mini">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
            <h3>Entry Port</h3>
            <hr>
        </div>
	</div>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Form Entry</div>
				<div class="panel-body">
					<?php echo form_open(); ?>
						<?php if(validation_errors()) { ?>
                            <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>

                        <?php if(isset($error_var) && $error_var == "error") { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $error_msg; ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('failed')) { ?>
                            <div class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('failed'); ?>
                            </div>
                        <?php } ?>

                        <?php if($this->session->flashdata('success')) { ?>
                            <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } ?>

						<div class="form-group">
							<label>Port ID</label>
							<input type="text" name="port_id" class="form-control" value="<?php echo set_value('port_id'); ?>" placeholder="e.g. IDTJP">
						</div>
                        <div class="form-group">
                            <label>Port Name</label>
                            <input type="text" name="port_name" class="form-control" value="<?php echo set_value('port_name'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" name="country" class="form-control" value="<?php echo set_value('country'); ?>">
                        </div>
						<button type="submit" class="btn btn-success">Save</button>
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

<script type="text/javascript">
	$(document).ready(function(){
		// autocomplete from location
        $("#vessel").autocomplete({
          source: "<?php echo site_url('Order/search_vessel2'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=vessel_id]').val(data.item.vessel_id);
          }
        });

        $('.date').datetimepicker({
            timepicker:false,
            format: "Y-m-d"
        });

        // autocomplete pol
        $("#pol_name").autocomplete({
          source: "<?php echo site_url('Order/search_port'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=pol_id]').val(data.item.port_id);
          }
        });

        // autocomplete from location
        $("#pod_name").autocomplete({
          source: "<?php echo site_url('Order/search_port'); ?>",
          minLength:1,
          select:function(event, data){
            $('input[name=pod_id]').val(data.item.port_id);
          }
        });
	});
	// convert rupiah
        function toRp(angka){
            var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
            var rev2    = '';
            for(var i = 0; i < rev.length; i++){
                rev2  += rev[i];
                if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
                    rev2 += ',';
                }
            }
            return rev2.split('').reverse().join('');
        }
</script>

<?php
    $this->load->view('layouts/footer.php');
?>
