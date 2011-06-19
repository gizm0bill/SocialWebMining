<?php

use App\Model\CampaignData;
use App\Model\CampaignAttributes;
use App\Model\Campaign;

/**
 * request parameter 'id' always specifies a campaign id :)
 */
class Marketing_CampaignController extends Zend_Controller_Action
{
	private function userId()
	{
		return Zend_Auth::getInstance()->getIdentity()->id;
	}

	public function indexAction()
	{
		$c = new Campaign;
		$this->view->campaigns =
			$c->fetchAll( $c->select()->where( Campaign::getCols()->idUser . " = ? ", $this->userId() ) );
	}

	/**
	 * TODO add to a service
	 */
	private function _getFullCampaignByReqId()
	{
		$c = new Campaign;
		$campaign = current( $c->fetchAllWithAttributes
		(
			$c->select()->where( Campaign::getCols()->id . " = ? ", $this->_request->getParam('id') )
		) );
		return $campaign;
	}

	public function editAction()
	{
		$campaign = $this->_getFullCampaignByReqId();
		$this->view->campaign = (object) $campaign;

		$form = new Zend_Form();
		$form->setMethod( Zend_Form::METHOD_POST );
		$form->setName( 'campaignedit' );
		// add campaign values
		$form->addElement( new Zend_Form_Element_Hidden( array
		(
			'name' 		 => Campaign::getCols()->id,
			'required'   => true,
			'filters'    => array( 'StringTrim' ),
            'validators' => array( 'Alnum', 'NotEmpty' ),
			'decorators' => array( 'ViewHelper' )
		) ) );
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => Campaign::getCols()->title,
			'label' 	 => 'Title',
			'class'		 => 'large',
			'required'   => true,
			'filters'    => array( 'StringTrim' ),
            'validators' => array( 'Alnum', 'NotEmpty' )
		) ) );
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => Campaign::getCols()->from,
			'label'	 	 => 'Starting',
			'required'	 => true,
			'filters'    => array( 'StringTrim' ),
            'validators' => array( 'NotEmpty' )
		) ) );
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => Campaign::getCols()->to,
			'label'	 	 => 'Ending',
			'required'	 => true,
			'filters'    => array( 'StringTrim' ),
            'validators' => array( 'NotEmpty' )
		) ) );
		$form->setDefaults( $campaign );

		// add attributes
		foreach( $campaign['attrs'] as $k => $attr )
		{
			$valElem = new Zend_Form_Element_Text( array
			(
				'name'		 => CampaignAttributes::getCols()->val."[$k]",
				'label'	 	 => 'Attribute value',
				'class'		 => 'medium',
				'filters'    => array( 'StringTrim' ),
				'value'		 => $attr['val'],
			) ) ;
			$attrElem = new Zend_Form_Element_Select( array
			(
				'name' 		 => CampaignAttributes::getCols()->attr."[$k]",
				'label'	 	 => 'Attribute name',
				'class'		 => 'medium',
				'multioptions' => Campaign::getAttributeList(),
				'value'		 => $attr['attr']
			) );

			$form->addDisplayGroup( array( $attrElem, $valElem ), "attributes$k" );
			$attrs = $form->getDisplayGroup( "attributes$k" );
        	$attrs->setDecorators( array
        	(
                    'FormElements',
                    'Fieldset',
                    array( 'HtmlTag', array( 'tag'=>'div' ) )
	        ));
		}

		if( count( $this->_request->getPost() ) && $form->isValid( $this->_request->getPost() ) )
		{
			$c = new Campaign();
			$data = $this->_request->getPost();
			$c->update( array
			(
				Campaign::getCols()->title 	=> $data[Campaign::getCols()->title],
				Campaign::getCols()->from 	=> $data[Campaign::getCols()->from],
				Campaign::getCols()->to 	=> $data[Campaign::getCols()->to],
			), array( Campaign::getCols()->id => $this->_request->getPost( 'id_campaign' ) ) );
		}

		$form->addElement( new Zend_Form_Element_Submit( array
		(
			'name' 	=> 'submit-button',
			'label' => 'Save settings',
			'class' => 'clear',
			'decorators' => array( 'ViewHelper' )
		) ) );

		$this->view->form = $form;

		$this->view->headScript()->appendFile($this->view->baseUrl( '/scripts/jquery.js' ));
		$this->view->headScript()->appendFile($this->view->baseUrl( '/scripts/jquery-ui.js' ));
		$this->view->headScript()->appendFile($this->view->baseUrl( '/scripts/app/marketing/campaign/edit.js' ));
		$this->view->headLink( array( 'type' => 'text/css', 'rel' => 'stylesheet', 'href' => $this->view->baseUrl( '/styles/jquery-ui.css' ) ) );
	}

	private $_workerlist = array
	(
		"campaigntw" => "Twitter worker"
	);

	public function workersStatusAction()
	{
		$campaignId = $this->_request->getParam('id');
		$this->view->campaignId = $campaignId;
		if( is_null($campaignId) )
		{
			$this->view->statuses = array();
			return false;
		}

		$c = new Campaign;
		$this->view->campaign = $c->find( $campaignId )->current();

		$statuses = array();
		foreach( $this->_workerlist as $action => $title )
		{
			$fn = APPLICATION_PATH."/../data/workers/"
				. $this->_request->getModuleName() . "-"
				. $action . "-"
				. $campaignId . ".ini";
			if( file_exists( $fn ) )
			{
				$workerCfg = new Zend_Config_Ini( $fn );
				$statuses[ $title ] = $workerCfg->status;
			}
		}

		$this->view->statuses = $statuses;
	}

	public function workersStartAction()
	{
		$campaign = $this->_getFullCampaignByReqId();
		$this->view->campaign = (object) $campaign;

		// make cli request command
		$exec = "(/usr/bin/php ".PUBLIC_PATH ."/index.php -m marketing -c cli -a %s -p 'id=".$campaign['id_campaign']."&%s' &) > /dev/null";

		$attrs = $errors = $outputs = array();

		// get all campaign attributes
		foreach( $campaign['attrs'] as $attr )
			$attrs[$attr['attr']] = $attr['val'];

		// make worker requests based on registered _workerList
		foreach( $this->_workerlist as $action => $title )
		{
			switch( $action )
			{
				case 'campaigntw' :
					$args = "hashtag=".addslashes( $attrs['twitter_hashtag'] );
					break;
			}

			exec( sprintf( $exec, $action, $args ), $x, $r );
			$outputs[] = "Started worker for '$title'";
		}
		$this->view->errors = $errors;
		$this->view->outputs = $outputs;
	}

	public function workersStopAction()
	{
		$campaign = $this->_getFullCampaignByReqId();
		$this->view->campaign = (object) $campaign;

		$outputs = array();
		foreach( $this->_workerlist as $action => $title )
		{
			$fn = APPLICATION_PATH."/../data/workers/"
				. $this->_request->getModuleName() . "-"
				. $action . "-"
				. $campaign['id_campaign'] . ".ini";

			// get ini config, merge with stop status, and write
			$cfg = new Zend_Config_Ini( $fn, null, true );
			$cfg->merge( new Zend_Config( array( 'status' => 'stopped' ) ) );
			$newCfg = new Zend_Config_Writer_Ini();
			$newCfg->setConfig( $cfg );
			$newCfg->write( $fn );

			$outputs[] = "Stopped worker for '$title'";
		}
		$this->view->outputs = $outputs;
	}
}