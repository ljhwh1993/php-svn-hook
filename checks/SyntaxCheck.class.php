<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'BasePreCommitCheck.class.php';

class SyntaxCheck extends BasePreCommitCheck {
  
  public function getTitle(){
    return "PHP Syntax Checker";
  }
  
  public function renderErrorSummary(){
    return "Fail!";
  }
  
  public function checkFullFile($lines, $filename){
    if (false !== strpos($filename, 'php')) {
      exec("/usr/bin/svnlook cat $this->repoName $filename -t $this->trxNum | /Data/apps/php/bin/php -l", $result);

//error_log("\n\n =============== \n", 3, '/tmp/a');
//error_log(print_r("/usr/bin/svnlook cat $this->repoName $filename -t $this->trxNum | /Data/apps/php/bin/php -l", true)."\n", 3, '/tmp/a');
//error_log(print_r("$this->repoName, $filename ,$this->trxNum\n", true), 3, '/tmp/a');
//error_log(print_r($result, true), 3, '/tmp/a');
//error_log("\n\n =============== \n", 3, '/tmp/a');

      if (count($result) != 1) {
        return $result[1]."($filename)";
      }
    }
  }
}
