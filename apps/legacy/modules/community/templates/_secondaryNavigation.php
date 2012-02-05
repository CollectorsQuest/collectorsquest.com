<!-- Added by prakash panchal 25-March-2011 -->
<?php 
use_helper("Javascript", "nifty", "Asset");
use_stylesheet("secondary-menu")
echo javascript_tag(nifty_round_elements("ul.secondary-menu a", "transparent"));
?>
<div style="height: 28px; background: #BADC70; padding: 5px 10px 5px 20px; overflow: hidden;" class="yui-g">
	<?php if (!$sf_user->isAuthenticated()):?>
		<div style="float: right; padding-right: 10px; padding-top: 4px; font-size: 12pt;">
			<?php 
				echo link_to('sign up', 'community/signup', array("style" => "color: white; font-size: 12pt"));
				echo link_to('log in','community/login', array("style" => "color: white; font-size: 12pt"));
			?>
		</div>
	<?php elseif ($sf_user->isAuthenticated()):?>
		<div style="float: right; padding-right: 10px; padding-top: 4px; font-size: 12pt;">
			<?php
				echo link_to('profile', 'community/profile', array("style" => "color: white; font-size: 12pt"));
				echo link_to('sign out','community/logout',array("style" => "color: white; font-size: 12pt"));
			?>
		</div>
	<?php endif;?>
	<ul class="secondary-menu">
		<?php 
		    foreach ($menu as $key => $item):
				if(array_key_exists("auth", $item)):
					if ($item["auth"] === false && $sf_user->isAuthenticated() !== false):
						continue;
					endif;
					if ($item["auth"] === true && $sf_user->isAuthenticated() !== true):
						continue;
					endif;
				endif;
				$class = ($key === $search)?"activelink":'';
				echo '<li class = "'.$class.'">'.link_to($item["text"], $item["url"]).'</li>';
			endforeach;
		?>
	</ul>
</div>