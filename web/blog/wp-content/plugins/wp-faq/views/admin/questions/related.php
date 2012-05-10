<div class="wrap">
	<h2><?php _e('Related Questions', $this -> plugin_name); ?></h2>
    
    <p>
		<small><?php _e('Drag and drop questions related to: ', $this -> plugin_name); ?></small><br/>
        <strong><?php echo $question -> question; ?></strong>
    </p>
    
    <div id="message" style="color:red;"></div>
    
    <div style="float:left; width:49%;">
    	<h3><?php _e('Related Questions', $this -> plugin_name); ?></h3>
        
        <ul id="related">	
			<?php if (empty($related)) : ?>
				<li class="<?php echo $this -> pre; ?>lineitem" id="related_r"><?php _e('Drag and drop related questions here', $this -> plugin_name); ?></li>
			<?php else : ?>
				<?php $rquestions = array(); ?>
				<?php foreach ($related as $r) : ?>
					<?php $wpfaqDb -> model = $wpfaqQuestion -> model; ?>
					<?php $rquestion = $wpfaqDb -> find(array('id' => $r -> rel_id)); ?>
					<li class="<?php echo $this -> pre; ?>lineitem" id="related_<?php echo $rquestion -> id; ?>">
						<?php echo $rquestion -> question; ?>
					</li>
					<?php $rquestions[] = $rquestion -> id; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
    </div>
    
    <div style="float:left; width:2%;"></div>
    
    <div style="float:left; width:49%;">
    	<h3><?php _e('All Questions', $this -> plugin_name); ?></h3>
        
        <?php if (!empty($questions)) : ?>
        	<div style="overflow:auto; max-height:300px;">
                <ul style="display:block;" id="questions">
                	<?php $unrelated = false; ?>
                    <?php foreach ($questions as $question) : ?>
                    	<?php if ($_GET['id'] != $question -> id) : ?>
							<?php if (empty($rquestions) || (!empty($rquestions) && !in_array($question -> id, $rquestions))) : ?>
                                <li id="related_<?php echo $question -> id; ?>" class="wpfaqlineitem"><?php echo $question -> question; ?></li>
                                <?php $unrelated[] = $question -> id; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    
                    <?php if (empty($unrelated)) : ?>
                    	<li class="<?php echo $this -> pre; ?>lineitem" id="questions_r"><?php _e('Drag and drop unrelated questions here', $this -> plugin_name); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php else : ?>
        	<p class="<?php echo $this -> pre; ?>error"><?php _e('No questions were found', $this -> plugin_name); ?></p>
        <?php endif; ?>
    </div>
    
    <script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("ul#related").sortable({
			connectWith: "ul#questions",
			placeholder: "sortableHelper",
			forcePlaceholderSize: 'true',
			start: function(event, ui) {
				jQuery('#message').slideUp('slow');
			},
			update: function(event, ui) {
				jQuery("#message").load(wpfaqAjax + "?cmd=questions_related&id=<?php echo $_GET['id']; ?>", jQuery("ul#related").sortable('serialize')).slideDown('slow');
			}
		});
			
		jQuery("ul#questions").sortable({connectWith: "ul#related", placeholder: "sortableHelper", forcePlaceholderSize: 'true'});
		jQuery("ul#questions").disableSelection();
	});
	</script>
</div>