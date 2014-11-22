<div class="one_half" id="">

	<section class="title">
		<h4><?php echo lang('shop_collections:create_collection'); ?></h4>
	</section>

	<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>


	<section class="item form_inputs">

		<div class="content">

			<fieldset>
				<ul>
					<li>
						<a class='btn green' href="admin/shop_collections/collections/">Back to List</a>
					</li>
					<li class="<?php echo alternator('even', ''); ?>">
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