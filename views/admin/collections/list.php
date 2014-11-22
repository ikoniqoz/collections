<?php if(isset($collection)):?>
<div class="one_half" id="">
<?php else:?>
<div class="one_full" id="">
<?php endif;?>


	<section class="title">
	    <span>
			<h4><?php echo lang('shop_collections:title');?></h4>
		</span>	
	</section>


	<?php echo form_open('admin/shop_collections/collections/delete'); ?>
	<section class="item">
		<div class="content">
		<?php if (empty($collections)): ?>
			<div class="no_data">
				<br />
				<p>
					Collections
				</p>

				<?php echo lang('shop_collections:no_collections'); ?>
				<br /><br /><br />
				<p>
				<small>Enjoy using NitroCart! Send us your feedback here <a href='mailto:feedback@nitrocart.net'>feedback@nitrocart.net</a></small>
				</p>
			</div>
	</section>
	<?php else: ?>
		<table class='sortable' id='sortable_list'>
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('shop_collections:id');?></th>
					<th><?php echo lang('shop_collections:collection');?></th>
					<th></th>
					<th style="width: 120px"></th>
				</tr>
			</thead>
			<tbody>

				<?php
					$data = new StdClass();
					foreach ($collections AS $_collection): ?>
					<?php $_items = array();?>
					<tr>
						<td><a class='handle'></a> <input type="checkbox" name="action_to[]" value="<?php echo $_collection->id; ?>"  /></td>
						<td><?php echo $_collection->id; ?></td>
						<td><?php echo $_collection->name; ?></td>
						<td></td>
						<td>
							<?php $can_show=FALSE;?>
							<?php if(isset($collection)): ?>
								<?php if($collection->id != $_collection->id): ?>
									<?php $can_show=TRUE;?>
								<?php endif;?>
							<?php else:?>
								<?php $can_show=TRUE;?>
							<?php endif;?>

							<?php  if($can_show): ?>
									<span style="float:right;">
										<?php $_items[] = dropdownMenuStandard("admin/shop_collections/collections/edit/{$_collection->id}", false, 'Edit', FALSE, 'edit');?>
										<?php $_items[] = dropdownMenuStandard("admin/shop_collections/collections/products/{$_collection->id}", true, 'Products', FALSE, 'eye-open');?>
										<?php $_items[] = dropdownMenuStandard("admin/shop_collections/collections/delete/{$_collection->id}", false, 'Delete', true, 'minus');?>
										<?php echo dropdownMenuList($_items, $actionsText='Actions');?>
									</span>
							<?php endif;?>							
						</td>
					</tr>
				<?php endforeach; ?>

			</tbody>
			<tfoot>
				<tr>
					<td colspan="6"><div style="float:right;"></div></td>
				</tr>
			</tfoot>
		</table>

		<div class="buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
		</div>
		</section>
	<?php endif; ?>

	<?php echo form_close(); ?>

	<?php if (isset($pagination)): ?>
		<?php echo $pagination; ?>
	<?php endif; ?>
</div>

<?php if(isset($collection)):?>

	<div class="one_half last" id="">


		<section class="title" style="height:40px;">
			<h4>Products in <em>`{{collection.name}}`</em></h4>
		</section>
		<section class="item">
			<div class="content">

									<table>

										<thead>
											<tr>
												<th class="collapse"></th>
												<th class="collapse"><?php echo lang('shop_collections:id');?></th>
												<th class="collapse"><?php echo lang('shop_collections:name');?></th>
												<th class="collapse"><?php echo lang('shop_collections:featured');?></th>
												<th class="collapse"><?php echo lang('shop_collections:visibility');?></th>
												<th></th>
											</tr>
										</thead>
										<tbody id="">
											{{products}}
												<tr>
													<td></td>
													<td>{{id}}</td>
													<td>{{name}}</td>
													<td>{{featured}}</td>
													<td>{{public}}</td>
													<td>
														<span style="float:right;">
															<?php echo single_button("admin/shop/product/edit/{{id}}", false, 'View', FALSE, $icon='eye-open');?>
														</span>
													</td>
												</tr>
											{{/products}}
										</tbody>
								</table>
			</div>
		</section>
	</div>
<?php endif;?>