# oauth-server-bundle
OAuth Server Bundle

Symfony bundle which provides OAuth 2.0 authorization server capabilities. The authorization implemented using the [thephpleague/oauth2-server](https://github.com/thephpleague/oauth2-server) library.

## Installation
This component can be installed with the [Composer](https://getcomposer.org/) dependency manager.

1. [Install Composer](https://getcomposer.org/doc/00-intro.md)

2. Install the component as a dependency of your project

        composer require free2er/oauth-server-bundle

3. Enable the bundle

```php
<?php

// config/bundles.php
return [
    // ...
    Free2er\OAuth\OAuthServerBundle::class => ['all' => true],
    // ...
];
```

4. Configure the bundle parameters

```yml
# config/packages/oauth_server.yaml
o_auth_server:
    encryption_key: '%env(resolve:APP_SECRET)%'
    private_key: file://%kernel.project_dir%/path/to/private.key
    private_key_password: ~ 
    ttl:
        access_token: PT1H
        authorization_code: PT1H
        refresh_token: P1M
    grants:
        - League\OAuth2\Server\Grant\ClientCredentialsGrant
        - League\OAuth2\Server\Grant\PasswordGrant
        - League\OAuth2\Server\Grant\AuthCodeGrant
        - League\OAuth2\Server\Grant\RefreshTokenGrant
```

5. Configure the router

```yml
# config/routes.yaml
# ...
oauth:
    resource: '@OAuthServerBundle/Resources/config/routes.yaml'
    prefix: /auth
# ...
```

6. Update the project schema and register the client application

        bin/console doctrine:schema:update
        bin/console oauth:client:update my-client -g client_credentials

7. Done!
