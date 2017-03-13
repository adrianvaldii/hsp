<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Quotation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin:0; padding:0;">
	<table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
		<tr>
			<td>Based on revesion quotation, you can approve or reject the following quotation :</td>
		</tr>
		<tr>
			<td style="text-align:justify;">
				<table align="center" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
					<tr>
						<td>Quotation Number</td>
						<td> : </td>
						<td><?php echo $quotation; ?></td>
					</tr>
					<tr>
						<td>Customer</td>
						<td> : </td>
						<td><?php echo $customer; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>Please click <a href="<?php echo base_url(); ?>Approval/index">Approve</a> to change the quotation's status</td>
		</tr>
	</table>
</body>
</html>