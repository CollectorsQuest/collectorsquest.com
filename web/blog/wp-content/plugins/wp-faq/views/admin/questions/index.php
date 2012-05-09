<div class="wrap">
	<h2><?php _e('Manage FAQ Questions', $this -> plugin_name); ?> <?php echo $wpfaqHtml -> link(__('Add New', $this -> plugin_name), '?page=' . $this -> sections -> questions_save, array('class' => "button add-new-h2")); ?></h2>
	
	<form id="posts-filter" method="post">
		<?php if (!empty($questions)) : ?>
			<ul class="subsubsub">
				<li><?php echo (empty($_GET['showall'])) ? $paginate -> allcount : count($questions); ?> <?php _e('questions', $this -> plugin_name); ?> |</li>
				
				<?php if (empty($_GET['showall'])) : ?>
					<li><?php echo $wpfaqHtml -> link(__('Show All', $this -> plugin_name), '?page=' . $this -> sections -> questions . '&amp;showall=1', array('title' => __('Show all FAQ questions', $this -> plugin_name))); ?> |</li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Show Paging', $this -> plugin_name), "?page=" . $this -> sections -> questions, array('title' => __('Show paginated FAQ questions', $this -> plugin_name))); ?> |</li>
				<?php endif; ?>
				
				<?php if ((isset($_COOKIE[$this -> pre . 'questionssorting']) && $_COOKIE[$this -> pre . 'questionssorting'] == "modified") || (!isset($_COOKIE[$this -> pre . 'questionsquestiondir']) || $_COOKIE[$this -> pre . 'questionsquestiondir'] == "DESC")) : ?>
					<li><?php echo $wpfaqHtml -> link(__('A to Z', $this -> plugin_name), '#void', array('onclick' => "change_sorting('question', 'ASC');")); ?> |</li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Z to A', $this -> plugin_name), '#void', array('onclick' => "change_sorting('question', 'DESC');")); ?> |</li>
				<?php endif; ?>
				
				<?php if ((isset($_COOKIE[$this -> pre . 'questionssorting']) && $_COOKIE[$this -> pre . 'questionssorting'] == "question") || (!isset($_COOKIE[$this -> pre . 'questionsmodifieddir']) || $_COOKIE[$this -> pre . 'questionsmodifieddir'] == "ASC")) : ?>
					<li><?php echo $wpfaqHtml -> link(__('New to Old', $this -> plugin_name), '#void', array('onclick' => "change_sorting('modified', 'DESC');")); ?></li>
				<?php else : ?>
					<li><?php echo $wpfaqHtml -> link(__('Old to New', $this -> plugin_name), '#void', array('onclick' => "change_sorting('modified', 'ASC');")); ?></li>
				<?php endif; ?>
			</ul>
			<p class="search-box">
				<input type="text" name="searchterm" value="<?php echo (empty($_POST['searchterm'])) ? $_GET[$this -> pre . 'searchterm'] : $_POST['searchterm']; ?>" id="post-search-input" class="search-input" />
				<input type="submit" name="search" value="<?php _e('Search Questions', $this -> plugin_name); ?>" class="button" />
			</p>
		<?php endif; ?>
	</form>
	
	<?php $this -> render('questions' . DS . 'loop', array('questions' => $questions, 'paginate' => $paginate), 'admin', true); ?>
</div>