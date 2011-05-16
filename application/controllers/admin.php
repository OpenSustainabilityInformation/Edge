<?php
/**
 * Controller for environmental information structures
 * 
 * @version 0.8.0
 * @author info@footprinted.org
 * @package opensustainability
 * @subpackage controllers
 * @uses 
 */

class Admin extends FT_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model(Array('lcamodel', 'geographymodel', 'bibliographymodel','peoplemodel','commentsmodel','ecomodel','opencycmodel'));	
		$this->load->library(Array('form_extended', 'xml'));
		$this->load->helper(Array('nameformat_helper','linkeddata_helper'));
		$obj =& get_instance();    
        $obj->load->library(array('xml'));
        $this->ci =& $obj;
	}
	
	
	public function assignCategory($index = 1) {
		// find URI of something that doesnt have a category or sameas
		$uris = $this->lcamodel->getRecords();
		$record = array(
			'uri'=> $uris[$index]['uri'],
			'label'=>$uris[$index]['label']
		);
		$sameAs = $this->lcamodel->getSameAs($uris[$index]['uri']);
		$categories = $this->lcamodel->getCategories($uris[$index]['uri']);
		$sameAsSuggestions = $this->lcamodel->getOpenCycSuggestions($uris[$index]['uri']);
		$categorySuggestions = array(
			array(
				"uri" => "Mx4rvUCoPtoTQdaZVdw2OtjsAg",
				"label" => "chemical compound"
			),
			array(
				"uri"=>"Mx4rv-6HepwpEbGdrcN5Y29ycA",
				"label"=> "transportation"
				),
			array(
				"uri"=>"Mx4rvVibU5wpEbGdrcN5Y29ycA",
				"label"=> "textile"
				),
			array(
				"uri"=>"Mx4rwQr0i5wpEbGdrcN5Y29ycA",
				"label"=> "building material"
			),
			array(
				"uri"=>"Mx4rvVi9A5wpEbGdrcN5Y29ycA",	
				"label"=> "food"
			),
			array(
				"uri"=>"Mx4rvViSe5wpEbGdrcN5Y29ycA",
				"label"=> "commodity"
			)		
		);
		// Print out the selector
		$this->data("record", $record);
		$this->data("sameAs", $sameAs);
		$this->data("categories", $categories);		
		$this->data("sameAsSuggestions", $sameAsSuggestions);
		$this->data("categorySuggestions", $categorySuggestions);
		$this->data("next", $index+1);		
		$this->display("Admin - Fix Categories and Same Concept Links", "adminCategories");
			
	}
	
}