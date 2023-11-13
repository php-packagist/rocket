<?php

namespace PhpPackagist\Rocket;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\CurlException;

class Request
{
    /**
     * The base URL for the request.
     *
     * @var string
     */
    protected $baseUrl = '';

    /**
     * UA
     *
     * @var string
     */
    protected $userAgent = 'tw591-ms-client';

    /**
     * The request options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Set the base URL for the pending request.
     *
     * @param string $url
     *
     * @return $this
     */
    public function baseUrl($url)
    {
        $this->baseUrl = $url;

        return $this;
    }

    /**
     * Specify the request's content type.
     *
     * @param string $contentType
     *
     * @return $this
     */
    public function contentType($contentType)
    {
        return $this->withHeaders(array('Content-Type' => $contentType));
    }

    /**
     * Indicate that JSON should be returned by the server.
     *
     * @return $this
     */
    public function acceptJson()
    {
        return $this->accept('application/json');
    }

    /**
     * Indicate the type of content that should be returned by the server.
     *
     * @param string $contentType
     *
     * @return $this
     */
    public function accept($contentType)
    {
        return $this->withHeaders(array('Accept' => $contentType));
    }

    /**
     * Add the given headers to the request.
     *
     * @param array $headers
     *
     * @return $this
     */
    public function withHeaders($headers)
    {
        $this->options = array_merge_recursive($this->options, array(
            'headers' => $headers,
        ));

        return $this;
    }

    /**
     * Specify the basic authentication username and password for the request.
     *
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function withBasicAuth($username, $password)
    {
        $this->options['auth'] = array($username, $password);

        return $this;
    }

    /**
     * Specify the digest authentication username and password for the request.
     *
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function withDigestAuth($username, $password)
    {
        $this->options['auth'] = array($username, $password, 'digest');

        return $this;
    }

    /**
     * Specify an authorization token for the request.
     *
     * @param string $token
     * @param string $type
     *
     * @return $this
     */
    public function withToken($token, $type = 'Bearer')
    {
        $this->options['headers']['Authorization'] = trim($type . ' ' . $token);

        return $this;
    }

    /**
     * Indicate that redirects should not be followed.
     *
     * @return $this
     */
    public function withoutRedirecting()
    {
        $this->options['allow_redirects'] = false;

        return $this;
    }

    /**
     * Indicate that TLS certificates should not be verified.
     *
     * @return $this
     */
    public function withoutVerifying()
    {
        $this->options['verify'] = false;

        return $this;
    }

    /**
     * Specify the timeout (in seconds) for the request.
     *
     * @param int $seconds
     *
     * @return $this
     */
    public function timeout($seconds)
    {
        $this->options['timeout'] = $seconds;

        return $this;
    }

    /**
     * Merge new options into the client.
     *
     * @param array $options
     *
     * @return $this
     */
    public function withOptions($options)
    {
        $this->options = array_merge_recursive($this->options, $options);

        return $this;
    }

    /**
     * Issue a GET request to the given URL.
     *
     * @param string $url
     * @param array|string|null $query
     *
     * @return Response
     * @throws \Exception
     */
    public function get($url, $query = array())
    {
        return $this->send('GET', $url, array(
            'query' => $query,
        ));
    }

    /**
     * Issue a HEAD request to the given URL.
     *
     * @param string $url
     * @param array|string|null $query
     *
     * @return Response
     * @throws \Exception
     */
    public function head($url, $query = null)
    {
        return $this->send('HEAD', $url, array(
            'query' => $query,
        ));
    }

    /**
     * Issue a POST request to the given URL.
     *
     * @param string $url
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function post($url, $data = array())
    {
        return $this->send('POST', $url, array(
            'body' => $data,
        ));
    }

    /**
     * Issue a PATCH request to the given URL.
     *
     * @param string $url
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function patch($url, $data = array())
    {
        return $this->send('PATCH', $url, array(
            'body' => $data,
        ));
    }

    /**
     * Issue a PUT request to the given URL.
     *
     * @param string $url
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function put($url, $data = array())
    {
        return $this->send('PUT', $url, array(
            'body' => $data,
        ));
    }

    /**
     * Issue a DELETE request to the given URL.
     *
     * @param string $url
     * @param array $data
     *
     * @return Response
     * @throws \Exception
     */
    public function delete($url, $data = array())
    {
        return $this->send('DELETE', $url, empty($data) ? array() : array(
            'body' => $data,
        ));
    }

    /**
     * Send the request to the given URL.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function send($method, $url, $options = array())
    {
        $url = ltrim(rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/'), '/');
        // $this->mergeOptions($options));

        list($headers, $body, $options) = $this->anaOptions($options);

        try {
            $resp = $this->buildClient()
                ->createRequest($method, $url, $headers, $body, $options)
                ->send();

            $response = new Response($resp);

            if (!$response->successful()) {
                $response->throws();
            }

            return $response;
        } catch (CurlException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        } catch(ClientErrorResponseException $e) {
            throw new RequestException(new Response($e->getResponse()));
        }
    }

    /**
     * Analyze the options.
     *
     * @param $options
     * @return array
     */
    protected function anaOptions($options)
    {
        $headers = $body = array();

        $options = $this->mergeOptions($options);

        if (isset($options['headers'])) {
            $headers = $options['headers'];
            unset($options['headers']);
        }

        if (isset($options['body'])) {
            $body = $options['body'];
            unset($options['body']);
        }

        return array($headers, $body, $options);
    }

    /**
     * Build the Guzzle client.
     *
     * @return \Guzzle\Http\Client
     */
    public function buildClient()
    {
        $client = new Client();

        $client->setUserAgent($this->userAgent);

        return $client;
    }

    /**
     * Merge the given options with the current request options.
     *
     * @param array $options
     *
     * @return array
     */
    public function mergeOptions($options)
    {
        return array_merge_recursive($this->options, $options);
    }
}
