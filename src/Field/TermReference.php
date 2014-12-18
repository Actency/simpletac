<?php

namespace Drupal\simpletac\Field;

/**
 * Field adapter for taxonomy_term_reference fields.
 */
class TermReference implements EntityTypeFieldInterface {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @param string $entityType
   * @param string $fieldName
   */
  function __construct($entityType, $fieldName) {
    $this->entityType = $entityType;
    $this->fieldName = $fieldName;
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
      $etids[] = $item['tid'];
    }

    return $etids;
  }

  /**
   * @return string
   */
  public function getTargetType() {
    return 'taxonomy_term';
  }
}
