<?php

namespace MBLSolutions\Tests;

use MBLSolutions\Exceptions\ResponseInvalidException;
use MBLSolutions\McryptService;
use PHPUnit\Framework\TestCase;

class McryptServiceTest extends TestCase
{
    /** @var McryptService $mcryptService */
    private $mcryptService;

    /** {@inheritdoc} **/
    public static function setUpBeforeClass()
    {
        if (file_exists(__DIR__.'/../.env')) {
            $dotenv = new \Dotenv\Dotenv(__DIR__.'/../');
            $dotenv->load();
        }
    }

    /** {@inheritdoc} **/
    public function setUp()
    {
        $this->mcryptService = new McryptService(getenv('MCRYPT_SERVICE_ENDPOINT'), getenv('MCRYPT_SERVICE_STAGE'));
    }

    /** @test **/
    public function response_containing_error_throws_validation_exception()
    {
        $this->expectException(ResponseInvalidException::class);

        $encrypted = '0sQg7vz6S9g='; // password
        $secret = 'x';

        $this->mcryptService->decrypt($encrypted, $secret);
    }

    /** @test **/
    public function can_decrypt_an_mcrypt_encrypted_string()
    {
        $encrypted = '0sQg7vz6S9g='; // password
        $secret = 'dGhpc2lzYXR3ZW50eWZvdXJjaGFya2V5'; // thisisatwentyfourcharkey

        $data = $this->mcryptService->decrypt($encrypted, $secret);

        // The base 64 Representation of the unencrypted string
        $this->assertEquals('cGFzc3dvcmQ=', $data);
    }

    /** @test **/
    public function decrypted_string_returns_a_base64_encoded_string()
    {
        $encrypted = '0sQg7vz6S9g='; // password
        $secret = 'dGhpc2lzYXR3ZW50eWZvdXJjaGFya2V5'; // thisisatwentyfourcharkey

        $data = $this->mcryptService->decrypt($encrypted, $secret);

        $decoded = base64_decode($data);

        // The base 64 Representation of the unencrypted string
        $this->assertEquals('password', $decoded);
    }

    /** @test */
    public function can_encrypt_a_string()
    {
        $string = base64_encode('password');
        $secret = base64_encode('thisisatwentyfourcharkey'); // thisisatwentyfourcharkey

        $data = $this->mcryptService->encrypt($string, $secret);

        $this->assertEquals('0sQg7vz6S9g=', $data);
    }

}