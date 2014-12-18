<?php

namespace Drupal\simpletac\Info;

/**
 * An object containing the info collected from hook_simpletac(), joined with
 * info from field_info_fields().
 *
 * This is used for simpletac's implementations of hook_node_grants() and
 * hook_node_access_records()
 */
class SimpletacInfo {

  /**
   * @var FieldCombo[]
   *   Format: $[$realm] = $fieldCombo.
   */
  private $fieldCombos;

  /**
   * @param FieldCombo[] $fieldCombos
   */
  function __construct(array $fieldCombos) {
    $this->fieldCombos = $fieldCombos;
  }

  /**
   * @param object $user
   * @param string $op
   *
   * @return int[][]
   *   Format: $[$realm][] = $tid
   *
   * @see hook_node_grants()
   * @see simpletac_node_grants()
   */
  function getNodeGrants($user, $op) {

    $grants = array();
    foreach ($this->fieldCombos as $realm => $fieldCombo) {
      $grants[$realm] = $fieldCombo->getNodeGrants($user, $op);
    }

    return $grants;
  }

  /**
   * @param object $node
   *
   * @return array[]
   *   Format: $[] = array(
   *     'realm' => 'simpletac:field_user_term:field_node_term',
   *     'gid' => 123,
   *     'grant_view' => 1,
   *     'grant_update' => 0,
   *     'grant_delete' => 0,
   *     'priority' => 0,
   *   );
   *
   * @see hook_node_access_records()
   * @see simpletac_node_access_records()
   */
  function getNodeAccessRecords($node) {

    if (empty($node->status)) {
      // Node is unpublished, so we don't allow every group member to see
      // it.
      return array();
    }

    $grants = array();
    foreach ($this->fieldCombos as $realm => $fieldCombo) {
      foreach ($fieldCombo->getNodeAccessRecords($node) as $grant) {
        // Normalize some of the values.
        $grant['realm'] = $realm;
        $grant['priority'] = 0;
        $grants[] = $grant;
      }
    }

    return $grants;
  }
}
