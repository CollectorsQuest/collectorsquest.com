<div class="table table_content">
	<p class="sub"><?php _e('Groups &amp; Questions', $this -> plugin_name); ?></p>
    <table>
    	<tbody>
        	<tr class="first">
            	<td class="first b b-posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> groups); ?>"><?php echo $counts['groups_count']; ?></a></td>
                <td class="t posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> groups); ?>"><?php _e('Groups', $this -> plugin_name); ?></a></td>
            </tr>
            <tr>
            	<td class="first b b-posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> questions); ?>"><?php echo $counts['allquestions_count']; ?></a></td>
                <td class="t posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> questions . '&approved=all'); ?>"><?php _e('Questions', $this -> plugin_name); ?></a></td>
            </tr>
            <tr>
            	<td class="first b b-posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> questions . '&approved=Y'); ?>"><?php echo $counts['approvedquestions_count']; ?></a></td>
                <td class="t posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> questions . '&approved=Y'); ?>" class="approved"><?php _e('Approved Questions', $this -> plugin_name); ?></a></td>
            </tr>
            <tr>
            	<td class="first b b-posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> questions . '&approved=N'); ?>"><?php echo $counts['unapprovedquestions_count']; ?></a></td>
                <td class="t posts"><a href="<?php echo admin_url('admin.php?page=' . $this -> sections -> questions . '&approved=N'); ?>" class="waiting"><?php _e('Unapproved Questions', $this -> plugin_name); ?></a></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table table_discussion">
	<p class="sub"><?php _e('Latest Questions', $this -> plugin_name); ?></p>
    <?php if (!empty($latest_questions)) : ?>
        <table>
            <tbody>
            	<?php $q = 1; ?>
            	<?php foreach ($latest_questions as $question) : ?>
                	<tr<?php echo ($q == 1) ? ' class="first"' : ''; ?>>
                    	<td class="t posts"><?php echo $wpfaqHtml -> truncate($question -> question, 25); ?></td>
                        <td class=""><a class="waiting" href="admin.php?page=<?php echo $this -> sections -> questions_save; ?>&amp;id=<?php echo $question -> id; ?>"><?php _e('Edit', $this -> plugin_name); ?></td>
                    </tr>
                	<?php $q++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
    	<p><?php _e('There are no questions yet!', $this -> plugin_name); ?></p>
        <p><a href="admin.php?page=<?php echo $this -> sections -> questions_save; ?>"><?php _e('Add a Question', $this -> plugin_name); ?></a></p>
    <?php endif; ?>
</div>

<!-- clear the floats -->
<br class="clear" />