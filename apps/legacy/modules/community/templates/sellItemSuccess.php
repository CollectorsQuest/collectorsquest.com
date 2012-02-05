<?php
if($bSeller):
	use_stylesheet('legacy/forms.css');
	use_helper('Form','Javascript','Object');
	if($sf_params->get('thankyou') == true):?>
		<ul class="error_list"><li>Thank you for select/upgrade a package. please check mail to show details about your selected package!</li></ul>
	<?php endif;?>
	<br clear='all' /><br />
	<?php echo form_tag('@collectible_sell');
	if($sf_params->get('id') && $sf_params->get('collection_id')):

		use_javascript('tags.js');
		include_javascripts();
		use_stylesheet('jquery/tags.css');

		echo input_hidden_tag('id',$sf_params->get('id'));
		echo input_hidden_tag('item_id',$sf_params->get('item_id'));
		echo input_hidden_tag('edit',true);
		echo input_hidden_tag('collection_id',$sf_params->get('collection_id'));
		$ssSubmitButtonName = 'Save Changes';
	?>
	<div class="yui-g">
		<table width="100%">
		  <tbody>
			<tr>
			  <td align="center" style="width: 150px; background: #BBDC71; padding: 5px; color: #fff; font-size: 16px; border: 1px solid #BBDC71;"><b>Information</b></td>
			  <td>&nbsp;</td>
			</tr>
			<tr>
			  <td style="padding: 20px; border: 1px solid #ddd" colspan="2">
					<div style="float: right; width: 150px; height: 100px; margin: 10px; padding-left: 50px;" id="collection_item_preview">&nbsp;</div>
					<div><font class="helvetica-green-12"><?php echo $oCollectionForm['name']->renderLabel(); ?></font> </div>
					<div id="ext-gen6">
					  <div class="x-form-field-wrap" id="ext-gen7">
						<?php echo $oCollectionForm['name'];?>&nbsp;&nbsp;<span style="color: #62a0b2">required</span>
						  <span style="color:#FF0000"><?php	echo $oCollectionForm['name']->renderError();?></span>
					  </div>
					</div>
					<br />
					<div><font class="helvetica-green-12"> <?php echo $oCollectionForm['description']->renderLabel(); ?></font> </div>
					<div class="x-form-field-wrap" id="ext-gen7">
						<?php echo $oCollectionForm['description'];?>&nbsp;&nbsp;<span style="padding-top:0;color: #62a0b2">required</span>
						  <span style="color:#FF0000"><?php	echo $oCollectionForm['description']->renderError();?></span>
					  </div>
					<br />
					<div><font class="helvetica-green-12"> <?php echo $oCollectionForm['collection_category_id']->renderLabel(); ?></font> </div>
					  <div class="x-form-field-wrap" id="ext-gen7">
						<?php echo $oCollectionForm['collection_category_id'];?>&nbsp;&nbsp;<span style="padding-top:0;color: #62a0b2">required</span>
						  <span style="color:#FF0000"><?php	echo $oCollectionForm['collection_category_id']->renderError();?></span>
					  </div>
					<br />
					<div>
						<div style="float: right"><?php echo image_tag_collection($omCollection, '150x150', array('width' => 75, 'height' => 75)); ?></div>
						<font class="helvetica-green-12"> <?php echo $oCollectionForm['thumbnail']->renderLabel(); ?></font>
					</div>
					<div class="x-form-field-wrap" id="ext-gen7">
						<?php echo $oCollectionForm['thumbnail'];?>&nbsp;&nbsp;<span style="padding-top:0;color: #62a0b2">required</span>
						  <span style="color:#FF0000"><?php	echo $oCollectionForm['thumbnail']->renderError();?></span>
					</div>
					<br />
					<div><font class="helvetica-green-12"> <?php echo label_for("tags", 'Tags / Keywords:');?></font> </div>
					<div class="x-form-field-wrap" id="ext-gen7">
						<?php $tags = ($sf_params->get('collection[tags]')) ? $sf_params->get('collection[tags]') : $omCollection->getTags(); ?>
						<div style="background: #E9E9E9; vertical-align: middle; width: 400px; padding: 5px;">
							<select id="collection_tags" name="collection[tags][]">
							  <?php foreach ($tags as $tag): ?>
							  <option value="<?php echo  $tag; ?>" class="selected"><?php echo  $tag; ?></option>
							  <?php endforeach; ?>
							</select>
						</div>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<?php else:
		$ssSubmitButtonName = 'Sell Your Item';	?>
	<div style="float:right;">
		<?php
			$smTotalItemAllowed = ($omSeller->getItemsAllowed() > 0) ? $omSeller->getItemsAllowed() : 'Unlimited';
			echo '<span style="float:right;font-weight:bold;">Items Allowed For Sale ( '.$smTotalItemAllowed.' )</span><br>';
			echo ($omSeller->getItemsAllowed() >= 0) ? link_to('Want to Upgrade Your Package ?','@upgrade_package?id='.$sf_user->getCollector()->getId(),array('title' => 'Want to Upgrade Your Package ?')) : '';
		?>
	</div>

	<div class="yui-g">
	<table width="100%">
	  <tbody>
		<tr>
		  <td align="center" style="width: 150px; background: #BBDC71; padding: 5px; color: #fff; font-size: 16px; border: 1px solid #BBDC71;"><b>Choose an Item</b></td>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td style="padding: 20px; border: 1px solid #ddd" colspan="2"><div style="float: right; width: 150px; height: 100px; margin: 10px; padding-left: 50px;" id="collection_item_preview">&nbsp;</div>
			<div><font class="helvetica-green-12"><?php echo label_for("title", "Choose a Collection:");?></font> </div>
			<div id="ext-gen6">
			  <div class="x-form-field-wrap " id="ext-gen7" style="width: 375px;">
				<?php
					echo $oForm['collection_id']->render(
						array('onchange' =>	"ajaxChooseItem(this.value);return false;")
						);
					echo '&nbsp;&nbsp;<span style="color: #62a0b2">required</span>';
					echo '<br />'.$oForm['collection_id']->renderError();
				?>
			  </div>
			</div>
			<br />
			<div>
				<font class="helvetica-green-12"> <?php echo label_for("description", "Choose a Collection Item:");?></font>
				<div id="selectCollectibleDiv" class="x-form-field-wrap" style="width: 375px;">
					<?php include_partial('getCollectibleAsPerCollection',array('amUserCollectionsItems' => array()));
						echo '&nbsp;&nbsp;<span style="color: #62a0b2">required</span>';
						if($sf_user->hasFlash('msg_collection_item'))
							echo '<br /><ul class="error_list"><li>'.$sf_user->getFlash('msg_collection_item').'</li></ul>';
					 ?>
				</div>
			</div>

			</td>
		</tr>
		</tbody>
	</table>
	</div>
	<?php endif;?>
	<br clear='all' />
	<br />
	<table width='100%'>
	  <tr>
		<td align="center" style="width: 150px; background: #BBDC71; padding: 5px; color: #fff; font-size: 16px; border: 1px solid #BBDC71;"><b>Marketplace</b> </td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
	  	<td colspan="2">
			<?php echo $oForm->renderGlobalErrors();?>
		</td>
	  </tr>
	  <tr>
		<td colspan="2" style="padding: 20px; border: 1px solid #ddd;">

		  <div style="width: 350px; margin-top: 10px;">
			<div> <font class='helvetica-green-12'><?php echo $oForm['price']->renderLabel(); ?></font> :&nbsp;
			  <?php echo $oForm['price'];?>&nbsp; USD
			  <span style="color:#FF0000"><?php	echo $oForm['price']->renderError();?></span>
			  </div>
		  </div>
		  <div style="width: 350px; margin-top: 10px;">
			<div> <font class='helvetica-green-12'><?php echo $oForm['condition']->renderLabel(); ?></font> :&nbsp; <span style="color:#FF0000">
			  <?php	echo $oForm['condition']->renderError();?>
			  </span> <?php echo $oForm['condition'];?> </div>
		  </div>
		  <br />
		  <font class='helvetica-green-12'><?php echo $oForm['is_price_negotiable'];?><label for='is_price_negotiable'> Allow buyers to make me an offer</label></font>
		  <br /><br />
		  <font class='helvetica-green-12'><?php echo $oForm['is_shipping_free'];?><label for='is_shipping_free'> Free shipping</label></font>
		</td>
	  </tr>
	  <tr>
		<td colspan="2" align="center" style="padding-bottom: 10px;">&nbsp;</td>
	  </tr>
	  <tr>
		<td colspan="2" align="center" style="padding-bottom: 10px; text-align:center;">
		<?php
			//echo '<a href="/index.php/collection/6/slide-puzzles">Go back to the collection</a>';

			echo submit_tag($ssSubmitButtonName, array('style' => 'background:#FAFDE0; border:1px solid #C7C7C7; min-width:120px; height: 30px; cursor:pointer;','title' => $ssSubmitButtonName));?>
		</td>
	  </tr>
	</table>
	</form>
	<script type="text/javascript" language="javascript">
		<?php echo file_get_contents(sfConfig::get('sf_app_dir').'/modules/community/templates/js/sell_item.js');?>
		$(function()
		{
		  $('#collection_description').tinymce(
		  {
			script_url: '<?php echo sfConfig::get('app_static_domain_name')?>js/tiny_mce/tiny_mce.js',
			content_css : "<?php echo sfConfig::get('app_static_domain_name')?>css/legacy/tinymce.css",

			theme: "advanced",
			theme_advanced_buttons1: "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
			theme_advanced_buttons2: "",
			theme_advanced_buttons3: "",
			theme_advanced_toolbar_location: "external",
			theme_advanced_toolbar_align: "left",
			theme_advanced_resizing: true
		  });

		  $('#collection_tags').fcbkcomplete(
      {
        json_url: '<?php echo  url_for('@ajax_autocomplete?section=tags'); ?>',
        maxshownitems: 10,
        cache: true,
        filter_case: true,
        filter_hide: true,
        firstselected: true,
        filter_selected: true,
        addoncomma: true,
        input_min_size: 2,
        width: '388px',
        newel: true
		  });
		});
		function ajaxChooseItem(snCollectionId)
		{
			$.ajax({
			   update: "selectCollectibleDiv",
			   type: "POST",
			   url: "<?php echo url_for('@collection_item');?>",
			   data: "collection_id="+snCollectionId,
			   success: function(data){
					$("#selectCollectibleDiv").html(data);
			   }
			});
			return false;
		}
	</script>
<?php else:
	echo '<div style="font-size:20px">';
	if($sf_user->hasFlash('msg_seller'))
		echo '<span style="color:#FF0000;">'.$sf_user->getFlash('msg_seller').'</span>&nbsp;&nbsp;';
	echo '</div>';
endif;
?>
