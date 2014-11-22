
				<?php if(!(isset($userDisplayMode))):?>
					$userDisplayMode = 'edit';
				<?php endif;?>

		      <?php if(!group_has_role('shop_collections','admin_manage'))
		      {
		          $userDisplayMode = 'view';
		          echo "<fieldset><h3 style='color:#f00'>You do not have permission to manage collections.</h3></fieldset>";
		      }
		      ?>				


				<fieldset>
						<h3>Collection Assignments</h3>
						<h4>If the Collection does not exist, go over to manage collections.</h4>
						<?php if($userDisplayMode == 'edit'):?>
	            			<a class="sbtn gray glow" href="admin/shop_collections/collections/create">Create a new Collection</a>
	            			<!-- <a class="sbtn gray modal" href="shop_collections/admin/collections/addoption/0/quicknew">Create a new Collection (inline)</a> -->
						<?php endif;?>


				</fieldset>


				<fieldset>

						<h4>Assign your product to a collection by selecting the LINK button.</h4>


							<div class="input">

								<?php if(isset($modules['shop_collections']['list'])) : ?>
									<table class='collection_rows'>

										<?php foreach($modules['shop_collections']['list'] as $collection_id => $collection) : ?>
											<tr>
												<td style='width:40%'><?php echo $collection; ?></td>
												<td>
                                                    <span></span>
                                                    <span style='float:right'>
													<?php if(isset($modules['shop_collections']['assigned'])) :?>

															<?php if(isset($modules['shop_collections']['assigned'][$collection_id])) :?>


																<?php if($userDisplayMode != 'edit'):?>
											            		  	Assigned
											            		<?php else:?>

																	<?php $link_id = $modules['shop_collections']['assigned'][$collection_id];?>

																	<a class='button blue collection_linker' href='admin/shop_collections/collections/unlink/<?php echo $id;?>/<?php echo $collection_id;?>/<?php echo $link_id;?>'>Unlink</a>

																<?php endif;?>


															<?php else:?>

																<?php if($userDisplayMode == 'edit'):?>
											            			<a class='button gray collection_linker' href='admin/shop_collections/collections/link/<?php echo $id;?>/<?php echo $collection_id;?>'>Link</a>
											            		<?php endif;?>

															<?php endif;?>

													<?php else:?>

														<?php if($userDisplayMode == 'edit'):?>
															<a class='button gray collection_linker' href='admin/shop_collections/collections/link/<?php echo $id;?>/<?php echo $collection_id;?>'>Link</a>
														<?php endif;?>

													<?php endif;?>
                                                    </span>
												</td>
											</tr>
										<?php endforeach;?>

									</table>


								<?php endif;?>

							</div>


				</fieldset>


<script>

	function collection_enabled(button, is_linked, product_id, collection_id, link_id)
	{

		var link_link = 'admin/shop_collections/collections/link/'+product_id+'/'+collection_id;
		var unlink_link = 'admin/shop_collections/collections/unlink/'+product_id+'/'+collection_id+'/'+link_id;

		link = ((is_linked)? unlink_link : link_link);
		buttonText = ((is_linked)? 'Unlink' : 'Link');
		classes = ((is_linked)? 'blue' :  'gray');

		button.text(buttonText);
		button.attr('href', link );
		button.attr('class','collection_linker button '+classes);

	}


    $(document).on('click', '.collection_linker', function(event) {

    	var button = $(this);
        var url = button.attr('href');

          $.post(url).done(function(data)
          {

              var obj = jQuery.parseJSON(data);

              if(obj.status == 'success')
              {
              		collection_enabled( button , obj.is_linked, obj.product_id , obj.collection_id , obj.link_id);
              }
              else
              {
      				alert('Unable to process collection..');
              }

          });


          // Prevent Navigation
          event.preventDefault();

    });

</script>