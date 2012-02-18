<?php

/**
 * Package form.
 *
 * @package    collectornew
 * @subpackage form
 * @author     Prakash Panchal
 */
class PackageForm extends BasePackageForm
{
  public function configure()
  {   	
	$this->asPlanType = array('Casual'=>'Casual','Power'=>'Power');
	unset($this['created_at'], $this['updated_at'],$this['id']);

	//set Widgets		
	$this->setWidgets(array(
		'package_name'			=> new sfWidgetFormInput(),
		'package_description'	=> new sfWidgetFormTextarea(),
		'max_items_for_sale'	=> new sfWidgetFormInput(),
		'package_price'			=> new sfWidgetFormInput(),
		'plan_type'				=> new sfWidgetFormSelect(array('choices' => $this->asPlanType)),		
	));
	
	//set Validation
	$this->setValidators(array(
		'package_name'			=> new sfValidatorString(array(),array('required' => 'Please enter title')),
		'package_description'	=> new sfValidatorString(array('required' => false)),
		'max_items_for_sale'	=> new sfValidatorNumber(array('required'=> true),array('required' => 'Please enter allowed items', 'invalid' => 'Please enter valid allowed items')),
		'package_price'			=> new sfValidatorNumber(array('required'=> true),array('required' => 'Please enter package price', 'invalid' => 'Please enter valid package price')),
		'plan_type'				=> new sfValidatorChoice(array('choices' => array_keys($this->asPlanType), 'required' => false))
	));
	
	$this->widgetSchema->setNameFormat('package[%s]');
	
	$this->validatorSchema->setOption('allow_extra_fields', true);
	$this->validatorSchema->setOption('filter_extra_fields', false);
  }
}
