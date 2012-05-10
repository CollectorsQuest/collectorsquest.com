<div class="wrap">
	<h2><?php _e('Manage FAQs Groups', $this -> plugin_name); ?> <?php echo $wpfaqHtml -> link(__('Add New', $this -> plugin_name), '?page=' . $this -> sections -> groups_save, array('class' => "button add-new-h2")); ?></h2>

	<form id="posts-filter" method="post">	
		<?php if (!empty($groups)) : ?>
			<ul class="subsubsub">
				<li><?php echo (empty($_GET['showall'])) ? $paginate -> allcount : count($groups); ?> <?php _e('question groups', $this -> plugin_name); ?> |</li>
				
				<?php if (empty($_GET['showall'])) : ?>
					<li><?php echo $wpfaqHtml -> link(__('Show All', $this -> plugin_name), '?page=' . $this -> sections -> groups . '&amp;showall=1', array('title' => __('Show all FAQ groups', $this -> plugin_name))); ?> |</li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Show Paging', $this -> plugin_name), "?page=" . $this -> sections -> groups, array('title' => __('Show paginated FAQ groups', $this -> plugin_name))); ?> |</li>
				<?php endif; ?>
				
				<?php if ((isset($_COOKIE[$this -> pre . 'groupssorting']) && $_COOKIE[$this -> pre . 'groupssorting'] == "modified") || (!isset($_COOKIE[$this -> pre . 'groupsnamedir']) || $_COOKIE[$this -> pre . 'groupsnamedir'] == "DESC")) : ?>
					<li><?php echo $wpfaqHtml -> link(__('A to Z', $this -> plugin_name), '#void', array('onclick' => "change_sorting('name', 'ASC');")); ?> |</li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Z to A', $this -> plugin_name), '#void', array('onclick' => "change_sorting('name', 'DESC');")); ?> |</li>
				<?php endif; ?>
				
				<?php if ((isset($_COOKIE[$this -> pre . 'groupssorting']) && $_COOKIE[$this -> pre . 'groupssorting'] == "name") || (!isset($_COOKIE[$this -> pre . 'groupsmodifieddir']) || $_COOKIE[$this -> pre . 'groupsmodifieddir'] == "ASC")) : ?>
					<li><?php echo $wpfaqHtml -> link(__('New to Old', $this -> plugin_name), '#void', array('onclick' => "change_sorting('modified', 'DESC');")); ?></li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Old to New', $this -> plugin_name), '#void', array('onclick' => "change_sorting('modified', 'ASC');")); ?></li>
				<?php endif; ?>
			</ul>
			<p class="search-box">
				<input type="text" name="searchterm" value="<?php echo (empty($_POST['searchterm'])) ? $_GET[$this -> pre . 'searchterm'] : $_POST['searchterm']; ?>" class="search-input" id="post-search-input" />
				<input type="submit" name="search" value="<?php _e('Search Groups', $this -> plugin_name); ?>" class="button" />
			</p>
		<?php endif; ?>
	</form>
	
	<?php $this -> render('groups' . DS . 'loop', array('groups' => $groups, 'paginate' => $paginate), 'admin', true); ?>
</div>