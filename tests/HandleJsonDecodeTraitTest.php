<?php

namespace MBLSolutions\Tests;

use MBLSolutions\Traits\HandleJsonDecode;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class HandleJsonDecodeTraitTest extends TestCase
{
    use HandleJsonDecode;
    
    /** @test **/
    public function receive_array_if_string_json_decoded_correctly()
    {
        $json = '{"boolean": true}';

        $this->assertEquals(['boolean' => true], $this->decodeJson($json));
    }

    /** @test **/
    public function receive_exception_if_string_did_not_decode_correctly()
    {
        $this->expectException(RuntimeException::class);

        $json = 'not-json';

        $this->decodeJson($json);
    }

}