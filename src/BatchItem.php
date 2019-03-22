<?php

namespace EZVenue;
use \Common\Util;

class BatchItem {

    public function __construct($data) {
        $this->id = Util::get('id', $data);
        $this->ref = Util::get('ref', $data);
        $this->input = Util::get('input', $data);
        $this->notes = Util::get('notes', $data);
        $this->completed_at = Util::get('completed_at', $data);
        $this->created_at = Util::get('created_at', $data);
        $this->result = null;

        $result_data = get('result', $data);
        if ($result_data) {
            $this->result = new Lookup($result_data);
        }
    }
}