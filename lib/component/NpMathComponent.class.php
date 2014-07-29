<?php
class NpMathComponent extends Component{
	
	static public function fibonacci($total){
		$arr = array(1, 1);
		for($i=2; $i<=$total;$i++){
			$n = $i % 3;
			$f = ($n + 1) % 3;
			$s = ($n + 2) % 3;
			$arr[$n] = $arr[$f] + $arr[$s];
		}
		return $arr[$n];
	}
}