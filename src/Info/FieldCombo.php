<?php

namespace Drupal\simpletac\Info;

/**
 * Object representing a combo of reference fields on user and node, and a
 * subset of the operations ['view', 'update', 'delete'].
 *
 * This is used within SimpletacInfo
 *
 * @see SimpletacInfo
 */
class FieldCombo {

  /**
   * @var int[]
   */
  private static $grantStubEmpty = array(
    'grant_view' => 0,
    'grant_update' => 0,
    'grant_delete' => 0,
  );

  /**
   * @var string[]
   *   Format: $[$op] = $op.
   *   Example: array('view' => 'view', 'update' => 'update')
   */
  private $ops = array();

  /**
   * @var string[]
   *   Format: array('grant_view' => 1, 'grant_update' => 1, 'grant_delete' => 0)
   */
  private $grantStub;

  /**
   * A taxonomy term reference or entity reference field on the user entity.
   *
   * @var \Drupal\simpletac\Field\EntityTypeFieldInterface
   */
  private $userField;

  /**
   * A taxonomy term reference or entity reference field on a node bundle.
   * (e.g. a node)
   *
   * @var \Drupal\simpletac\Field\EntityTypeFieldInterface
   */
  private $nodeField;

  /**
   * @param \Drupal\simpletac\Field\EntityTypeFieldInterface $userField
   * @param \Drupal\simpletac\Field\EntityTypeFieldInterface $nodeField
   * @param string[] $ops
   *   Format: array('view', 'delete')
   */
  function __construct($userField, $nodeField, array $ops) {
    $this->userField = $userField;
    $this->nodeField = $nodeField;
    $this->grantStub = static::$grantStubEmpty;
    foreach ($ops as $op) {
      $this->ops[$op] = $op;
      $this->grantStub['grant_' . $op] = 1;
    }
  }

  /**
   * @param object $user
   * @param string $op
   *
   * @return int[]
   *   Format: $[] = $grant_id
   */
  function getNodeGrants($user, $op) {

    if (!isset($this->ops[$op])) {
      return array();
    }

    // @todo Is this language-specific?
    $grants = array();
    foreach ($this->userField->getReferencedIds($user) as $etid) {
      $grants[] = $etid;
    }

    return $grants;
  }

  /**
   * @param object $node
   *
   * @return array[]
   *   Format: $[] = array(
   *     'grant_view' => 1,
   *     'grant_update' => 0,
   *     'grant_delete' => 0,
   *     'gid' => $tid,
   *   );
   */
  public function getNodeAccessRecords($node) {

    $grant = $this->grantStub;

    // @todo Is this language-specific?
    $grants = array();
    foreach ($this->nodeField->getReferencedIds($node) as $etid) {
      $grant['gid'] = $etid;
      $grants[] = $grant;
    }

    return $grants;
  }

}
