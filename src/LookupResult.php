<?php

namespace EZVenue;
use \Common\Util;

class LookupResult {

    public function __construct($data) {
        $this->id = Util::get('id', $data);
        $this->area_id = Util::get('area_id', $data);
        $this->venue_id = Util::get('venue_id', $data);
        $this->venue_county = Util::get('venue_county', $data);
        $this->venue_precinct = Util::get('venue_precinct', $data);
        $this->venue_name = Util::get('venue_name', $data);
        $this->venue_efile = Util::get('venue_efile', $data);
        $this->created_at = Util::get('created_at', $data);
    }
}