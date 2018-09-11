<?php

namespace MBLSolutions\Tests;

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
    public function can_decrypt_an_md5_string()
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

}