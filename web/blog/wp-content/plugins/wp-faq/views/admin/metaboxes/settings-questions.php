<table class="form-table">
	<tbody>
    	<tr>
        	<th><label for="filter_the_content_N"><?php _e('Apply the_content Filter', $this -> plugin_name); ?></label></th>
            <td>
            	<label><input <?php echo ($this -> get_option('filter_the_content') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="filter_the_content" value="Y" id="filter_the_content_Y" /> <?php _e('Enabled', $this -> plugin_name); ?></label>
                <label><input <?php echo ($this -> get_option('filter_the_content') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="filter_the_content" value="N" id="filter_the_content_N" /> <?php _e('Disabled', $this -> plugin_name); ?></label>
            	<span class="howto"><?php _e('Turn this on to parse answers through "the_content" filter used by 3rd party plugins.', $this -> plugin_name); ?></span>
            </td>
        </tr>
    	<tr>
        	<th><label for="showrelatedquestions_Y"><?php _e('Show Related Questions', $this -> plugin_name); ?></label></th>
            <td>
            	<label><input <?php echo ($this -> get_option('showrelatedquestions') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="showrelatedquestions" value="Y" id="showrelatedquestions_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
                <label><input <?php echo ($this -> get_option('showrelatedquestions') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="showrelatedquestions" value="N" id="showrelatedquestions_N" /> <?php _e('No', $this -> plugin_name); ?></label>
                <span class="howto"><?php _e('should specified related questions be shown on individual question posts/pages?', $this -> plugin_name); ?></span>
            </td>
        </tr>
        <tr>
        	<th><label for="showquestionexcerpts_N"><?php _e('Show Excerpts', $this -> plugin_name); ?></label></th>
            <td>
            	<label><input onclick="jQuery('#showquestionexcerpts_div').show();" <?php echo ($this -> get_option('showquestionexcerpts') == "Y") ? 'checked="checked"' : ''; ?> type="radio" name="showquestionexcerpts" value="Y" id="showquestionexcerpts_Y" /> <?php _e('Yes', $this -> plugin_name); ?></label>
                <label><input onclick="jQuery('#showquestionexcerpts_div').hide();" <?php echo ($this -> get_option('showquestionexcerpts') == "N") ? 'checked="checked"' : ''; ?> type="radio" name="showquestionexcerpts" value="N" id="showquestionexcerpts_N" /> <?php _e('No', $this -> plugin_name); ?></label>
                <span class="howto"><?php _e('show excerpt of questions in the list for questions with a post/page (link to post/page)', $this -> plugin_name); ?></span>
            </td>
        </tr>
    </tbody>
</table>

<div id="showquestionexcerpts_div" style="display:<?php echo ($this -> get_option('showquestionexcerpts') == "Y") ? 'block' : 'none'; ?>;">
	<table class="form-table">
    	<tbody>
        	<tr>
            	<th><label for="questionexcerptreadmore"><?php _e('Read More Link Text', $this -> plugin_name); ?></label></th>
                <td>
                	<input type="text" class="widefat" name="questionexcerptreadmore" value="<?php echo esc_attr(stripslashes($this -> get_option('questionexcerptreadmore'))); ?>" id="questionexcerptreadmore" />
                    <span class="howto"><?php _e('Text of the read more link when a question excerpt is shown.', $this -> plugin_name); ?></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>