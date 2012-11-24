<?php

class cqValidatorFile extends sfValidatorFile
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('mime_categories', array(
      'web_images' => array(
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
        'image/gif',
      ),
      'cq_supported_images' => array(
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
        'image/gif',
        'image/bmp',
        'image/x-bmp',
        'image/x-bitmap',
        'image/x-xbitmap',
        'image/x-win-bitmap',
        'image/x-windows-bmp',
        'image/ms-bmp',
        'image/x-ms-bmp',
        'application/bmp',
        'application/x-bmp',
        'application/x-win-bitmap'
      ),
      'cq_supported_multimedia' => array(
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
        'image/gif',
        'image/bmp',
        'image/x-bmp',
        'image/x-bitmap',
        'image/x-xbitmap',
        'image/x-win-bitmap',
        'image/x-windows-bmp',
        'image/ms-bmp',
        'image/x-ms-bmp',
        'application/bmp',
        'application/x-bmp',
        'application/x-win-bitmap',
        'video/x-flv',
        'application/octet-stream'
      ))
    );
  }
}
