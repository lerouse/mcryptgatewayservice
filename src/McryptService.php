<?php

namespace MBLSolutions;

use GuzzleHttp\Psr7\Response;
use MBLSolutions\Exceptions\ResponseInvalidException;
use MBLSolutions\Traits\HandleBase64;
use GuzzleHttp\Client as GuzzleClient;
use MBLSolutions\Traits\HandleJsonDecode;

/**
 * Class McryptService
 * @package MBLSolutions
 */
class McryptService
{
    use HandleBase64, HandleJsonDecode;

    /** @var string $endpoint */
    private $endpoint;

    /** @var string $stage */
    private $stage;

    /** @var GuzzleClient $client */
    private $client;

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
     * @return string
     * @throws ResponseInvalidException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function decrypt(string $data, string $secret): string
    {
        $response = $this->makeGetRequest($data, $secret);

        return $this->getResponse($response, 'data');
    }

    /**
     * Encrypt Data using Mcrypt
     *
     * @param string $data
     * @param string $secret
     * @return string
     * @throws ResponseInvalidException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function encrypt(string $data, string $secret): string
    {
        $response = $this->makePostRequest($data, $secret);

        return $this->getResponse($response, 'data');
    }

    /**
     * Call the API Gateway
     *
     * @param string $data
     * @param string $secret
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function makeGetRequest(string $data, string $secret): Response
    {
        return $this->getGuzzleClient()->request('GET', "/{$this->stage}?data={$data}&secret={$secret}", []);
    }

    /**
     * Call the API Gateway
     *
     * @param string $data
     * @param string $secret
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function makePostRequest(string $data, string $secret): Response
    {
        $request = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'data' => $data,
                'secret' => $secret,
            ]
        ];

        return $this->getGuzzleClient()->request('POST', "/{$this->stage}", $request);
    }

    /**
     * Get the Guzzle Client
     *
     * @return GuzzleClient
     */
    private function getGuzzleClient(): GuzzleClient
    {
        if (is_null($this->client)) {
            $this->client = new GuzzleClient([
                'base_uri' => $this->endpoint,
                'http_errors' => false,
            ]);
        }

        return $this->client;
    }

    /**
     * Validate and return the expected response data
     *
     * @param Response $response
     * @param string $key
     * @return string
     * @throws ResponseInvalidException
     */
    public function getResponse(Response $response, string $key = 'data'): string
    {
        $originalResponse = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() != 200) {
            throw new ResponseInvalidException($originalResponse['error'] ?? $response->getReasonPhrase());
        }

        if (!isset($originalResponse[$key])) {
            throw new ResponseInvalidException('Could not find index ' . $key);
        }

        return $originalResponse[$key];
    }

}