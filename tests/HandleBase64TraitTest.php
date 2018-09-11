<?php

namespace MBLSolutions\Tests;

use MBLSolutions\Traits\HandleBase64;
use PHPUnit\Framework\TestCase;

class HandleBase64TraitTest extends TestCase
{
    use HandleBase64;
    
    /** @test **/
    public function receive_true_if_string_is_base_64_encoded()
    {
        $string = 'MTIzNDU=';

        $this->assertTrue($this->isBase64Encoded($string));
    }

    /** @test **/
    public function receive_false_if_string_is_not_base_64_encoded()
    {
        $string = '12345';

        $this->assertFalse($this->isBase64Encoded($string));
    }

}