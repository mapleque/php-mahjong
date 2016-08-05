<?php
/**
 * For Test Echo
 */
function dump($p)
{
	if (is_array($p)) {
		foreach($p as $e) {
			echo
				//'[',$e['index'],']',
				//'[',$e['value'],']',
				'|',$e['name'],'|';
		}
	} elseif (is_string($p)) {
		echo $p;
	} else {
		var_dump($p);
	}
	echo "\n";
}