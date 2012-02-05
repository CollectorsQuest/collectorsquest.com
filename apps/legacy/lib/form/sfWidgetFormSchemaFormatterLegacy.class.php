<?php

class sfWidgetFormSchemaFormatterLegacy extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat                 = '<div class="span-4" style="text-align: right;">%label%</div><div class="prepend-1 span-13 last">%error%%field%%help%%hidden_fields%</div><div class="clear append-bottom"></div>',
    $helpFormat                = '<span class="help">%help%</span>',
    $errorRowFormat            = '<dt class="error">Errors:</dt><dd>%errors%</dd>',
    $errorListFormatInARow     = '<ul class="error_list">%errors%</ul>',
    $errorRowFormatInARow      = '<li>%error%</li>',
    $namedErrorRowFormatInARow = '<li>%name%: %error%</li>',
    $decoratorFormat           = '<dl id="formContainer">%content%</dl>';
}
