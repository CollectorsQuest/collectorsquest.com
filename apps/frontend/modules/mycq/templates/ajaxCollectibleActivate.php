<?php
/**
 * @var $collectible Collectible
 *
 * @var $form CollectibleEditForm
 * @var $form_for_sale CollectibleForSaleEditForm
 */

  if ($form->hasErrors())
  {
    echo $form->renderAllErrors();
  }
  else if ($collectible->getIsPublic() === false)
  {
    // you should not be able to make this item for sale
    echo '<div class="alert"><strong>NOTE:</strong>',
    ' Your item will not be discoverable until you fill in all the required information!',
    ' (marked with a <span style="color: #cc0000;">*</span> in the form below)',
    '</div>';
  }

  if ($form_for_sale)
  {
    include_partial(
      'mycq/collectible_form_for_sale', array(
      'collectible' => $collectible,
      'form' => $form_for_sale,
      'form_shipping_us' => $form_shipping_us,
      'form_shipping_zz' => $form_shipping_zz,
    ));
  }

?>
