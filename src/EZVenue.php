<?php

namespace EZVenue;

use \Curl\Curl;
use \Common\Util;

class EZVenue {
    const VERSION = '0.3.1';
    public static $curl;
    public static $api_url;
    public static $mapper_key;

    public function __construct($usernname, $password, $url = 'https://ezvenue.app/api') {
        self::$api_url = $url;

        self::$curl = new Curl();
        self::$curl->setBasicAuthentication($usernname, $password);
        self::$curl->setHeader('Content-Type', 'application/json');
        self::$curl->setUserAgent('EZVenue-PHP/'.self::VERSION.' (https://github.com/ez-company/ezvenue-php-sdk) PHP/'.PHP_VERSION.' Curl/'.curl_version()['version']);
    }

    /**
     * Set the mapper key
     *
     * @param $key
     * the key you obtained from EZ Venue
     *
     * @see https://ezvenue.app/docs/developer#create-a-single-lookup
     */
    public function setMapperKey($key) {
        self::$mapper_key = $key;
    }

    /**
     * Create a batch
     *
     * @param @file source file
     * @param $mapping field mapping data
     *
     * @return Batch
     *
     */
    public function createBatch($file, $mapping = null) {
        self::$curl->setHeader('Content-Type', 'multipart/form-data');
        $response = self::$curl->post(self::$api_url.'/lookups/batches', [
            'file' => new \CURLFile($file),
            'mapping' => $mapping,
            'mapper_key' => self::$mapper_key
        ]);

        if (self::$curl->error) {
            throw new ProtocolException($response, self::$curl);
        } else {
            return new Batch($response);
        }
    }

    /**
     * Creates a lookup
     *
     * @param $data
     * The data required for a lookup
     *
     * @return Lookup
     *
     */
    public function createLookup($data) {
        if (!$data) throw new \Exception('No data provided');

        if (self::$mapper_key) {
            if (is_object($data)) {
                $data->mapper_key = self::$mapper_key;
            } else if (is_array($data)) {
                $data['mapper_key'] = self::$mapper_key;
            }
        }

        $response = self::$curl->post(self::$api_url.'/lookups', $data);
        if (self::$curl->error) {
            throw new ProtocolException($response, self::$curl);
        } else {
            return new Lookup($response);
        }
    }

    /**
     * Get a single lookup
     *
     * @param $id
     * The lookup id
     *
     * @return Lookup
     *
     */
    public function getLookup($id) {
        $response = self::$curl->get(self::$api_url.'/lookups/'.$id);
        if (self::$curl->error) {
            throw new ProtocolException($response, self::$curl);
        } else {
            return new Lookup($response);
        }
    }

    /**
     * Get lookups
     *
     * @return array
     *
     */
    public function getLookups($params = [], $page = 1) {
        $response = self::$curl->get(self::$api_url.'/lookups?page='.$page, $params);
        if (self::$curl->error) {
            throw new ProtocolException($response, self::$curl);
        } else {
            $lookups = [];
            foreach ($response as $lookup_data) {
                $lookups[] = new Lookup($lookup_data);
            }

            return $lookups;
        }
    }

    /**
     * Get batches
     *
     * @return array
     *
     */
    public function getBatches($params = [], $page = 1) {
        $response = self::$curl->get(self::$api_url.'/lookups/batches?page='.$page, $params);
        if (self::$curl->error) {
            throw new ProtocolException($response, self::$curl);
        } else {
            $batches = [];
            foreach ($response as $batch_data) {
                $batches[] = new Batch($batch_data);
            }

            return $batches;
        }
    }

    public function getBatch($id) {
        $response = self::$curl->get(self::$api_url.'/lookups/batches/'.$id);
        if (self::$curl->error) {
            throw new ProtocolException($response, self::$curl);
        } else {
            return new Batch($response);
        }
    }

    public function lookups($id) {
        $lookup = new Lookup(['id' => $id]);
        return $lookup;
    }

    public function batches($id) {
        $batch = new Batch(['id' => $id]);
        return $batch;
    }

    public function getPagination() {
        return [
            'next' => self::$curl->responseHeaders['next-page'],
            'last' => self::$curl->responseHeaders['last-page']
        ];
    }
}