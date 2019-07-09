<?php

namespace EZVenue;
use \Common\Util;

class Pagination {
	private static function _getPage($direction) {
		if (empty(EZVenue::$curl->responseHeaders['link'])) return false;

		$links = explode(', ', EZVenue::$curl->responseHeaders['link']);
		foreach ($links as $link) {
			$parts = explode('; ', $link);
			$url = trim($parts[0], '<>');
			preg_match('/rel="(\w+)"/', $parts[1], $matches);
			if ($matches) {
				if ($direction === $matches[1]) {
					parse_str(parse_url($url, PHP_URL_QUERY), $output);
					return $output['page'] ?? null;
				}
			}
		}

		return false;
	}

	public static function nextPage() {
		return self::_getPage('next');
	}

	public static function lastPage() {
		return self::_getPage('last');
	}

	public static function prevPage() {
		return self::_getPage('prev');
	}

	public static function firstPage() {
		return self::_getPage('first');
	}
}