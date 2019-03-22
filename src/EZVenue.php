<?php

namespace EZVenue;

use \Curl\Curl;
use \Common\Util;

class EZVenue {

    public static $curl;
    public static $api_url;

    public function __construct($usernname, $password, $url = 'https://ezvenue.app/api') {
        self::$api_url = $url;

        self::$curl = new Curl();
        self::$curl->setBasicAuthentication($usernname, $password);
        self::$curl->setHeader('Content-Type', 'application/json');
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
            'mapping' => $mapping
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
        plog($response);
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
}