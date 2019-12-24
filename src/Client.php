<?php

namespace EasyHttpClient;

use Exception;
use InvalidArgumentException;

class Client
{
    /** @var string */
    public $host;

    /**
     * @param string $host
     */
    public function __construct($host)
    {
        $this->host = trim($host, '/');
    }

    /**
     * @param string $path
     * @param array|null $query
     * 
     * @return array
     * 
     * @throws Exception
     */
    public function get($path, $query = null)
    {
        $this->checkPathFormat($path);
        $url = $this->createUrl($path, $query);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $message = curl_error($ch);
            curl_close($ch);

            throw new Exception($message);
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'body' => $response,
            'statusCode' => $statusCode
        ];
    }

    /**
     * @param string $path
     * @param array|null $query
     * 
     * @return $url
     */
    private function createUrl($path, $query = null)
    {
        $url = implode([$this->host, $path]);

        if ($query) {
            $query = http_build_query($query);
            $url = implode('?', [$url, $query]);
        }

        return $url;
    }

    /**
     * @param string $path
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private function checkPathFormat($path)
    {
        $position = strpos($path, '/');
        if ($position ===  false || $position !== 0) {
            throw new InvalidArgumentException();
        }
    }
}
