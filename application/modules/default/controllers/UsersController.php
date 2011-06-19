<?php

use App\Model\User;

class UsersController extends Zend_Controller_Action
{
	public function loginAction()
	{
		$this->_helper->layout->setLayout( 'login' );

		$form = new Zend_Form();
		$form->setMethod( Zend_Form::METHOD_POST );
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => 'username',
			'label' 	 => 'Username',
			'required'   => true,
			'filters'    => array( 'StringTrim' ),
            'validators' => array
			(
            	'Alnum',
                array( 'Regex', false, array('/^[a-z0-9\.\-]{3,}$/' ) )
            )
		) ) );
		$form->addElement( new Zend_Form_Element_Password( array
		(
			'name' 		 => 'password',
			'label'	 	 => 'Password',
			'required'	 => true,
			'filters'    => array( 'StringTrim' ),
            'validators' => array( 'NotEmpty' )
		) ) );
		$form->addElement( new Zend_Form_Element_Submit( array( 'name' => 'submit-button', 'label' => 'Login' ) ) );

		$u = new User;
		if( count( $this->_request->getPost() ) && $form->isValid( $this->_request->getPost() ) )
		{
			try
			{
				$u->login( $this->_request->getPost('username'), $this->_request->getPost('password') );
			}
			catch( Zend_Auth_Exception $e )
			{
				throw new Exception( 'login failed', 1 );
			}
			$this->_redirect( '/' );
		}
		$this->view->form = $form;
	}
}