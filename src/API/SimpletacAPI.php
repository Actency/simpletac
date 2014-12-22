<?php

namespace Drupal\simpletac\API;

/**
 * An object that is passed to hook_simpletac(), as a starting point for method
 * chaining.
 */
class SimpletacAPI {

  /**
   * @var bool[][][]
   *   Format: $[$userFieldName][$nodeFieldName][$op] = TRUE
   */
  private $info;

  /**
   * @param bool[][][] $info
   *   Format: $[$userFieldName][$nodeFieldName][$op] = TRUE
   *   This array will typically be empty on construction.
   */
  function __construct(array &$info) {
    $this->info =& $info;
  }

  /**
   * Specifies the field to use on the user entity.
   *
   * @param string $fieldName
   *   The name of a term reference or entityreference field.
   *
   * @return \Drupal\simpletac\API\UserField
   * @throws \Exception
   */
  function userField($fieldName) {
    if (!isset($this->info[$fieldName])) {
      $this->info[$fieldName] = array();
    }
    return new UserField($this->info[$fieldName]);
  }
}
