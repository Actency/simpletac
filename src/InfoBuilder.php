<?php

namespace Drupal\simpletac;

use Drupal\simpletac\API\SimpletacAPI;
use Drupal\simpletac\Field\EntityReference;
use Drupal\simpletac\Field\TermReference;
use Drupal\simpletac\Info\FieldCombo;
use Drupal\simpletac\Info\SimpletacInfo;

/**
 * A helper to prepare the SimpletacInfo object from hook_simpletac() and
 * field_info_fields().
 */
class InfoBuilder {

  /**
   * @var array[]
   *   Format: $[$fieldName] = $fieldInfo.
   */
  private $fieldInfoFields;

  /**
   * @param array[] $fieldInfoFields
   *   Format: $[$fieldName] = $fieldInfo.
   *
   * @see field_info_fields()
   */
  function __construct($fieldInfoFields) {
    $this->fieldInfoFields = $fieldInfoFields;
  }

  /**
   * @return \Drupal\simpletac\Info\SimpletacInfo
   */
  function getInfo() {
    // @todo Cache this stuff.
    $info = $this->scanHookSimpletac();
    $fieldCombos = $this->buildFieldCombos($info);
    return new SimpletacInfo($fieldCombos);
  }

  /**
   * Runs hook_simpletac() to collect the info for field combos.
   *
   * @return bool[][][]
   *   Format: $[$userFieldName][$nodeFieldName][$op] = TRUE
   */
  private function scanHookSimpletac() {

    /**
     * @var bool[][][]
     *   Format: $[$userFieldName][$nodeFieldName][$op] = TRUE
     */
    $info = array();
    $api = new SimpletacAPI($info);
    module_invoke_all('simpletac', $api);

    return $info;
  }

  /**
   * Builds the field combos from info collected in hook_simpletac().
   *
   * @param array $info
   *
   * @return bool[][][]
   *   Format: $[$userFieldName][$nodeFieldName][$op] = TRUE
   *
   * @throws \Exception
   */
  private function buildFieldCombos(array $info) {
    $fieldCombos = array();
    foreach ($info as $userFieldName => $userFieldSettings) {
      $userField = $this->buildEntityTypeField('user', $userFieldName);
      $targetType = $userField->getTargetType();
      foreach ($userFieldSettings as $nodeFieldName => $ops) {
        $nodeField = $this->buildEntityTypeField('node', $nodeFieldName);
        if ($targetType !== $nodeField->getTargetType()) {
          throw new \Exception(format_string("Target type mismatch between '@userFieldName' and '@nodeFieldName'.", array(
            '@userFieldName' => $userFieldName,
            '@nodeFieldName' => $nodeFieldName,
          )));
        }
        $fieldCombos["simpletac:$userFieldName:$nodeFieldName"] = new FieldCombo($userField, $nodeField, array_keys($ops));
      }
    }
    return $fieldCombos;
  }

  /**
   * Builds a field adapter object for a given field on a node or user.
   *
   * @param string $entityType
   *   Should be either 'node' or 'user'.
   * @param string $fieldName
   *
   * @return \Drupal\simpletac\Field\EntityTypeFieldInterface
   * @throws \Exception
   */
  private function buildEntityTypeField($entityType, $fieldName) {

    if (!isset($this->fieldInfoFields[$fieldName])) {
      throw new \Exception(format_string("Unknown field '@field'.", array('@field' => $fieldName)));
    }
    $fieldInfo = $this->fieldInfoFields[$fieldName];

    switch ($fieldInfo['type']) {

      case 'entityreference':
        if (!isset($fieldInfo['target_type'])) {
          throw new \Exception(format_string("Field '@fieldName' does not specify a target type.", array('@fieldName' => $fieldName)));
        }
        return new EntityReference($entityType, $fieldName, $fieldInfo['target_type']);

      case 'taxonomy_term_reference':
        return new TermReference($entityType, $fieldName);

      default:
        throw new \Exception(format_string("Field type '@type' not supported.", array('@field_type' => $fieldInfo['type'])));
    }
  }

}
