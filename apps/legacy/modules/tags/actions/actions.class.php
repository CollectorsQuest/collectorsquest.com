<?php

/**
 * tags actions.
 *
 * @package    CollectorsQuest
 * @subpackage tags
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class tagsActions extends cqActions
{
 /**
  * Executes index action
  *
  * @param  sfWebRequest $request A request object
  * @return string
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->tags = CollectiblePeer::getPopularTags(100);
    $this->tags = array_merge(CollectionPeer::getPopularTags(100), $this->tags);
    uksort($this->tags, "strcasecmp");

    $this->addBreadcrumb('Tag Cloud');
    $this->prependTitle('Tag Cloud');

    return sfView::SUCCESS;
  }

  public function executeTagCloud(sfWebRequest $request)
  {
    $which = $request->getParameter('which');

    if (empty($which))
    {
      $this->redirect('tag/index');
    }

    switch ($which)
    {
      case 'collections':
        $this->url = '@collections_by_tag?tag=';
        $this->tags = CollectionPeer::getPopularTags(sfConfig::get('app_tag_cloud_max'));
        break;
      case 'collectibles':
        $this->url = '@search?only=collectibles&q=';
        $this->tags = CollectiblePeer::getPopularTags(sfConfig::get('app_tag_cloud_max'));
        break;
      case 'cities':
        $this->url = '@search?only=collectors&q=';
        $this->tags = CollectorPeer::getCity2Tags(sfConfig::get('app_tag_cloud_max'));
        break;
      case 'states':
        $this->url = '@search?only=collectors&q=';
        $this->tags = CollectorPeer::getState2Tags(sfConfig::get('app_tag_cloud_max'));
        break;
      case 'countries':
        $this->url = '@search?only=collectors&q=';
        $this->tags = CollectorPeer::getCountry2Tags(sfConfig::get('app_tag_cloud_max'));
        break;
      default:
        $this->redirect404();
        break;
    }

    // Sort by name
    uksort($this->tags, "strcasecmp");

    $this->addBreadcrumb('Tag Clouds', '@tags_index');
    $this->addBreadcrumb(ucwords($which));

    $this->prependTitle('Tag Clouds');
    $this->prependTitle(ucwords($which));

    return sfView::SUCCESS;
  }
}
