<?php
Includes::useComponent('Const');
class SpokerService extends AppService{
	/**
 	 * @todo shuffle of cards
	 * @param number $hands number of players
	 * @param number $rest_cards the remain cards
	 * @param a1rray $cards cards 
	 * @return false for failed or array for success
	 */
	public function shuffle($hands = 3, $rest_cards = 3, $cards = array()){
		$newCards = empty($cards) || !is_array($cards) ? ConstComponent::$cards : $cards; 
		$count = count($newCards);
		$tmp = $count - $rest_cards;
		if($tmp < 0){
			return false;
		}
		if($tmp % $hands != 0){
			return false;
		}
		$every = (int)($tmp / $hands);
		$result = array('hands' => array(), 'rest' => array());
		for($i = 0; $i < $hands; $i++){
			$hand = array();
			$indexs = array_rand($newCards, $every);
			foreach ($indexs as $index){
				$hand[] = $newCards[$index];
				unset($newCards[$index]);
			}
			$result['hands'][] = $hand;
		}
		$result['rest'] = $newCards;
		return $result;
	}
}
?>