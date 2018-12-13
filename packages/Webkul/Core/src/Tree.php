<?php

namespace Webkul\Core;

use Illuminate\Support\Facades\Request;

class Tree {

	public $items = [];

	public $current;

	public $currentKey;

	public function __construct() {
		$this->current = Request::url();
	}

	/**
	 * Shortcut method for create a Config with a callback.
	 * This will allow you to do things like fire an event on creation.
	 *
	 * @param  callable $callback Callback to use after the Config creation
	 * @return object
	 */
	public static function create($callback) {
		$tree = new Tree();
		$callback($tree);

		return $tree;
	}

	/**
	 * Add a Config item to the item stack
	 *
	 * @param string  $item   Dot seperated heirarchy
	 */
	public function add($item, $type = '')
	{
        $item['children'] = [];

		if ($type == 'menu') {
			$item['url'] = route($item['route']);

			if (strpos($this->current, $item['url']) !== false) {
				$this->currentKey = $item['key'];
			}
		}

		$children = str_replace('.', '.children.', $item['key']);
		core()->array_set($this->items, $children, $item);
	}

	/**
	 * Method to find the active links
	 *
	 * @param  array $item Item that needs to be checked if active
	 * @return string
	 */
	public function getActive($item)
	{
		$url = trim($item['url'], '/');

		if ((strpos($this->current, $url) !== false) || (strpos($this->currentKey, $item['key']) === 0)) {
			return 'active';
		}
	}
}