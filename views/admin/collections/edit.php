<div class="one_half" id="">

<section class="title">
	<?php if (isset($id) AND $id > 0): ?>
		<h4><?php echo sprintf(lang('shop_collections:edit'), $name); ?></h4>
	<?php else: ?>
		<h4><?php echo lang('shop_collections:new'); ?></h4>
	<?php endif; ?>
</section>

<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>


<?php if (isset($id) AND $id > 0): ?>
	<?php echo form_hidden('id', $id); ?>
	<input type="hidden" name="cid" id="cid" value="<?php echo $id; ?>" >
<?php endif; ?>
<section class="item form_inputs">
	<div class="content">

		<fieldset>
			<ul>
				<li class="">
					<label for="name"><?php echo lang('shop_collections:name');?><span>*</span></label>
					<div class="input">
						<?php echo form_input('name', set_value('name', $name), 'id="name" '); ?>
					</div>
				</li>
			</ul>


		</fieldset>

		<div class="buttons">
				<button class="btn blue" value="save_exit" name="btnAction" type="submit">
					<span><?php echo lang('shop_collections:save_exit');?></span>
				</button>

				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save'))); ?>
				<a href="admin/shop_collections/collections/" class="btn gray">Cancel</a>
		</div>

	</div>
</section>
<?php echo form_close(); ?>

</div>





<div class="one_half last" id="">

<section class="title" style="">

	<h4>Actions</h4>
	<h4 style="float:right"></h4>

</section>
<section class="item form_inputs">
	<div class="content">
		<table class='sortable' id='sortable_list'>
			<tbody>
				<tr>
					<td>View products in this collection</td>
					<td><a class='button orange' href="admin/shop_collections/collections/products/{{id}}">View</a></td>
				</tr>
				<tr>
					<td>Add all products to the <em>`{{name}}`</em> collection</td>
					<td><a class='modal button red' href="admin/shop_collections/collections/add/all/{{id}}">Add All</a></td>
				</tr>
				<tr>
					<td>Remove all products from the <em>`{{name}}`</em> collection</td>
					<td><a class='modal button red' href="admin/shop_collections/collections/clear/products/{{id}}">Remove All</a></td>
				</tr>
			</tbody>
		</table>
	</div>
</section>

</div>
