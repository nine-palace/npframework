<?php
class MultiController extends AppController{
	
	public function index(){
		$pid = pcntl_fork();
	}
}