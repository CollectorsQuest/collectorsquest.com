<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<a name="filters"></a>
<div class="sf_admin_filter" style="height: auto;">
  <?php if ($form->hasGlobalErrors()): ?>
  <div class="alert alert-block error">
    <p><strong>Oh snap! You got an error!</strong> Change this and that and <a href="#">try again</a>.</p>
    <?php echo $form->renderGlobalErrors() ?>
  </div>
  <?php endif; ?>

  <h2>Filters <small>for <?php echo __('Package Transactions', array(), 'messages') ?></h2>

  <form action="<?php echo url_for('package_transaction_collection', array('action' => 'filter')) ?>" method="post" class="well well-reset-paggination form-horizontal">
    <div class="row-fluid">
      <div class="span6">
        <fieldset>
        <?php $i = 0; ?>
        <?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?>

        <?php if (0 == $i % 3 && 0 != $i): ?>
            </fieldset>
          </div> <!-- .span6 -->
        <?php endif; ?>

        <?php if (0 == $i % 6 && 0 != $i): ?>
        </div> <!-- .row-fluid -->
        <div class="row-fluid">
        <?php endif; ?>

        <?php if (0 == $i % 3 && 0 != $i): ?>
          <div class="span6">
            <fieldset>
        <?php endif; ?>

          <div class="control-group">
        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
          <?php include_partial('packageTransaction/filters_field', array(
            'name'       => $name,
            'attributes' => $field->getConfig('attributes', array()),
            'label'      => $field->getConfig('label'),
            'help'       => $field->getConfig('help'),
            'form'       => $form,
            'field'      => $field,
            'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_filter_field_'.$name,
          )) ?>
          </div>

        <?php $i++; ?>
        <?php endforeach; ?>
        </fieldset>
      </div> <!-- .span6 -->
    </div> <!-- .row-fluid -->
    <div class="form-actions form-actions-fix-posiotion">
      <?php echo $form->renderHiddenFields() ?>
      <button type="submit" name="filter_action[submit]" class="btn btn-primary"><?php echo __('Filter', array(), 'ice_backend_plugin') ?></button>
      <button type="submit" name="filter_action[calculate_profit_submit]" class="btn btn-info"><?php echo __('Calculate profit', array(), 'ice_backend_plugin') ?></button>
      <?php echo link_to(__('Reset', array(), 'ice_backend_plugin'), 'package_transaction_collection', array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post', 'class' => 'btn')) ?>
    </div>
  </form>
</div>
