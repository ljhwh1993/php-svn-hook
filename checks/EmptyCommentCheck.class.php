<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'BasePreCommitCheck.class.php';

class EmptyCommentCheck extends BasePreCommitCheck {
  
  function getTitle(){
    return "Reject minimalistic comment";
  }
  
  public function renderErrorSummary(){
    return "Commit message empty or too short!";
  }
  
  public function checkSvnComment($comment){
    if (strlen($comment) < 5){
      return 'You must give me more than 5 chars as comment!';
    }

    exec('/usr/bin/svnlook changed -t "'.$this->trxNum.'" "'.$this->repoName.'"', $result);
    //error_log(print_r($result, true)."\n".substr($result[0], 4, 4)."\n", 3, '/tmp/a');
    //error_log((int)preg_match('\w+-\d+[ ,].*review[ ]+by[ ]+\w+[ ,].*(ready|test)', $comment)."\n", 3, '/tmp/a');
    if ((substr($result[0], 4, 4) == 'tags') && (0 == preg_match('/\w+-\d+[ ,].*review[ ]+by[ ]+\w+[ ,].*(ready|test)/', $comment, $matches))) {
      //error_log("\n aaaaaaaa \n".print_r($matches, true), 3, '/tmp/a');
      return 'comment format wrong,ex: "ark-111, DC-112, review by xxx , test"';
    }
  }
}


//LOGMSG=`$SVNLOOK log -t "$TXN" "$REPOS" | grep "[a-zA-Z0-9]" | wc -c`
//if [ "$LOGMSG" -lt 5 ];#要求注释不能少于5个字符，您可自定义
//then
//  echo -e "You must give me more than 5 chars as comment!." 1>&2
//  exit 1
//fi
//
//# 检查提交线上版本代码时的注释格式
//repos_name=`curl 'http://deploy1-dev.bj1.haodf.net:88/current.php' 2>/dev/null | grep 'webapps' | awk '{print $3}'`
//ver_name=`curl 'http://deploy1-dev.bj1.haodf.net:88/current.php' 2>/dev/null | grep 'webapps' | awk '{print $4}'`
//if [[ `$SVNLOOK changed -t "$TXN" "$REPOS" | grep " $repos_name/$ver_name/"` != '' ]];then
//        echo -e "comment format wrong,ex: \"ark-111, DC-112, review by xxx , test\"" 1>&2
//        $SVNLOOK log -t "$TXN" "$REPOS" | grep -P '\w+-\d+[ ,].*review[ ]+by[ ]+\w+[ ,].*(ready|test)' || exit 1
//fi
