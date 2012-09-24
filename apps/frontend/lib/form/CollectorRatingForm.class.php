<?php

/**
 * CollectorRating form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors Quest, Inc.
 */
class CollectorRatingForm extends BaseCollectorRatingForm
{
  public function configure()
  {
    $this->useFields(array('rate','comment'));
    $this->getWidgetSchema()->setFormFormatterName('BootstrapWithRowFluid');

    $this->widgetSchema['rate'] = new sfWidgetFormSelectRadio(array(
      'choices'   => array_merge(
        $this->widgetSchema['rate']->getOption('choices'), array(''=>'Leave later')
      ),
      'formatter' => array($this, 'inlineRadioInputFormatter'),
    ));
  }

  public function inlineRadioInputFormatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $label = strip_tags($input['label']);
      $rows[] = $widget->renderContentTag('label', $input['input'] . ucfirst($label).'&nbsp',
        array(
          'class' => 'radio inline rate_label' . ($label != 'Leave later' ? ' label rate_'.strtolower($label) : '')
        ));
    }

    return !$rows ? '' : $widget->renderContentTag('div', implode($this->getOption('separator'), $rows),
      array('class' => $this->getOption('class')));
  }

}
