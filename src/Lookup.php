<?php

namespace EZVenue;
use \Common\Util;

class Lookup {

    public function __construct($data) {
        $this->id = Util::get('id', $data);
        $this->batch_id = Util::get('batch_id', $data);
        $this->ref = Util::get('ref', $data);
        $this->result = Util::get('result', $data);
        $this->amount = Util::get('amount', $data);
        $this->street_1 = Util::get('street_1', $data);
        $this->street_2 = Util::get('street_2', $data);
        $this->city = Util::get('city', $data);
        $this->state = Util::get('state', $data);
        $this->zip = Util::get('zip', $data);
        $this->lat = Util::get('lat', $data);
        $this->lng = Util::get('lng', $data);
        $this->formatted_address = Util::get('formatted_address', $data);
        $this->created_at = Util::get('created_at', $data);
        $this->results = [];

        if ($results = get('results', $data)) {
            foreach ($results as $lookup_result) {
                $this->results[] = new LookupResult($lookup_result);
            }
        }
    }
}