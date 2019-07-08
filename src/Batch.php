<?php

namespace EZVenue;
use \Common\Util;

class Batch {

    public function __construct($data) {
        $this->id = Util::get('id', $data);
        $this->processed = Util::get('processed', $data);
        $this->exceptions = Util::get('exceptions', $data);
        $this->filename = Util::get('filename', $data);
        $this->mapping = Util::get('mapping', $data);
        $this->completed_at = Util::get('completed_at', $data);
        $this->created_at = Util::get('created_at', $data);
    }

    public function isCompleted() {
        return $this->completed_at ? true : false;
    }

    public function getItems($params = [], $page = 1, &$next_page = null) {
        $next_page = null;

        $response = EZVenue::$curl->get(EZVenue::$api_url.'/lookups/batches/'.$this->id.'/items?page='.$page, $params);
        if (EZVenue::$curl->error) {
            throw new ProtocolException($response, EZVenue::$curl);
        } else {
            $items = [];
            foreach ($response as $item_data) {
                $items[] = new Lookup($item_data);
            }

            // process pagination
            if (!empty(self::$curl->responseHeaders['link'])) {
                $pagination = new Pagination(self::$curl->responseHeaders['link']);
                $next_page = $pagination->nextPage();
            }

            return $items;
        }
    }
}