<?php

/**
 * sfWidgetFormSchemaFormatterBootstrap basic twitter bootstrap formatter
 */
class sfWidgetFormSchemaFormatterBootstrapWithRowFluid extends sfWidgetFormSchemaFormatterBootstrap
{
  protected $rowFormat = '
    <div class="row-fluid spacer-7 %error_class%">
      <div class="span4 v-center-container-label">
        <span class="v-center">%label%</span>
      </div>
      <div class="span8 ">
        %field%
        %help%
        %errors%
        %hidden_fields%
      </div>
    </div>
';

  public function formatRequiredField($field)
  {
    return $field;
  }

}
