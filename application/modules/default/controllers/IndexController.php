<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    			$firstInterest = new Zend_Form_Element_Select(
'firstInterest',
array(
'required' => 'true',
'value' => 'a',
'multiOptions' => array( 'z' => 'z', 'x' => 'x', 'a' => 'a' )));
	$firstInterest->setValue('a');
			echo $firstInterest;


    }


}

