        </td>
      </tr>
      <tr>
        <td colspan="2" style="padding: 15px; padding-bottom: 0;">
          <?php include_slot('email_footer_greeting', __('Thank you for choosing collectorsquest.com!', array(), 'emails')); ?>
        </td>
      </tr>
      <tr>
        <td width="300" style="padding: 15px; padding-right: 0; color: grey;">
          <b>Collectors' Quest</b>, Where Hunters Gather!
        </td>
        <td align="right" style="padding: 15px; white-space: nowrap;" nowrap="nowrap">
          <?= image_tag('icons/facebook.png', array('width' => 20, 'height' => 18, 'align' => 'absmiddle', 'absolute' => true)); ?>&nbsp;<?= link_to(__('Become a Fan of Collectors\' Quest on Facebook!'), 'http://www.facebook.com/pages/Collectors-Quest/119338990397', array('target' => '_blank')); ?>
        </td>
      </tr>
      </table>
      <?php include_slot('email_footer_bottom'); ?>
      <br clear="all"><br>
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
