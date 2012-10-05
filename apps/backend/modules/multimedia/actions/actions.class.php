<?php

require_once dirname(__FILE__).'/../lib/multimediaGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/multimediaGeneratorHelper.class.php';

/**
 * multimedia actions.
 *
 * @package    CollectorsQuest
 * @subpackage multimedia
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class multimediaActions extends autoMultimediaActions
{
  /**
   * @param  sfWebRequest  $request
   * @return string
   */
  public function executeCrop(sfWebRequest $request)
  {
    /* @var $multimedia iceModelMultimedia */
    $this->multimedia = $this->getRoute()->getObject();

    $this->response->addJavascript ('jquery/imgareaselect.js', 'last');
    $this->response->addJavascript ('jquery/showLoading.js', 'last');
    $this->response->addStylesheet ('backend/jquery.imageselect/imgareaselect-default.css', 'last');

    return sfView::SUCCESS;
  }

  public function executeCropUpdate (sfWebRequest $request)
  {
    /* @var $multimedia iceModelMultimedia */
    $multimedia = $this->getRoute()->getObject();

    /* @var cqApplicationConfiguration $configuration */
    $configuration = sfProjectConfiguration::getActive();
    $configuration->loadHelpers(array('cqImages'));

    // where the top left corner of the cropping starts
    $x1 = $request->getParameter ('x1');
    $y1 = $request->getParameter ('y1');

    // width and height of crop area
    $w = $request->getParameter ('width');
    $h = $request->getParameter ('height');

    $model = $multimedia->getModel();

    // @todo generate proper thumbnails based on $x1, $y1, $w and $h
    if ($model == 'Collectible')
    {
      $multimedia->makeThumb(150, 150, 'top', false);
      $multimedia->makeCustomThumb(190, 190, '190x190', 'top', false);
      $multimedia->makeCustomThumb(620, 0, '620x0', 'resize', false);
      $multimedia->makeCustomThumb(75, 75, '75x75', 'top', false);
      $multimedia->makeCustomThumb(190, 150, '190x150', 'top', false);
      $multimedia->makeCustomThumb(260, 205, '260x205', 'top', false);
    }
    else if ($model == 'Collection')
    {
      $multimedia->makeThumb(150, 150, 'top', false);
      $multimedia->makeCustomThumb(50, 50, '50x50', 'top', false);
      $multimedia->makeCustomThumb(190, 150, '190x150', 'top', false);
      $multimedia->makeCustomThumb(190, 190, '190x190', 'top', false);
    }
    else if ($model == 'Collector')
    {
      $multimedia->makeThumb(100, 100, 'center', false);
      $multimedia->makeCustomThumb(235, 315, '235x315', 'top', false);
    }

    $this->multimedia = $multimedia;
    $this->model = $model;

   /* $large_image_location = src_tag_multimedia($this->multimedia, 'original');
    $thumb_image_location = src_tag_multimedia($this->multimedia, 'thumbnail');

    $this->cropped = $this->resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);*/
  }

  /*private function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
    $source = imagecreatefromjpeg($image);
    imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
    imagejpeg($newImage,$thumb_image_name,90);

    return $thumb_image_name;
  }*/
}
