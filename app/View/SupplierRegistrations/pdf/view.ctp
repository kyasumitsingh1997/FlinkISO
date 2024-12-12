<h2><?php  echo __('Supplier Registration'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['title']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Number'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Type Of Company'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['type_of_company']); ?>
			&nbsp;
		</td></tr>
            <tr bgcolor="#FFFFFF"><td class="head-strong" colspan="2"><h3>Office Details</h3></td></tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Contact Person Office'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['contact_person_office']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Designition In Office'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['designition_in_office']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Office Address'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['office_address']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Office Telephone'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['office_telephone']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Office Fax'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['office_fax']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Office Weekly Off'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['office_weekly_off']); ?>
			&nbsp;
		</td></tr>
            <tr bgcolor="#FFFFFF"><td class="head-strong" colspan="2"><h3>Factory / Workshop details</h3></td></tr>

		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Contact Person Work'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['contact_person_work']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Designation In Work'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['designation_in_work']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Work Address'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['work_address']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Work Telephone'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['work_telephone']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Work Fax'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['work_fax']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Work Weekly Off'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['work_weekly_off']); ?>
			&nbsp;
		</td></tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong" colspan="2"></td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('CST Registration Number'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['cst_registration_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('ST Registration Number'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['st_registration_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Incometax Number'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['incometax_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('SSI Registration Number'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['ssi_registration_number']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Range Of Products'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['range_of_products']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Services Offered'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['services_offered']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Existing Facilities'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['existing_facilities']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prominent Customers'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['prominent_customers']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Quality Assurence'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['quality_assurence']); ?>
			&nbsp;
		</td></tr>
<!--
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php // echo __('Name'); ?></td>
		<td>
			<?php // echo h($supplierRegistration['SupplierRegistration']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php //echo __('Designation'); ?></td>
		<td>
			<?php //echo h($supplierRegistration['SupplierRegistration']['designation']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php //echo __('Date'); ?></td>
		<td>
			<?php //echo h($supplierRegistration['SupplierRegistration']['date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php // echo __('Facilites'); ?></td>
		<td>
			<?php // echo h($supplierRegistration['SupplierRegistration']['facilites']); ?>
			&nbsp;
		</td></tr>
-->
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Facility Comments'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['facility_comments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Supplier Representative'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['supplier_representative']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Is supplier selected as Acceptable Supplier?'); ?></td>
		<td>
			<?php echo ($supplierRegistration['SupplierRegistration']['supplier_selected'])? "Yes" : "No"; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Supplier Category'); ?></td>
		<td>
			<?php echo $supplierRegistration['SupplierCategory']['name']; ?>
			&nbsp;
		</td></tr>
<!--
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Details'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['details']); ?>
			&nbsp;
		</td></tr>
-->
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Trial Order'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['trial_order']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Order Date'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['order_date']); ?>
			&nbsp;
		</td></tr>
<!--
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php //echo __('Name2'); ?></td>
		<td>
			<?php //echo h($supplierRegistration['SupplierRegistration']['name2']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php //echo __('Designation2'); ?></td>
		<td>
			<?php //echo h($supplierRegistration['SupplierRegistration']['designation2']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php //echo __('Date2'); ?></td>
		<td>
			<?php //echo h($supplierRegistration['SupplierRegistration']['date2']); ?>
			&nbsp;
		</td></tr>
-->
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Is supplier ISO certified?'); ?></td>
		<td>
			<?php echo $supplierRegistration['SupplierRegistration']['iso_certified'] ? 'Yes' : 'No'; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $supplierRegistration['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $supplierRegistration['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($supplierRegistration['SupplierRegistration']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($supplierRegistration['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $supplierRegistration['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />
