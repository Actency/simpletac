
# Simple TAC (Simple Taxonomy Access Control)

An access control module based on taxonomy term reference fields.

A user can see (or update, or delete) a node, if there is a taxonomy term that
is referenced both by the user and by the node.

This is an API module, it has no admin UI. You need to use hook_simpletac() to
tell the module which fields to connect, and which operations are affected:
'view', 'update' or 'delete'. 'create' is not supported, because this is
not associated with an existing node.


## Example

A term reference field named `field_user_term` on the user entity.

A term reference field named `field_node_term` on one or more node types.

Both of them target the same vocabulary.

A term "Heal the world" in this vocabulary, tid = 5.

If both `$user->field_user_term` and `$node->field_node_term` reference this
term (or another term), then the user can see the node.


## Usage

You need to implement `hook_simpletac()`. See the simpletac.api.php, or the
example module.

A good place for these hook implementations is in your feature modules.

You may also need to rebuild permissions (`admin/reports/status/rebuild`).


## Caveat

A node that references no terms is always visible - unless other access control
modules define access records for this node. This is simply how
`node_access_acquire_grants()` works, nothing we can do about it.

The OG equivalent would be: A document that is not in any group is visible to
everyone.
