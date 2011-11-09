<?php
/**
 * Controller for searching the footprints
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */

class Search extends FT_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	/*
	Public search function, it uses the non-linked cached version of the database
	Three possible searchs:
	- ?category= search for free text in the category
	- ?ref= search for free text in the reference of the data (so looking for things from the same dataset)
	- ?keyword= free text search in the name
	*/
	public function index(){
		
		/*
		// Get params
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		$search_terms = $_GET;
		// Category search
		if (isset($search_terms['category']) == true) {
			$this->db->like('category', $search_terms['category']);
			$this->data("category", $search_terms['category']);
			$this->db->order_by("name", "ASC"); 
			$this->db->where("public",true);
			$rs = $this->db->get('footprints',100,0);
			$this->data("set", $rs->result());
		}
		// Reference search (dataset ref)
		if (isset($search_terms['ref']) == true) {
			$this->db->like('ref', $search_terms['ref']);
			$this->data("category", $search_terms['ref']);
			$this->db->order_by("name", "ASC");
			$this->db->where("public",true); 
			$rs = $this->db->get('footprints');
			$this->data("set", $rs->result());
		}
		// Keyword search
		if (isset($_POST["keyword"]) == true){
			$keyword = $_POST["keyword"];
		} else {
			$keyword = "";
		}
		if ($keyword != "") {
			$this->db->like('name', $keyword); 
			$this->db->order_by("name", "ASC");
			$this->db->where("public",true); 
			$rs = $this->db->get('footprints',100,0);
			$this->data("set", $rs->result());
		}
		*/	
		$search_terms = array();
		parse_str($_SERVER['QUERY_STRING'],$_GET);
		if ($_GET) {
			$search_terms = array_merge($search_terms,$_GET);
		}
		if ($_POST) {
			$search_terms = $_POST;
		}
		foreach($search_terms as $term=>$value) {
			if ($value == "") {
				unset($search_terms[$term]);
			}
		}
		$do_search = false;
		// Category search
		if (isset($search_terms['category']) == true) {
			if ($search_terms['category'] != "") {
				$this->db->like('category', $search_terms['category']);
				$do_search = true;
			}
		}
		// Country search
		if (isset($search_terms['country']) == true) {
			if ($search_terms['country'] != "") {
				$this->db->like('country', $search_terms['country']);
				$do_search = true;
			}
		}
		// Reference search (dataset ref)
		if (isset($search_terms['ref']) == true) {
			if ($search_terms['ref'] != "") {
				$this->db->like('ref', $search_terms['ref']);
				$do_search = true;
			}
		}
		// Keyword search
		if (isset($search_terms["keyword"]) == true){
			if ($search_terms['keyword'] != "") {
				$this->db->like('name', $search_terms['keyword']);
				$do_search = true;
			}
		}
		if (isset($search_terms["startYear"]) == true){
			if ($search_terms['startYear'] != "") {
				$this->db->where('year >= ', $search_terms['startYear']);
				$do_search = true;
			}
		}
		if (isset($search_terms["endYear"]) == true){
			if ($search_terms['endYear'] != "") {
				$this->db->where('year <= ', $search_terms['endYear']);
				$do_search = true;
			}
		}

		if ($do_search == true) {
			$this->db->where("public",true);
			$this->data("set_count", $this->db->count_all_results('footprints'));
			$this->db->order_by("name", "ASC"); 
			$this->db->where("public",true);
			// Category search
			if (isset($search_terms['category']) == true) {
				if ($search_terms['category'] != "") {
					$this->db->like('category', $search_terms['category']);
				}
			}
			// Country search
			if (isset($search_terms['country']) == true) {
				if ($search_terms['country'] != "") {
					$this->db->like('country', $search_terms['country']);
				}
			}
			// Reference search (dataset ref)
			if (isset($search_terms['ref']) == true) {
				if ($search_terms['ref'] != "") {
					$this->db->like('ref', $search_terms['ref']);
				}
			}
			// Keyword search
			if (isset($search_terms["keyword"]) == true){
				if ($search_terms['keyword'] != "") {
					$this->db->like('name', $search_terms['keyword']);
				}
			}
			if (isset($search_terms["startYear"]) == true){
				if ($search_terms['startYear'] != "") {
					$this->db->where('year >= ', $search_terms['startYear']);
				}
			}
			if (isset($search_terms["endYear"]) == true){
				if ($search_terms['endYear'] != "") {
					$this->db->where('year <= ', $search_terms['endYear']);
				}
			}
			
			if (isset($search_terms['offset']) == true) {
				$this->db->limit(100, $search_terms['offset']);
			} else {
				$this->db->limit(100);
			}
			$rs = $this->db->get('footprints');
			$this->data("search_terms", $search_terms);
			$this->data("set", $rs->result());
		}
		// Categories array for quick search menu 
		$categories = array(
			array("uri" => "chemical", "label" => "chemical compounds"),
			array("uri"=>"transport", "label"=> "transportation"),
			array("uri"=>"textile","label"=> "textiles"),
			array("uri"=>"building","label"=> "building materials"),
			array("uri"=>"food",	"label"=> "food"),
			array("uri"=>"fuel",	"label"=> "fuels"),
			array("uri"=>"process",	"label"=> "transformation processes"),
			array("uri"=>"electricity","label"=> "electricity"));
		// Call the view
		$this->script(Array('search.js'));
		$this->data("menu", $categories);
		$this->display("Search","search_view");
	}
		
}