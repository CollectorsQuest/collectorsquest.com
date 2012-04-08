<?= form_tag('@collector_signup?step='.$snStep, array('class' => 'form-horizontal')) ?>
  <fieldset>
    <?= $form ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Submit" />
    </div>
  </fieldset>
</form>
