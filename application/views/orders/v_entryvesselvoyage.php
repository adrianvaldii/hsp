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
            <h3>Entry Vessel Voyage</h3>
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
							<label>Vessel</label>
							<input type="text" name="vessel_name" value="<?php echo set_value('vessel_name'); ?>" class="form-control" id="vessel"> <?php echo anchor_popup('Order/entry_vessel/','Entry Vessel', $attributes); ?>
							<input type="hidden" name="vessel_id" class="form-control" value="<?php echo set_value('vessel_id'); ?>">
						</div>
						<div class="form-group">
							<label>Voyage Number</label>
							<input type="text" name="voyage_number" class="form-control" value="<?php echo set_value('voyage_number'); ?>">
						</div>
						<div class="form-group">
							<label>Trade</label>
							<select class="form-control" name="trade_id">
								<option></option>
								<?php
									foreach ($data_trade as $key => $value) {
										?>
											<option value="<?php echo $value->GENERAL_ID ?>"> <?php echo $value->GENERAL_DESCRIPTION; ?> </option>
										<?php
									}
								?>
							</select>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>ETA</label>
									<input type="text" name="eta" class="form-control date" value="<?php echo set_value('eta'); ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>ETD</label>
									<input type="text" name="etd" class="form-control date" value="<?php echo set_value('etd'); ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>POL</label>
									<input type="text" name="pol_name" id="pol_name" class="form-control" value="<?php echo set_value('pol_name'); ?>"> 
									<?php echo anchor_popup('Order/entry_port/','Entry Port', $attributes); ?>
									<input type="hidden" value="<?php echo set_value('pol_id'); ?>" name="pol_id" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>POD</label>
									<input type="text" name="pod_name" value="<?php echo set_value('pod_name'); ?>" id="pod_name" class="form-control">
									<input type="hidden" name="pod_id" class="form-control" value="<?php echo set_value('pod_id'); ?>">
								</div>
							</div>
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
