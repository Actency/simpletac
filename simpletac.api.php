<?php

use Drupal\simpletac\API\SimpletacAPI;

/**
 * Implements hook_simpletac()
 *
 * @param \Drupal\simpletac\API\SimpletacAPI $api
 */
function hook_simpletac(SimpletacAPI $api) {
  $api->userField('field_user_term')->nodeField('field_categories')
    ->view()
    ->update()
    ->delete()
  ;
}
