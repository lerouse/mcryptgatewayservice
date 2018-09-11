# MBL Solutions Mcrypt Gateway Service

Service to interact with the MBL Solutions Mcrypt Gateway Service.

Allows versions of PHP to use decrypt Mcrypt encrypted strings.

## Installation with Composer

``` composer require vlucas/phpdotenv ```

## Usage

Data passed in should be bse64 encoded

### Decryption

```php
$mcryptService = new MBLSolutions\McryptService('https://example.execute-api.eu-west-1.amazonaws.com', 'production');

$encrypted = base64_encode('password'); // password
$secret = base64_encode('thisisatwentyfourcharkey');

$result = $mcryptService->decrypt($encrypted, $secret); // Decrypts into cGFzc3dvcmQ=

base64_decode($result); // password
```