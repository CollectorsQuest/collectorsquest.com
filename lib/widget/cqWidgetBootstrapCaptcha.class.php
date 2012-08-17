<?php

class cqWidgetBootstrapCaptcha extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('width');
    $this->addOption('height');
    $this->addOption('background-color');
    $this->addOption('font-size');
    $this->addOption('code-length', 5);
    $this->addOption('format', '%captcha% <span class="arrow-l-r"></span> %input%');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $vars['%captcha%'] =  $this->renderContentTag('a',
      $this->renderTag('img', array(
          'src' => $this->getCaptchaUrl()."?r=' + Math.random() + '&" . $this->getCaptchaParams(),
          'onClick' => "this.src=this.src.replace(/(.+r=)(.+)(&amp;w.+)/, '$1' + Math.random() + '$3' + '&reload=1')",
          'width' => $this->getOption('width'),
          'height' => $this->getOption('height'),
      )),
      array('onClick' => 'return false;', 'style' => 'text-decoration:none')
    );

    $attributes = array_merge($attributes, array(
        'class' => (isset($attributes['class']) ? $attributes['class'] : '')
                    . ' input-captcha',
        'type' => 'text',
        'name' => $name,
        'value' => $value,
        'maxlength' => $this->getOption('code-length'),
    ));
    $vars['%input%'] = $this->renderTag('input', $attributes);

    return strtr($this->getOption('format'), $vars);
  }

  protected function getCaptchaUrl()
  {
    return cqContext::getInstance()->getRouting()->generate(
      'ice_captcha_image',
      array(),
      true
    );
  }

  protected function getCaptchaParams()
  {
    return sprintf(
      'w=%d&h=%d&bc=%s&fs=%d&cl=%d',
      $this->getOption('width'), $this->getOption('height'),
      $this->getOption('background-color'), $this->getOption('font-size'),
      $this->getOption('code-length')
    );
  }
}
