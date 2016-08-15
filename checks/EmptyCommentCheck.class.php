<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'BasePreCommitCheck.class.php';

class EmptyCommentCheck extends BasePreCommitCheck {

	public function getTitle(){
		return "Reject commit by your comment";
	}

	public function renderErrorSummary(){
		return "Commit message is ilegal!";
	}

	public function checkSvnComment($comment){

		if (strlen($comment) < 5){
			return 'You must give me more than 5 chars as comment!';
		}
		if (strpos($comment, 'self')){
			return 'You can NOT review by yourself, find someone else to help you!';
		}

		exec('/usr/bin/svnlook changed -t "'.$this->trxNum.'" "'.$this->repoName.'"', $result);
		if ($this->isTagCommit($result[0]) 
			&& (false == $this->isMaintains($comment)) 
			&& ($this->isInValidComment($comment))) {
			return 'comment format wrong,ex: "ark-111, DC-112, review by xxxxxxxx Dxxx, test"';
		}
	}

	private function isTagCommit($text){
		return (false !== strpos($text, 'tags'));
	}

	private function isInValidComment($comment){
		$matches = array();
		return (0 == preg_match('/\w+-\d+[ ,].*review[ ]+by[ ]+\w+[ ]+D\d+[ ,].*(ready|test)/', $comment, $matches));
	}

	private function isMaintains($comment){
		return (false !== strpos($comment, 'create tag')
			|| false !== strpos($comment, 'delete tag')
			|| false !== strpos($comment, 'merge tag')
		);
	}
}
