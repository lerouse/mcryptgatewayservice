<?php

namespace MBLSolutions\Traits;

trait HandleBase64
{

    /**
     * Check if a String is Base64 Encoded
     *
     * @param string $string
     * @return bool
     */
    public function isBase64Encoded(string $string) : bool
    {
        if (base64_encode(base64_decode($string)) === $string) {
            return true;
        }

        return false;
    }

}