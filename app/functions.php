<?php
//公共函数模块
function formatPageSize($pageSize=10){

	$pageSize = max(5,$pageSize);
	$pageSize = min(10000,$pageSize);
	return intval($pageSize);
}

//菜单格式化成多维数组
function listToTreeMulti($list=[], $root = 0, $pk = 'id', $pid = 'parent_id', $child = 'children') {
   $tree = [];
   if(is_array($list)&&!empty($list)){
  	foreach($list as $k=>$item){
		if($item[$pid]==$root){
			$tree[] = &$list[$item[$pk]];
		}elseif(isset($list[$item[$pid]])){
			$list[$item[$pid]][$child][] = &$list[$item[$pk]];
		}
	}
   
  }
  return $tree;
}
//对象转数组
function objectToArray(&$object) {
    $object =  json_decode( json_encode( $object),true);
    return  $object;
}
?>