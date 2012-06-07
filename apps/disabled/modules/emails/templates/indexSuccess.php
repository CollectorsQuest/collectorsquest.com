<div class="prepend-1 span-19 last" style="margin-top: 20px;">
  <fieldset style="width: 90%;">
    <legend>Send a test email:</legend>
    <form action="<?= url_for('@emails'); ?>" method="post">
      <select name="email[partial]">
      <?php
        foreach ($emails as $section => $partials)
        {
          echo '<optgroup label="', $section,'">';
          foreach ($partials as $partial => $options)
          {
            echo '<option value="', $partial,'">', $options['name'], '</option>';
          }
          echo '</optgroup>';
        }
      ?>
      </select>
      <input type="text" name="email[subject]" value="(no subject)">
      <input type="text" name="email[to]" value="developers@collectorsquest.com">

      <input type="submit" value="Send Email!">
    </form>
  </fieldset>

  <?php
    foreach ($emails as $section => $partials)
    {
      echo '<h1>', $section, '</h1>';
      echo '<ul>';
      foreach ($partials as $partial => $options)
      {
        echo '<li>', link_to($options['name'], '@emails?partial='. $partial .'&random=true'), '</li>';
      }
      echo '</ul>';
    }
  ?>
</div>