<?php

namespace Laravel\Passport;

/**
 * @method static void routes(callable|null $callback = null, array $options = [])
 * @method static void tokensExpireIn(\DateTimeInterface $date)
 * @method static void refreshTokensExpireIn(\DateTimeInterface $date)
 * @method static void personalAccessTokensExpireIn(\DateTimeInterface $date)
 * @method static void tokensCan(array $abilities)
 * @method static void setDefaultScope(string $scope)
 * @method static void enableImplicitGrant()
 * @method static void usePersonalAccessClientId()
 * @method static void loadKeysFrom(string $path)
 * @method static void loadPrivateKeyFrom(string $path)
 * @method static void loadPublicKeyFrom(string $path)
 * @method static void ignoreMigrations()
 * @method static void useTokenModel(string $class)
 * @method static void useClientModel(string $class)
 * @method static void useAuthCodeModel(string $class)
 * @method static void usePersonalAccessClientModel(string $class)
 * @method static void useRefreshTokenModel(string $class)
 * @method static array scopes()
 */
class Passport
{
}

/**
 * Résultat retourné par createToken()
 *
 * @property string $accessToken
 * @property \Laravel\Passport\Token $token
 */
class PersonalAccessTokenResult
{
}

/**
 * Jeton personnel généré par Passport
 *
 * @property string $id
 * @property string $user_id
 * @property string $client_id
 * @property string $name
 * @property array $scopes
 * @property bool $revoked
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method bool tokenCan(string $scope)
 * @method void revoke()
 */
class Token
{
}

/**
 * Client OAuth2 (par ex. SPA, mobile app, etc.)
 *
 * @property string $id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Client
{
}
