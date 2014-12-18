<?php

namespace Drupal\simpletac\Field;

/**
 * Field adapter for entityreference fields.
 */
class EntityReference implements EntityTypeFieldInterface {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string
   */
  private $targetType;

  /**
   * @param string $entityType
   * @param string $fieldName
   * @param string $targetType
   */
  function __construct($entityType, $fieldName, $targetType) {
    $this->entityType = $entityType;
    $this->fieldName = $fieldName;
    $this->targetType = $targetType;
  }

  /**
   * @param object $entity
   *
   * @return int[]
   */
  public function getReferencedIds($entity) {

    // @todo Is this language-specific?
    $etids = array();
    foreach (field_get_items($this->entityType, $entity, $this->fieldName) ?: array() as $item) {
      $etids[] = $item['entity_id'];
    }

    return $etids;
  }

  /**
   * @return string
   */
  public function getTargetType() {
    return $this->targetType;
  }
}
