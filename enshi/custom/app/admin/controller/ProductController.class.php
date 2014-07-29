<?php
class ProductController extends AppController{
	public function initialize(){
		parent::initialize();
		$parts = S('category')->getParts(false, false, 1, 'product');
		$this->assign('parts', $parts);
	}
	public function filter($data){
		$params = array(
				'name' => array('name' => '产品名称'),
				'cate_id' => array('required' => false),
				'price' => array('name' => '市场价', 'required' => false),
				'sale_price' => array('name' => '销售价', 'required' => false),
				'brand' => array('name' => '品牌', 'required' => false),
				'details' => array('name' => '产品详情', 'length' => false, 'required' => false),
				'order_asc' => array('required' => false)
		
		);
		$result = ValidateComponent::validate($data['post'], $params);
		if($result === false){
			$this->showMsg(ValidateComponent::get_msg(),false);
			return false;
		}
		if(isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name'])){
			$file = SUtilComponent::upload($_FILES, 'thumbnail', $result['name']);
			if($file === false){
				$this->showMsg('文件上传错误!', false);
				return false;
			}
			$result['thumbnail'] = $file;
		}
		return $result;
	}
}