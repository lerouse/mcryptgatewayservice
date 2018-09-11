<?php

namespace MBLSolutions;

use GuzzleHttp\Psr7\Response;
use MBLSolutions\Traits\HandleBase64;
use GuzzleHttp\Client as GuzzleClient;
use MBLSolutions\Traits\HandleJsonDecode;

class McryptService
{
    use HandleBase64, HandleJsonDecode;

    /** @var string $endpoint */
    private $endpoint;

    /** @var string $stage */
    private $stage;

    /**
     * MBL Solutions Mcrypt Gateway
     *
     * @param string $endpoint
     * @param string $stage
     */
    public function __construct(string $endpoint, string $stage = 'production')
    {
        $this->endpoint = $endpoint;
        $this->stage = $stage;
    }

    /**
     * Decrypt Data using Mcrypt
     *
     * @param string $data
     * @param string $secret
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function decrypt(string $data, string $secret) :? string
    {
        // Check if data is Base64 Encoded, if not encode it
        if ( !$this->isBase64Encoded($data) ) {
            $data = base64_encode($data);
        }

        // Check if secret is Base64 Encoded, if not encode it
        if ( !$this->isBase64Encoded($secret) ) {
            $secret = base64_encode($secret);
        }

        $response = $this->callApiGateway($data, $secret);

        return $response['data'];
    }

    /**
     * Call the API Gateway
     *
     * @param string $data
     * @param string $secret
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function callApiGateway(string $data, string $secret) : array
    {
        $client = new GuzzleClient(['base_uri' => $this->endpoint]);

        $response = $client->request('GET', "/{$this->stage}?data={$data}&secret={$secret}", []);

        return $this->handleResponse($response);
    }

    /**
     * Handle Guzzle Http Response
     *
     * @param Response $response
     * @return array
     */
    private function handleResponse(Response $response) : array
    {
        $contents = $response->getBody()->getContents();

        return $this->decodeJson($contents);
    }

}