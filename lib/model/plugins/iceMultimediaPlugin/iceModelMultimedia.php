<?php

class iceModelMultimedia extends PluginiceModelMultimedia
{

  /**
   * @param     string|array $colors
   * @return    iceModelMultimedia
   */
  public function setColors($colors)
  {
    if (is_array($colors))
    {
      $colors = implode(', ', $colors);
    }

    return parent::setColors($colors);
  }

  /**
   * @param     string  $return
   * @return    array
   */
  public function getColors($return = 'string')
  {
    $colors = parent::getColors();

    if ($return === 'array')
    {
      $colors = explode(', ', parent::getColors());
      $colors = array_filter($colors);
    }

    return $colors;
  }

  /**
   * Disallow changing the created_at field for existing objects
   *
   * @param     mixed $v
   * @return    iceModelMultimedia
   *
   * @throws    RuntimeException
   * @see       BaseiceModelMultimedia::setCreatedAt()
   */
  public function setCreatedAt($v)
  {
    if ($this->isNew())
    {
      return parent::setCreatedAt($v);
    }
    else
    {
      throw new RuntimeException(
        '[iceModelMultimedia] you cannot change the created_at field for existing Multimedia records'
      );
    }
  }

}
