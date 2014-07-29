<?php
/**
 * time component 
 * @author palace
 *
 */
class TimeComponent extends Component{
	private static  $times = null;
	public static function getTime(){
		return (float)(self::$start - self::$end);
	}
	
	public static function point(){
		$time = self::now();
		if(self::$times == null){
			self::$times = new SplQueue();
		}
		self::$times->enqueue($time);
	}
	
	public static function pull(){
		if(self::$times == null){
			self::$times = new SplQueue();
		}
		if(self::$times->isEmpty()){
			$time = self::now();
			self::$times->enqueue($time);
		}
		return self::$times->dequeue();
	}
	
	public static function now(){
		list($usec, $sec) = explode(' ', microtime());
		return ((float)$usec + (float)$sec);
	}
}