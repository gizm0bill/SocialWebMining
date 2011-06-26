<?php

use App\Model\Client;

class Management_ClientController extends Zend_Controller_Action
{
	private function _makeClietnForm( $client = null, $name = 'clientedit' )
	{
		$form = new Zend_Form();
		$form->setMethod(Zend_Form::METHOD_POST);
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => 'name',
			'label'		 => 'Client Name',
			'required'	 => true,
			'filters'	 => array( 'StringTrim', 'StripTags' ),
			'validators' => array( 'NotEmpty' )
		)));
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => 'domain',
			'label'		 => 'Domain',
			'class'		 => 'large',
			'required'	 => true,
			'filters'	 => array( 'StringTrim', 'StripTags' ),
			'validators' => array( 'NotEmpty' )
		)));
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => 'email',
			'class'		 => 'medium',
			'label'		 => 'Email',
			'filters'	 => array( 'StringTrim', 'StripTags' ),
			'validators' => array( 'EmailAddress' )
		)));
		return $form;
	}

	public function indexAction()
	{
		$c = new Client();
		$this->view->clients = $c->fetchAll
		(
			$c->select()->where( Client::getCols()->idUser . ' = ? ', Zend_Auth::getInstance()->getIdentity()->id )
		);
	}

	public function addAction()
	{
		$x = new Zend_Validate_Hostname();

		$this->view->form = $form = $this->_makeClietnForm();
		if( count( $this->_request->getPost() ) && $form->isValid( $this->_request->getPost() ) )
		{
			$c = new Client();
			$data = $this->_request->getPost();
			$iid = $c->insert( array
			(
				Client::getCols()->name		=> $data[Client::getCols()->name],
				Client::getCols()->domain	=> $data[Client::getCols()->domain],
				Client::getCols()->email 	=> $data[Client::getCols()->email],
				Client::getCols()->idUser	=> Zend_Auth::getInstance()->getIdentity()->id
			) );
			$this->_helper->flashMessenger( 'Client succefully added!' );
			$this->_helper->redirector( 'edit', 'client', 'management', array( 'id' => $iid ) );
		}
		$form->addElement( new Zend_Form_Element_Submit( array
		(
			'name' 	=> 'submit-button',
			'label' => 'Add client',
			'class' => 'clear',
			'decorators' => array( 'ViewHelper' )
		) ) );
	}

	public function editAction()
	{

	}
}