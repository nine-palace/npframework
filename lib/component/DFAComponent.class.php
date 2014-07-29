<?php
class Node{
	public $c;
	public $flag;
	public $nodes = array();
	
	public function __construct($c, $flag = 0){
		$this->c = $c;
		$this->flag = $flag;
	}
}

class DFAComponent extends Component{
	private $arr = array('DFA', '恶心', 'DA');
	private $content = 'Hello DAFDFA World DFA, HaHa! 恶心';
	public $words = array();
	public $word = array();
	
	private $rootNode;
	
	public function __construct(){
		$this->rootNode = new Node('R');
		$this->createTree();
		$this->searchWord();
	}
	
	public function searchWord(){
		$chars = str_split($this->content);
		$node = $this->rootNode;
		$a = 0;
		while($a < count($chars)){
			$node = $this->findNode($node, $chars[$a]);
			if($node == null){
				$node = $this->rootNode;
				$a = $a - count($this->word);
			}elseif($node->flag == 1){
				$this->word[] = (string)$chars[$a];
				$sb = implode('', $this->word);
				$this->words[] = $sb;
				$a = $a - count($this->word) + 1;
				$this->word = array();
				$node = $this->rootNode;
			}else{
				$this->word[] = (string)$chars[$a];
			}
			$a++;
		}
	}
	
	private function createTree(){
		foreach ($this->arr as $v){
			$chars = str_split($v);
			if(count($chars) > 0){
				$this->insertNode($this->rootNode, $chars, 0);
			}
		}
	}
	
	private function insertNode(Node $node, Array $cs, $index){
		$n = $this->findNode($node, $cs[$index]);
		if($n == null){
			$n = new Node($cs[$index]);
			$node->nodes[] = $n;
		}
		if($index == (count($cs) - 1)){
			$n->flag = 1;
		}
		$index++;
		if($index < count($cs)){
			$this->insertNode($n, $cs, $index);
		}
	}
	
	private function findNode(Node $node, $c){
		$nodes = $node->nodes;
		$rn = null;
		foreach ($nodes as $v){
			if($v->c == $c){
				$rn = $v;
				break;
			}
		}
		return $rn;
	}
}