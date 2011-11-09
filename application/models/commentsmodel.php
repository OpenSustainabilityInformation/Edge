<?php
class Commentsmodel extends FT_Model{
    function Commentsmodel(){
        parent::__construct();
    }

	public function getComments($uri) {
		$q = "select * where { " . 
			"<" . $uri . "> sioc:post ?post . " . 
			"?post dcterms:title ?title . " . 
			"?post dcterms:created ?created . " .
			"?post sioc:content ?comment . " .
			"?post sioc:hasCreator ?account . " .			
			"?account sioc:userAccount ?a_bnode . " . 
			"?a_bnode foaf:account ?aa_bnode . " .
			"?aa_bnode foaf:accountName ?author . " .
			"?aa_bnode foaf:accountServiceHomepage 'http://footprinted.org' . " .
			"}";
		$records = $this->executeQuery($q);	
		$comments = $records;
			if(count($records) > 0) {
				$count = 0;
				foreach ($records as $record) {
					$replies = $this->getComments($record['post']);
					if(count($replies) > 0) {
						$comments[$count]['replies'] = $replies;
					}
					$count++;
				}
				return $comments;		
			}
	}
	
} // End Class