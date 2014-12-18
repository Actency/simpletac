<?php

namespace Drupal\simpletac\Field;

/**
 * Interface for handlers that can read a referenced entity ID from an entity.
 *
 * These handlers need to know the entity type of the referencing entity.
 *
 * @see EntityReference
 * @see TermReference
 */
interface EntityTypeFieldInterface {

  /**
   * @param object $entity
   *   An entity of the type assumed for referencing entities.
   *
   * @return int[]
   *   The entity IDs referenced from the field.
   */
  public function getReferencedIds($entity);

  /**
   * @return string
   *   The entity type that is being referenced, e.g. 'taxonomy_term' or 'node'.
   */
  public function getTargetType();
}
