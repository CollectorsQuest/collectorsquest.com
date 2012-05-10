<div class="wrap">
	<h2><?php _e('Manage Custom Fields', $this -> name); ?> <a class="add-new-h2 button" href="?page=<?php echo $this -> sections -> fields; ?>&amp;method=save" title="<?php _e('Create a new custom field', $this -> plugin_name); ?>"><?php _e('Add New', $this -> plugin_name); ?></a></h2>	
	<?php if (!empty($fields)) : ?>
		<form id="posts-filter" action="?page=<?php echo $this -> sections -> fields; ?>" method="post">
			<ul class="subsubsub">
				<li><?php echo (empty($_GET['showall'])) ? $paginate -> allcount : count($fields); ?> <?php _e('custom fields', $this -> plugin_name); ?> |</li>
				<?php if (empty($_GET['showall'])) : ?>
					<li><?php echo $wpfaqHtml -> link(__('Show All', $this -> plugin_name), $this -> url . '&amp;showall=1'); ?> |</li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Show Paging', $this -> plugin_name), '?page=' . $this -> sections -> fields); ?> |</li>
				<?php endif; ?>
				<?php if ((isset($_COOKIE[$this -> pre . 'fieldssorting']) && $_COOKIE[$this -> pre . 'fieldssorting'] == "modified") || (!isset($_COOKIE[$this -> pre . 'fieldstitledir']) || $_COOKIE[$this -> pre . 'fieldstitledir'] == "DESC")) : ?>
					<li><?php echo $wpfaqHtml -> link(__('A to Z', $this -> plugin_name), '#void', array('onclick' => "change_sorting('title', 'ASC');")); ?> |</li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Z to A', $this -> plugin_name), '#void', array('onclick' => "change_sorting('title', 'DESC');")); ?> |</li>
				<?php endif; ?>
				<?php if ((isset($_COOKIE[$this -> pre . 'fieldssorting']) && $_COOKIE[$this -> pre . 'fieldssorting'] == "title") || (!isset($_COOKIE[$this -> pre . 'fieldsmodifieddir']) || $_COOKIE[$this -> pre . 'fieldsmodifieddir'] == "ASC")) : ?>
					<li><?php echo $wpfaqHtml -> link(__('New to Old', $this -> plugin_name), '#void', array('onclick' => "change_sorting('modified', 'DESC');")); ?></li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Old to New', $this -> plugin_name), '#void', array('onclick' => "change_sorting('modified', 'ASC');")); ?></li>
				<?php endif; ?>
			</ul>
			<p class="search-box">
				<input type="text" name="searchterm" id="post-search-input" class="search-input" value="<?php echo (empty($_POST['searchterm'])) ? $_GET[$this -> pre . 'searchterm'] : $_POST['searchterm']; ?>" />
				<input class="button" name="search" type="submit" value="<?php _e('Search Fields', $this -> plugin_name); ?>" />
			</p>
		</form>
	<?php endif; ?>		
	<?php $this -> render('fields' . DS . 'loop', array('fields' => $fields, 'paginate' => $paginate), 'admin', true); ?>
</div>