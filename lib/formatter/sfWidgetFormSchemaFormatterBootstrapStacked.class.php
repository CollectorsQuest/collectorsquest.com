<?php

class sfWidgetFormSchemaFormatterBootstrapStacked extends sfWidgetFormSchemaFormatterBootstrap
{
  protected $rowFormat = '<div class="control-group %error_class%">
                              %label%
                              %field%
                              %help%
                              %errors%
                              %hidden_fields%
                          </div>';
}
