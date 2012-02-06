<?php

/**
 * CollectionItemForSale form.
 *
 * @package    collectornew
 * @subpackage form
 * @author     Prakash Panchal
 */
class CollectionItemForSaleForm extends BaseCollectionItemForSaleForm
{
	protected $amConditions;	
	public function configure()
	{
		$this->amConditions = array('excellent'=>'Excellent','very good'=>'Very Good','good'=>'Good','fair'=>'Fair','poor'=>'Poor');
		unset($this['created_at'], $this['updated_at'],$this['id'],$this['is_sold']);
		
		//set Widgets		
		$this->setWidgets(array(
		  'price'					=> new sfWidgetFormInput(array()),
		  'condition'				=> new sfWidgetFormSelect(array('choices' => $this->amConditions), array("class" => "text", "style" => "font-size: 14pt; margin-bottom: 5px;")),
		  'is_price_negotiable'		=> new sfWidgetFormInputCheckbox(),
		  'is_shipping_free'		=> new sfWidgetFormInputCheckbox(),
		  'is_post_at_marketplace'	=> new sfWidgetFormInputCheckbox(),
		));
		
		$omUserCollections = Collection::getCollectionAsPerCollector(sfContext::getInstance()->getUser()->getAttribute('id','','collector'));
		$this->amUserCollections = array('' => '---- Select Collection ----');
		if($omUserCollections)
		{
			foreach($omUserCollections as $amDataset)
				$this->amUserCollections[$amDataset['ID']] = $amDataset['NAME'];
		}
		if($this->isNew())
		{			
			$this->widgetSchema['collection_id'] = new sfWidgetFormSelect(array('choices' => $this->amUserCollections), array("class" => "text", "style" => "font-size: 14pt; margin-bottom: 5px;"));
		}

	
		//set Validation
		$this->setValidators(array(
		  'price'			=> new sfValidatorNumber(array('required'=> true),array('required' => 'Please enter price', 'invalid' => 'Please enter valid price')),
  		  'condition'		=> new sfValidatorChoice(array('choices' => array_keys($this->amConditions), 'required' => false)),
		));

		if($this->isNew())
		{
			$this->validatorSchema['collection_id'] = new sfValidatorChoice(array('choices' => array_keys($this->amUserCollections), 'required' => true),array('required' => 'Please select collection'));
		}

		$this->setDefault('is_price_negotiable',false);
		$this->setDefault('is_shipping_free',false);

		//set Labels
		$this->widgetSchema->setLabel(array(
		  'price'				=> 'Price',
		  'condition'			=> 'Condition',
		));

	
		$this->widgetSchema->setNameFormat('collection_item_for_sale[%s]');
		
		$this->validatorSchema->setOption('allow_extra_fields', true);
		$this->validatorSchema->setOption('filter_extra_fields', false);
		// Disable the secret key
        $this->disableLocalCSRFProtection();
	}
	public function updateObject($values = null) 
	{ 
		$amFormRequest = sfContext::getInstance()->getRequest()->getParameter($this->getName());
		
		if(sfContext::getInstance()->getRequest()->getParameter('item_id'))
			$this->values['item_id']			 = sfContext::getInstance()->getRequest()->getParameter('item_id');

		$this->values['is_price_negotiable'] = isset($amFormRequest['is_price_negotiable']) ? 1 : 0;
		$this->values['is_shipping_free'] 	 = isset($amFormRequest['is_shipping_free']) ? 1 : 0;
		parent::updateObject($this->values);
	}
}
