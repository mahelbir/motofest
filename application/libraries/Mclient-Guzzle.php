<?php

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\BadResponseException;

class Mclient
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var array
     */
    private $requests;
    /**
     * @var array
     */
    private $responses;
    /**
     * @var int
     */
    private $timeout;
    /**
     * @var int
     */
    private $connectTimeout;
    /**
     * @var int
     */
    private $concurrency;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->setTimeout(0);
        $this->setConnectTimeout(5);
        $this->setConcurrency(50);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param array $options
     * @param null $extra
     * @return void
     */
    public function request(string $method, string $url, array $headers = [], array $options = [], $extra = null): void
    {
        $headers= array_change_key_case($headers);
        $options= array_change_key_case($options);
        if (!empty($options["interface"]) && empty($options["proxy"])) {
            $options["curl"][CURLOPT_IPRESOLVE] = stristr($options["interface"], ":") ? CURL_IPRESOLVE_V6 : CURL_IPRESOLVE_V4;
            $options["curl"][CURLOPT_INTERFACE] = $options["interface"];
        }
        if (empty($headers["user-agent"]))
            $headers["user-agent"] = "Mclient";

        $this->requests[] = [
            "method" => strtoupper($method),
            "url" => $url,
            "headers" => $headers,
            "options" => $options,
            "extra" => $extra
        ];
    }

    /**
     * @param string $url
     * @param array|string $data
     * @param array $headers
     * @param array $options
     * @param null $extra
     * @return void
     */
    public function post(string $url, $data, array $headers = [], array $options = [], $extra = null): void
    {
        if (is_array($data)) {
            $options["form_params"] = $data;
        } else {
            $options["body"] = $data;
        }
        $this->request("POST", $url, $headers, $options, $extra);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @param array $options
     * @param null $extra
     * @return void
     */
    public function get(string $url, array $data = [], array $headers = [], array $options = [], $extra = null): void
    {
        if (!empty($data))
            $options["query"] = $data;
        $this->request("GET", $url, $headers, $options, $extra);
    }

    /**
     * @param bool $single
     * @return array
     */
    public function execute(bool $single = false): array
    {
        $this->generateResponses();
        return $single ? $this->responses[0] : $this->responses;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getConnectTimeout(): int
    {
        return $this->connectTimeout;
    }

    /**
     * @param int $connectTimeout
     */
    public function setConnectTimeout(int $connectTimeout): void
    {
        $this->connectTimeout = $connectTimeout;
    }

    /**
     * @return int
     */
    public function getConcurrency(): int
    {
        return $this->concurrency;
    }

    /**
     * @param int $concurrency
     */
    public function setConcurrency(int $concurrency): void
    {
        $this->concurrency = $concurrency;
    }

    /**
     * @return void
     */
    private function generateResponses(): void
    {
        $this->responses = [];
        $pool = new Pool($this->client, $this->generateRequests(), [
            'concurrency' => $this->getConcurrency(),
            'fulfilled' => function (Response $response, $request) {
                $extra = $request["extra"];
                unset($request["extra"]);
                $this->responses[] = [
                    "code" => $response->getStatusCode(),
                    "body" => $response->getBody()->getContents(),
                    "headers" => array_change_key_case($response->getHeaders()),
                    "request" => $request,
                    "extra" => $extra
                ];
            },
            'rejected' => function (Exception $e, $request) {
                $body = '';
                $headers = [];
                if ($e instanceof BadResponseException) {
                    $body = $e->getResponse()->getBody()->getContents();
                    $headers = array_change_key_case($e->getResponse()->getHeaders());
                }
                $extra = $request["extra"];
                unset($request["extra"]);
                $this->responses[] = [
                    "code" => $e->getCode(),
                    "body" => $body,
                    "headers" => $headers,
                    "request" => $request,
                    "extra" => $extra
                ];
            },
        ]);
        $pool->promise()->wait();
        $this->requests = [];
    }

    /**
     * @return Generator
     */
    private function generateRequests(): Generator
    {
        foreach ($this->requests as $request) {
            yield $request => function () use ($request) {
                return $this->client->requestAsync($request["method"], $request["url"], array_merge($request["options"], [
                    "headers" => $request["headers"],
                    "timeout" => $this->getTimeout(),
                    "connect_timeout" => $this->getConnectTimeout(),
                    "http_errors" => false
                ]));
            };
        }
    }

}
