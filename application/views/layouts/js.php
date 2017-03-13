	<!-- scripts -->
	<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.datetimepicker.full.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.number.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
	<!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> -->
	<script src="<?php echo base_url(); ?>assets/js/tinymce/tinymce.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/select/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/autoNumeric.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-show-password.js"></script>

	<!-- <script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script> -->


	<!-- DataTables JavaScript -->
    <script src="<?php echo base_url(); ?>assets/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/datatables-responsive/js/dataTables.responsive.js"></script>

    <!-- dropdown multilevel -->
    <script>
		$(function(){
			$(".dropdown-menu > li > a.trigger").on("click",function(e){
				var current=$(this).next();
				var grandparent=$(this).parent().parent();
				if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
					$(this).toggleClass('right-caret left-caret');
				grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
				grandparent.find(".sub-menu:visible").not(current).hide();
				current.toggle();
				e.stopPropagation();
			});
			$(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
				var root=$(this).closest('.dropdown');
				root.find('.left-caret').toggleClass('right-caret left-caret');
				root.find('.sub-menu:visible').hide();
			});
		});
	</script>