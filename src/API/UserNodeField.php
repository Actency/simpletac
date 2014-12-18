<?php

namespace Drupal\simpletac\API;

/**
 * An intermediate object for method chaining in hook_simpletac()
 */
class UserNodeField {

  /**
   * @var bool[]
   *   Format: $[$op] = TRUE
   */
  private $ops;

  /**
   * @param bool[] $ops
   *   Format: $[$op] = TRUE
   *   This array will typically be empty on construction.
   */
  function __construct(array &$ops) {
    $this->ops =& $ops;
  }

  /**
   * Grants view access on nodes that reference at least one target entity
   * that is also referenced by the user, with the fields specified before.
   *
   * @return $this
   */
  function view() {
    $this->ops['view'] = TRUE;
    return $this;
  }

  /**
   * Grants update access on nodes that reference at least one target entity
   * that is also referenced by the user, with the fields specified before.
   *
   * @return $this
   */
  function update() {
    $this->ops['update'] = TRUE;
    return $this;
  }

  /**
   * Grants delete access on nodes that reference at least one target entity
   * that is also referenced by the user, with the fields specified before.
   *
   * @return $this
   */
  function delete() {
    $this->ops['delete'] = TRUE;
    return $this;
  }
}
