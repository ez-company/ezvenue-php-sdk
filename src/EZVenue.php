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
    public function getLookups($params = []) {
        $response = self::$curl->get(self::$api_url.'/lookups', $params);
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

    public function lookups($id) {
        $lookup = new Lookup(['id' => $id]);
        return $lookup;
    }
}