<?php
class DfaController extends AppController{
	
	public function index(){
		Includes::useComponent('DFA');
		$dfa = new DFAComponent();
		echo '<meta charset="utf-8">';
		var_dump($dfa->words);
	}
}