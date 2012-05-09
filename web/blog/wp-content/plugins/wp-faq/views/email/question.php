<p>
	<?php _e('A new FAQ question has been submitted:', $this -> plugin_name); ?>
</p>

<p>-------------------------------------------</p>
<?php echo wpautop(stripslashes($question -> question)); ?>
<p>-------------------------------------------</p>

<?php if (!empty($question -> email)) : ?>
    <p>
        <?php _e('The email of this user is:', $this -> plugin_name); ?> <?php echo $question -> email; ?>
    </p>
<?php endif; ?>

<p>
	<?php _e('Follow the URL below to answer and approve the question', $this -> plugin_name); ?> :<br/>
	<?php echo rtrim(get_bloginfo('wpurl'), '/'); ?>/wp-admin/admin.php?page=<?php echo $this -> sections -> questions_save; ?>&id=<?php echo $question -> id; ?>
</p>