<?php

namespace Webkul\Admin;

class ProductFormAccordian {

	public $items = [];

	/**
	 * Shortcut method for create a Product Form Accordian with a callback.
	 * This will allow you to do things like fire an event on creation.
	 *
	 * @param  callable $callback Callback to use after the accordian creation
	 * @return object
	 */
	public static function create($callback) {
		$accordian = new ProductFormAccordian();
		$callback($accordian);
		$accordian->items = core()->sortItems($accordian->items);

		return $accordian;
	}

	/**
	 * Add a accordian item to the item stack
	 *
	 * @param string  $key   Dot seperated heirarchy
	 * @param string  $name  Text for the anchor
	 * @param string  $view  Blade file for accordian
	 * @param integer $sort  Sorting index for the items
	 */
	public function add($key, $name, $view, $sort = 0)
	{
		array_push($this->items, [
			'key'		 => $key,
			'name'		 => $name,
			'view'		 => $view,
			'sort'		 => $sort
        ]);
	}
}