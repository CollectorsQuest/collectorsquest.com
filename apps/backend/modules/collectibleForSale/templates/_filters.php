<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<style type="text/css" media="screen">
  #sf_admin_bar {
    float: none;
    margin-bottom: 20px;
    margin-left: 0;
  }
  
  #sf_admin_content, .sf_admin_list {
    width: 100%;
  }
  
  .sf_admin_filter div.header {
    padding: 5px;
    font-size: 130%;
    font-weight: bold;
    background: url(/backend/images/background.jpg);
  }
  
  .sf_admin_filter div.body div {
    display: inline-block;
    height: 100%;
    padding: 0 5px;
    /*outline: 1px solid red; /* DEBUG */
  }
  
  .sf_admin_filter label {
    float: none;
    display: inline-block;
  }
  
  .sf_admin_filter div.footer {
    padding: 5px;
    background: url(/backend/images/background.jpg);
  }
</style>

<div class="sf_admin_filter">
  <div class="header">Filters</div>
  <?php if ($form->hasGlobalErrors()): ?>
    <?php echo $form->renderGlobalErrors() ?>
  <?php endif; ?>

  <form action="<?php echo url_for('collectible_for_sale_collection', array('action' => 'filter')) ?>" method="post">
    <div>
      <div class="body">
        <?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?>
          <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal()))
            continue ?>
          <?php
          include_partial('collectibleForSale/filters_field', array(
            'name' => $name,
            'attributes' => $field->getConfig('attributes', array()),
            'label' => $field->getConfig('label'),
            'help' => $field->getConfig('help'),
            'form' => $form,
            'field' => $field,
            'class' => 'sf_admin_form_row sf_admin_' . strtolower($field->getType()) . ' sf_admin_filter_field_' . $name,
          ))
          ?>
        <?php endforeach; ?>
      </div>
      <div class="footer">
        <span style="float: right;"><?php echo link_to('Export to CSV', 'collectible_for_sale_export') ?></span>
        <?php echo $form->renderHiddenFields() ?>
        <?php echo link_to(__('Reset', array(), 'sf_admin'), 'collectible_for_sale_collection', array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post')) ?>
        <input type="submit" value="<?php echo __('Filter', array(), 'sf_admin') ?>" />
      </div>
    </div>
  </form>
</div>
