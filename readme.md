# MBL Solutions Mcrypt Gateway Service

[![CircleCI](https://circleci.com/gh/mblsolutions/mcryptgatewayservice.svg?style=svg)](https://circleci.com/gh/mblsolutions/mcryptgatewayservice)

Package to interact with the MBL Solutions Mcrypt Gateway Service.

Allows versions of PHP that do not support mcrypt_encrypt and mcrypt_decrypt to handle Mcrypt encryption and decryption.

## Installation with Composer

``` composer require mblsolutions/mcryptgatewayservice ```

## Usage

Data passed in should be base64 encoded

### Encryption

```php
$mcryptService = new MBLSolutions\McryptService('https://example.execute-api.eu-west-1.amazonaws.com', 'prod');

$string = base64_encode('password'); // Encrypted base64 encoded string `password`
$secret = base64_encode('thisisatwentyfourcharkey'); // encryption secret

$result = $mcryptService->encrypt($string, $secret); // Encrypts into 0sQg7vz6S9g=
```

### Decryption

```php
$mcryptService = new MBLSolutions\McryptService('https://example.execute-api.eu-west-1.amazonaws.com', 'prod');

$encrypted = '0sQg7vz6S9g='; // Encrypted base64 encoded string `password`
$secret = base64_encode('thisisatwentyfourcharkey'); // encryption secret

$result = $mcryptService->decrypt($encrypted, $secret); // Decrypts into cGFzc3dvcmQ=

base64_decode($result); // password
```