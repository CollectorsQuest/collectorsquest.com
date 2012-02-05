<?php

class cqBreadcrumbsItem
{
  protected $text;
  protected $uri;
  protected $options;

  /**
   * Constructor
   *
   */
  public function __construct($text, $uri = null, $options = array())
  {
    $this->text = $text;
    $this->uri = $uri;
    $this->options = $options;
  }

  /**
   * Retrieve the uri of the item
   *
   */
  public function getUri()
  {
    return $this->uri;
  }

  /**
   * Retrieve the text of the item
   *
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Retrieve the options of the item
   *
   */
  public function getOptions()
  {
    return $this->options;
  }
}

class cqBreadcrumbs
{
  static    $instance = null;
  protected $items    = array();

  /**
   * Constructor
   *
   */
  public function __construct()
  {
    $this->setRoot('Home', '@homepage');
  }

  /**
   * Add an item
   *
   * @param string $text
   * @param string $uri
   */
  public function addItem($text, $uri = null, $options = array())
  {
    array_push($this->items, new cqBreadcrumbsItem($text, $uri, $options));
    $this->save();
  }

  /**
   * Delete all existings items
   *
   */
  public function clearItems()
  {
    $this->items = array();
    $this->save();
  }

  /**
   * Get the unique cqBreadcrumbs instance (singleton)
   *
   * @return cqBreadcrumbs
   */
  public static function getInstance()
  {
    if (is_null(self::$instance))
    {
      if (!self::$instance = sfContext::getInstance()->getRequest()->getParameter('cqBreadcrumbs'))
      {
        self::$instance = new cqBreadcrumbs();
        self::$instance->save();
      }
    }

    return self::$instance;
  }

  /**
   * Retrieve an array of cqBreadcrumbsItem
   *
   * @param int $offset
   */
  public function getItems($offset = 0)
  {
    return array_slice($this->items, $offset);
  }

  /**
   * Redefine the root item
   *
   */
  public function setRoot($text, $uri)
  {
    $this->items[0] = new cqBreadcrumbsItem($text, $uri);
    $this->save();
  }

  /**
   * Save cqBreadcrumbs instance as response parameter (allows action caching)
   *
   */
  protected function save()
  {
    sfContext::getInstance()->getRequest()->setParameter('cqBreadcrumbs', $this);
  }
}
