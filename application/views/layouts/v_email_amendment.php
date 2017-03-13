<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Agreement</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin:0; padding:0;">
	<table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
		<tr>
			<td>Based on amendment agreement, you can approve or reject the following agreement :</td>
		</tr>
		<tr>
			<td style="text-align:justify;">
				<table align="center" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
					<tr>
						<td>Agreement Number</td>
						<td> : </td>
						<td><?php echo $amendment_number; ?></td>
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
			<td>Please click <a href="<?php echo base_url() . 'Approval/detail_approval_agreement/' . $amendment_number . '/D1001'; ?>">Approve</a> to change the agreement's status</td>
		</tr>
	</table>
</body>
</html>