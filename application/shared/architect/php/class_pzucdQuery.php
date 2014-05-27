<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chrishoward
 * Date: 15/08/13
 * Time: 9:16 PM
 * To change this template use File | Settings | File Templates.
 */

class pzucdQuery {

	protected $criteria_options;
	public $page_type = array();
	public $pzucd_query;

	public function __contruct($criteria_options) {

		// First we build the query, then modify it according to the page type

		switch (true) {
			case (is_front_page()):
				$this->page_type = array_merge($this->page_type,array('frontpage'=>true));
		}

		foreach ($this->page_type as $page_type) {

		}
		return $this->pzucd_query;
	}


	protected function front_page() {

	}
	protected function single_page() {

	}
	protected function singular_page() {

	}
	protected function dates_page() {

	}
	protected function categories_page() {

	}
	protected function archives_page() {

	}
	protected function authors_page() {

	}
	protected function tags_page() {

	}
	protected function taxonomies_page() {

	}
	protected function search_page() {

	}

}