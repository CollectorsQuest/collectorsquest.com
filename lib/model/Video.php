<?php

class Video extends BaseVideo
{
  public function __toString()
  {
    return $this->getTitle();
  }

  public function getLengthFormatted()
  {
    $seconds = $this->getLength();
    return sprintf("%02.2d:%02.2d", floor($seconds / 60), $seconds % 60);
  }

  public function getPublished()
  {
    sfLoader::loadHelpers("Asset");

    if($this->getIsPublished())
    {
      return $this->getPublishedAt('m/d/y');
    }
    else
    {
      return 'Not published';
    }
  }

  public function getTagsString()
  {
    return implode(', ', $this->getTags());
  }

  public function getFilenameSrc()
  {
    return sfConfig::get('app_video_dir').'/flv/'.$this->getFilename();
  }

  public function getThumbSmallSrc($rand = false)
  {
    return sfConfig::get('app_cq_www_domain') .'/uploads/videos/thumbs/'.$this->getThumbSmall() . (($rand) ? '?'.rand(1, 100000) : '');
  }

  public function getThumbLargeSrc($rand = false)
  {
    return sfConfig::get('app_cq_www_domain') .'/uploads/videos/thumbs/'.$this->getThumbLarge() . (($rand) ? '?'.rand(1, 100000) : '');
  }

  public function getTagRelatedVideos($limit = 5, Criteria $criteria = null)
  {
    if (!$tags = $this->getTags())
    {
      return null;
    }

    $c = !($criteria instanceof Criteria) ? new Criteria() : clone $criteria;

    $c->setDistinct();
    $c->addJoin(VideoPeer::ID, iceModelTaggingPeer::TAGGABLE_ID);
    $c->addJoin(iceModelTaggingPeer::TAG_ID, iceModelTagPeer::ID);
    $c->add(VideoPeer::ID, $this->getId(), Criteria::NOT_EQUAL);
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Video');
    $c->add(iceModelTagPeer::NAME, $tags, Criteria::IN);
    $c->addDescendingOrderByColumn(VideoPeer::PUBLISHED_AT);
    $c->setLimit($limit);

    return VideoPeer::doSelect($c);
  }

  public function getLooselyRelatedVideos($limit = 5, Criteria $criteria = null)
  {
    if (!($criteria instanceof Criteria)) {
      $c= new Criteria();
    } else {
      $c = clone $criteria;
    }

    $c->setDistinct();
    $c->add(VideoPeer::IS_PUBLISHED, true);
    $c->addDescendingOrderByColumn(VideoPeer::PUBLISHED_AT);
    $c->setLimit($limit);

    return VideoPeer::doSelect($c);
  }

  public function generateThumbnails($offset = 25)
  {
    $video_file = $this->getFilename();
    $thumb_small = str_replace('.flv', '_front.jpg', $video_file);
    $thumb_large = str_replace('.flv', '.jpg', $video_file);

    $this->setThumbSmall($thumb_small);
    $this->setThumbLarge($thumb_large);
    $this->save();

    $dir = sfConfig::get('app_server_vhosts_dir').'/web/'.sfConfig::get('app_video_dir');
    $cmd = sprintf("ffmpeg -i '%s' -an -ss 00:%d:%d -an -r 1 -s 94x74 -vframes 1 -y -pix_fmt rgb24 %s/temp%%d.jpg", sfConfig::get('app_server_vhosts_dir').'/web'.$this->getFilenameSrc(true), $offset / 60, $offset % 60, $dir);
    exec($cmd);
    copy($dir.'/temp1.jpg', sfConfig::get('app_server_vhosts_dir').'/web'.$this->getThumbSmallSrc());
    unlink($dir.'/temp1.jpg');

    $cmd = sprintf("ffmpeg -i '%s' -an -ss 00:%d:%d -an -r 1 -s 480x360 -vframes 1 -y -pix_fmt rgb24 %s/temp%%d.jpg", sfConfig::get('app_server_vhosts_dir').'/web'.$this->getFilenameSrc(true), $offset / 60, $offset % 60, $dir);
    exec($cmd);
    copy($dir.'/temp1.jpg', sfConfig::get('app_server_vhosts_dir').'/web'.$this->getThumbLargeSrc());
    unlink($dir.'/temp1.jpg');
  }
}

sfPropelBehavior::add('Video', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'Video',
  array(
    'PropelActAsSluggableBehavior' => array(
      'columns' => array(
        'from' => VideoPeer::TITLE,
        'to' => VideoPeer::SLUG
      ),
      'separator' => '-',
      'permanent' => false,
      'ascii' => true,
      'chars' => 64
    )
  )
);
