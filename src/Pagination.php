<?php

namespace EZVenue;
use \Common\Util;

class Pagination {
	private $_link;
	private $_next_page;
	private $_last_page;
	private $_first_page;
	private $_prev_page;

	public function __construct($link) {
		$this->_link = $link;
		$this->_init();
	}

	/**
	 * Parses the given Link header
	 * @example <http://localhost/ezvenue/api/lookups/batches/173/items?page=2&per_page=30>; rel="next", <http://localhost/ezvenue/api/lookups/batches/173/items?page=2&per_page=30>; rel="last", <http://localhost/ezvenue/api/lookups/batches/173/items?page=1&per_page=30>; rel="first"
	 *
	 */
	private function _init() {
		if (!$this->_link) return false;

		$links = explode(', ', $this->_link);
		foreach ($links as $link) {
			$parts = explode('; ', $link);
			$url = trim($parts[0], '<>');
			preg_match('/rel="(\w+)"/', $parts[1], $matches);
			if ($matches) {
				$direction = $matches[1];

				parse_str(parse_url($url, PHP_URL_QUERY), $output);
				$this->{'_'.$direction.'_page'} = $output['page'] ?? null;
			}
		}
	}

	public function nextPage() {
		return $this->_next_page;
	}

	public function lastPage() {
		return $this->_last_page;
	}

	public function prevPage() {
		return $this->_prev_page;
	}
}