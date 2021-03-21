<?php

//特殊記号、タグの除去
function h_($str){
  return htmlspecialchars($str,ENT_QUOTES);
}

/* ///////////////////////////////////////////////////////////////////////
スクリプトの更新日付を返す
/////////////////////////////////////////////////////////////////////// */
function echo_filedate($filename){
  if (file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) {
    echo $filename."?".date('YmdHis', filemtime($_SERVER['DOCUMENT_ROOT'].$filename));
  } else {
    echo $filename;
  }
}
?>
