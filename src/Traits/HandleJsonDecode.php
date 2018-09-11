<?php

namespace MBLSolutions\Traits;

use RuntimeException;

trait HandleJsonDecode
{

    /**
     * Decode Json
     *
     * @param string $string
     * @return mixed
     * @throws RuntimeException
     */
    public function decodeJson(string $string) : array
    {
        $result = json_decode($string, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }

        return $result;
    }

}