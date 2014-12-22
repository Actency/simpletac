<?php

namespace Drupal\simpletac\API;

/**
 * An intermediate object for method chaining in hook_simpletac()
 */
class UserField {

  /**
   * @var bool[][]
   *   Format: $[$nodeFieldName][$op] = TRUE
   */
  private $info;

  /**
   * @param bool[][] $info
   *   Format: $[$nodeFieldName][$op] = TRUE
   *   This array will typically be empty on construction.
   */
  function __construct(array &$info) {
    $this->info =& $info;
  }

  /**
   * Specifies the field to use on the node entity.
   *
   * @param string $fieldName
   *   The name of a term reference or entityreference field.
   *
   * @return \Drupal\simpletac\API\UserNodeField
   */
  function nodeField($fieldName) {
    if (!isset($this->info[$fieldName])) {
      $this->info[$fieldName] = array();
    }
    return new UserNodeField($this->info[$fieldName]);
  }
}
