- composer require bassoumi/acl
- register DartACLServiceProvider
- register auth middleware in app/bootstrap
- change driver in auth.php to dart-jwt
- change providers.users.model to DartUser
- add auth:api middleware to secured routes
- add env variable: DART_JWT_SECRET