<?php

namespace MBLSolutions;

use Exception;
use GuzzleHttp\Psr7\Response;
use MBLSolutions\Exceptions\RequestInvalidException;
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
     * @return string|null
     * @throws RequestInvalidException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function decrypt(string $data, string $secret) :? string
    {
        $response = $this->makeGetRequest($data, $secret);

        return $this->getResponse($response, 'data');
    }

    /**
     * Encrypt Data using Mcrypt
     *
     * @param string $data
     * @param string $secret
     * @return null|string
     * @throws RequestInvalidException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function encrypt(string $data, string $secret) :? string
    {
        $response = $this->makePostRequest($data, $secret);

        return $this->getResponse($response, 'data');
    }

    /**
     * Call the API Gateway
     *
     * @param string $data
     * @param string $secret
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function makeGetRequest(string $data, string $secret) : array
    {
        $response = $this->getGuzzleClient()->request('GET', "/{$this->stage}?data={$data}&secret={$secret}", []);

        return $this->handleResponse($response);
    }

    /**
     * Call the API Gateway
     *
     * @param string $data
     * @param string $secret
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function makePostRequest(string $data, string $secret) : array
    {
        $request = [
            'headers'  => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'data' => $data,
                'secret' => $secret
            ]
        ];

        $response = $this->getGuzzleClient()->request('POST', "/{$this->stage}", $request);

        return $this->handleResponse($response);
    }

    /**
     * Get the Guzzle Client
     *
     * @return GuzzleClient
     */
    private function getGuzzleClient() : GuzzleClient
    {
        if ( is_null($this->client) ) {
            $this->client = new GuzzleClient(['base_uri' => $this->endpoint]);
        }

        return $this->client;
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

    /**
     * Validate and return the expected response data
     *
     * @param array $response
     * @param string $key
     * @return string
     * @throws RequestInvalidException
     * @throws Exception
     */
    public function getResponse(array $response, string $key = 'data') : string
    {
        if ( isset($response['error']) ) {
            throw new RequestInvalidException($response['error']);
        }

        if ( !isset($response[$key]) ) {
            throw new Exception("Response Missing Expected Key: `{$key}`");
        }

        return $response[$key];
    }

}