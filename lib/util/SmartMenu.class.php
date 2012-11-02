<?php

/**
 * SmartMenu is an easy to configure menu helper class that builds navigation
 * from standard app configuration
 *
 * @author     Ivan Plamenov Tanev aka Crafty_Shadow
 */
class SmartMenu
{
  protected static $selected = null;

  /**
   * Generate a menu from app.yml configuration
   *
   * @param     string $menu_name
   * @param     array $extra_items Extra items to be added to the menu;
   *                               Can override existing items
   * @return    string
   */
  public static function generate($menu_name, $extra_items = array())
  {
    // app_smart_menus_%MenuName%_template
    $template = self::getAppMenuData($menu_name, 'template');
    $output = '';

    foreach (self::getItems($menu_name, $extra_items) as $id => $item)
    {
      // We want to lock some nav items with gatekeeper
      if (isset($item['check_lock']) && IceGateKeeper::locked($item['check_lock']))
      {
        continue;
      }

      if (isset($item['check_eval']) && $item['check_eval'])
      {
        if (!eval('return ' . $item['check_eval'] . ';'))
        {
          continue;
        }
      }

      // Do we have an icon defined?
      $icon = isset($item['icon']) ? sprintf('<i class="icon icon-%s"></i> ', $item['icon']) : '';

      if (isset($item['uri']))
      {
        $rel = 'sm_'. $menu_name;

        if (sfContext::getInstance()->isHomePage())
        {
          $rel = 'hp_' . $rel;
        }

        $url = cq_url_for(
          $item['uri'],
          array('ref' => $rel),
          isset($item['absolute']) ? $item['absolute'] : true
        );
      }

      // Set template values, defaults when none provided
      $item = array(
        '%id%'    => $id,
        '%name%'  => $icon . $item['name'],
        '%title%' => isset($item['title']) ? $item['title'] : strip_tags($item['name']),
        '%url%'   => isset($url) ? $url : '#',

        // https://developer.mozilla.org/en/HTML/Element/a#attr-target
        '%target%'=> isset($item['target']) ? $item['target'] : '_self',
      );

      if (self::isSelected($menu_name, $item))
      {
        $output .= strtr(is_array($template) ? $template['active'] : $link_template, $item)."\n";
      }
      else
      {
        $output .= strtr(is_array($template) ? $template['normal'] : $link_template, $item)."\n";
      }
    }

    return $output;
  }

  /**
   * Set the selected item for a specific menu
   *
   * @param     string $menu_name
   * @param     string $selected
   *
   * @return    void
   */
  public static function setSelected($menu_name, $selected)
  {
    self::$selected[$menu_name] = $selected;
  }

  /**
   * Gets the selected item for a specific menu
   *
   * @param     string $menu_name
   * @return    string
   */
  public static function getSelected($menu_name)
  {
    return isset(self::$selected[$menu_name]) ? self::$selected[$menu_name] : false;
  }

  /**
   * Return a sf cache key, either for a specific menu or for all menus.
   *
   * The cache key is based on which element is selected (either for all
   * menus or for a specific menu)
   *
   * @param     string $menu_name
   * @return    string
   */
  public static function getCacheKey($menu_name = null)
  {
    return md5(var_export(
      isset($menu_name) && isset(self::$selected[$menu_name])
        ? self::$selected[$menu_name]
        : self::$selected
    , true));
  }

  /**
   * Get either the complete data array for a menu, or a specific key
   *
   * @param     string $menu_name
   * @param     string $key
   *
   * @return    mixed
   * @throws    Exception If the menu does not exist in app.yml
   */
  public static function getAppMenuData($menu_name, $key = null)
  {
    $menu_data = array_merge(
      sfConfig::get('app_smart_menus_defaults', array()),
      sfConfig::get('app_smart_menus_'.$menu_name, array())
    );

    if (empty($menu_data))
    {
      throw new Exception(sprintf('SmartMenus: The menu "%s" doesn\'t exist.', $menu_name));
    }

    return null === $key ? $menu_data : $menu_data[$key];
  }

  /**
   * Try to return
   *
   * @param     string $menu_name
   * @param     array $extra_items Extra items to be added to the result;
   *                               Can overwrite existing items
   * @return    array
   *
   * @throws    Exception If the menu doesn't have any items set
   */
  protected static function getItems($menu_name, $extra_items = array())
  {
    $menu_data = self::getAppMenuData($menu_name);

    if (!isset($menu_data['items']) && empty($extra_items))
    {
      throw new Exception(sprintf('SmartMenus: The menu "%s" doesn\'t have any items set.', $menu_name));
    }

    return array_merge(
      isset($menu_data['items']) ? $menu_data['items'] : array(),
      $extra_items
    );
  }

  /**
   * Check if a specific item is set as selected for a menu
   *
   * @param     string $menu_name
   * @param     array $item
   *
   * @return    boolean
   */
  protected static function isSelected($menu_name, $item)
  {
    // no menu item is selected at all
    if (!isset(self::$selected[$menu_name]))
    {
      return false;
    }

    // check against menu item id, better for multi-language sites
    if ($item['%id%'] == self::$selected[$menu_name])
    {
      return true;
    }

    // check against menu item name. Not the best idea, but leave it as an option
    if (mb_strtolower($item['%name%'], 'utf-8') == mb_strtolower((string)self::$selected[$menu_name], 'utf-8'))
    {
      return true;
    }

    return false;
  }

}
