			<li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Work Order <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">Order Entry</a></li>
	            <li><a href="#">Cash Request</a></li>
	            <li><a href="#">Shipping Line Status</a></li>
	            <li><a href="#">Delivery Order Status</a></li>
	            <li><a href="#">Custom Clearance Status</a></li>
	            <li><a href="#">Cost Detail</a></li>
	            <li><a href="#">Delivery Order</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="#">Trace and Status</a></li>
	          </ul>
	        </li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Quotation <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">View All Quotation</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="<?php echo site_url('Quotation/add_quotation'); ?>">Entry Quotation</a></li>
	          </ul>
	        </li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Approval <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	            <li><a href="#">Selling Service</a></li>
	            <li role="separator" class="divider"></li>
	            <li><a href="<?php echo site_url('Quotation/add_quotation'); ?>">Entry Quotation</a></li>
	          </ul>
	        </li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Master <span class="caret"></span></a>
	          <ul class="dropdown-menu">
	          <li>
	            	<a class="trigger right-caret">Services</a>
	            	<ul class="dropdown-menu sub-menu">
						<li><a href="<?php echo site_url('Service/index'); ?>">View All Service</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Service/index_edit'); ?>">View Edit All Service</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Service/new_service'); ?>">Add Service</a></li>
					</ul>
	            </li>
	            <li role="separator" class="divider"></li>
	            <li>
	            	<a class="trigger right-caret">Company Service</a>
	            	<ul class="dropdown-menu sub-menu">
						<li><a href="<?php echo site_url('Service/company_service'); ?>">View All Company Service</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Service/view_company_service'); ?>">Add Company Service</a></li>
					</ul>
	            </li>
	            <li role="separator" class="divider"></li>
	            <li>
	            	<a class="trigger right-caret">Selling Rate</a>
	            	<ul class="dropdown-menu sub-menu">
						<li><a href="<?php echo site_url('Cost/index'); ?>">View All Selling Rate</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Cost/add_container') ?>">Add Container Selling</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Cost/add_container_custom') ?>">Add Customs Selling</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Cost/add_location_selling') ?>">Add Location Selling</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Cost/add_weight_selling') ?>">Add Weight Selling</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Cost/add_ocean_freight_selling') ?>">Add Ocean Freight Selling</a></li>
					</ul>
	            </li>
	            <li role="separator" class="divider"></li>
	            <li>
	            	<a class="trigger right-caret">General ID</a>
	            	<ul class="dropdown-menu sub-menu">
						<li><a href="<?php echo site_url('Master/index'); ?>">View Master General ID</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Master/add_generalid') ?>">Add General ID</a></li>
					</ul>
	            </li>
	            <li role="separator" class="divider"></li>
	            <li>
	            	<a class="trigger right-caret">Floor and Marketplace Price</a>
	            	<ul class="dropdown-menu sub-menu">
						<li><a href="<?php echo site_url('Master/floor_market_trucking') ?>">Trailler Trucking</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Master/floor_market_customs') ?>">Customs Clearance</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Master/floor_market_location') ?>">Non Trailler Trucking</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Master/floor_market_weight') ?>">Weight Measurement</a></li>
						<li role="separator" class="divider"></li>
						<li><a href="<?php echo site_url('Master/floor_market_ocean_freight') ?>">Freight</a></li>
					</ul>
	            </li>
	          </ul>
	        </li>