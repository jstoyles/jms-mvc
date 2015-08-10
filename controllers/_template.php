<?php

class Test_Controller
{
	public $template = 'Test'; //Must be defined with same name as view and model templates

	public function main()
	{
		$class = ucfirst($this->template) . '_Model';
		$testModel = new $class;

		//$testModel->insert_test(array('field1','field2'), array('test 7','test 8'));
		//$testModel->delete_test('field1', 'test 5');
		//$testModel->update_test(array('field1','field2'), array('test 1','test 2'), 'id', '1');

		$content = 'No Content';
		if(count($params)>0 && isset($params['article'])){
			$state = $testModel->get_states($params['article']);
			$content = isset($state['title'])?$state['content']:'';
		}
		else{
			$test = $testModel->select_test('id',1);
			$content = isset($test)?$test:'No Fields Found';

			$test2 = $testModel->sp_test('spTest', array(1));
			$content2 = isset($test2)?$test2:'No Fields Found';
		}


		//Create a new view and pass it our template
		$view = new View(strtolower($this->template));

		//assign article data to view
		$view->assign('content' , $content);
		$view->assign('content2' , $content2);
	}
}
