<!-- Added by Prakash Panchal 30-Mar-2011 -->
<?php // if ($sf_request->hasErrors()):?>
<!--	<div id="error-explanation">
		<h2><?php // sprintf('&nbsp;%s:', $title);?></h2>
		<br />
		<ul>
			<?php 
				/*
				foreach($sf_request->getErrors() as $error):
					echo '<li>'.$error.'</li>';
				endforeach;
				*/
			?>
	</div>
--><?php if($sf_user->hasFlash('errors')):?>
	<div id="error-explanation">
		<h2><?php echo $title;?></h2>
		<br />
		<ul>
			<?php foreach($sf_user->getFlash('errors') as $error):
					echo '<li>'.$error.'</li>';
				  endforeach;
			?>
	</div>
<?php elseif($sf_user->hasFlash('warning')):?>
	<div id="error-explanation">
		<?php echo $sf_user->getFlash('warning');?>
	</div>
<?php elseif($sf_user->hasFlash('success')):?>
	<div id="success-explanation">
		<?php echo $sf_user->getFlash('success');?>
	</div>
<?php endif;?>