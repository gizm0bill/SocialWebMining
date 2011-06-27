<?php

use App\Service\Worker;
use App\Model\CampaignData;
use App\Model\CampaignAttributes;
use App\Model\Campaign;

/**
 * request parameter 'id' always specifies a campaign id :)
 */
class Management_CampaignController extends Zend_Controller_Action
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

	/**
	 * @todo add to form class
	 * @param string $name form name
	 * @param array $campaign arry with campaign data from model
	 * @param bool $isAdd for addAction remove attributes cause they are too much worries
	 * @return Zend_Form
	 */
	private function _makeCampaignForm( $campaign = null, $name = 'campaignedit', $isAdd=false )
	{
		$form = new Zend_Form();
		$form->setMethod( Zend_Form::METHOD_POST );
		$form->setName( $name );

		// add campaign elemnts and optionaly values
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => Campaign::getCols()->title,
			'label' 	 => 'Title',
			'class'		 => 'large',
			'required'   => true,
			'filters'    => array( 'StringTrim' ),
            'validators' => array( 'NotEmpty' )
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

		if( $campaign )
		{
			// set values for base info
			$form->addElement( new Zend_Form_Element_Hidden( array
			(
				'name' 		 => Campaign::getCols()->id,
				'required'   => true,
				'filters'    => array( 'StringTrim' ),
	            'validators' => array( 'Alnum', 'NotEmpty' ),
				'decorators' => array( 'ViewHelper' )
			) ) );
			$form->setDefaults( $campaign );
		}

		if( empty( $campaign['attrs'] ) && !$isAdd ) // set some default null array for one attribute entry
			$campaign['attrs'] = array( array( 'val' => null, 'attr' => null ) );


		if( isset($campaign['attrs']) ) foreach( $campaign['attrs'] as $k => $attr ) // add attributes
		{
			$valElem = new Zend_Form_Element_Text( array
			(
				'name'		 => CampaignAttributes::getCols()->val."$k",
				'label'	 	 => 'Attribute value',
				'class'		 => 'medium',
				'filters'    => array( 'StringTrim' ),
				'value'		 => $attr['val'],
			) );
			$valElem->setBelongsTo('attributes');

			$attrElem = new Zend_Form_Element_Select( array
			(
				'name' 		 => CampaignAttributes::getCols()->attr."$k",
				'label'	 	 => 'Attribute name',
				'class'		 => 'medium',
				'multioptions' => Campaign::getAttributeList(),
				'value'		 => $attr['attr'],
				'multiple'	 => false
			) );
			$attrElem->setBelongsTo('attributes');

			$form->addDisplayGroup( array( $attrElem, $valElem ), "attributes$k", array( "class" => "attributes" ) );
			$attrs = $form->getDisplayGroup( "attributes$k" );
        	$attrs->setDecorators( array
        	(
                    'FormElements',
                    'Fieldset',
                    array( 'HtmlTag', array( 'tag'=>'dl' ) )
	        ));
		}
		if( !$isAdd )
			$form->addElement( new Zend_Form_Element_Button( array
			(
				"name" => "add-attribute",
				"label" => "Add attribute",
				"class" => "add-attribute",
				'decorators' => array( 'ViewHelper', array( 'HtmlTag', array( 'tag'=> 'dl', 'class' => 'clear' ) ) )
			)));

		return $form;
	}

	public function addAction()
	{
		$form = $this->_makeCampaignForm( null, 'campaignadd', true );
		if( count( $this->_request->getPost() ) && $form->isValid( $this->_request->getPost() ) )
		{
			$c = new Campaign();
			$data = $this->_request->getPost();
			$iid = $c->insert( array
			(
				Campaign::getCols()->title 	=> $data[Campaign::getCols()->title],
				Campaign::getCols()->from 	=> $data[Campaign::getCols()->from],
				Campaign::getCols()->to 	=> $data[Campaign::getCols()->to],
				Campaign::getCols()->idUser	=> Zend_Auth::getInstance()->getIdentity()->id
			) );
			$this->_helper->flashMessenger( 'Campaign succefully added!' );
			$this->_helper->redirector( 'edit', 'campaign', 'management', array( 'id' => $iid ) );
		}

		$form->addElement( new Zend_Form_Element_Submit( array
		(
			'name' 	=> 'submit-button',
			'label' => 'Add campaign',
			'class' => 'fr',
			'decorators' => array( 'ViewHelper' )
		) ) );

		$this->view->form = $form;

		$this->view->headScript()->appendFile($this->view->baseUrl( '/scripts/jquery.js' ));
		$this->view->headScript()->appendFile($this->view->baseUrl( '/scripts/jquery-ui.js' ));
		$this->view->headScript()->appendFile($this->view->baseUrl( '/scripts/app/management/campaign/add.js' ));
		$this->view->headLink( array( 'type' => 'text/css', 'rel' => 'stylesheet', 'href' => $this->view->baseUrl( '/styles/jquery-ui.css' ) ) );

	}

	public function editAction()
	{
		$postData = $this->_request->getPost();
		if( count( $postData ) && isset($postData['attributes']) )
		{
			// take attrs first cause we need to reset the form.. myeah
			// make nice array because zend form is incapable of doin that anymore
			$postAttrs = array
			(
				CampaignAttributes::getCols()->attr => array(),
				CampaignAttributes::getCols()->val => array(),
			);
			foreach( $postData['attributes'] as $k => $v )
			{
				if( strpos( $k, CampaignAttributes::getCols()->attr ) !== false )
					$postAttrs[CampaignAttributes::getCols()->attr][] = $v;
				if( strpos( $k, CampaignAttributes::getCols()->val ) !== false )
					$postAttrs[CampaignAttributes::getCols()->val][] = $v;
			}

			// add attributes
			$ca = new CampaignAttributes;
			$ca->delete( array( CampaignAttributes::getCols()->idCampaign => $postData[Campaign::getCols()->id] ) );
			foreach( $postAttrs[CampaignAttributes::getCols()->attr] as $k => $attr )
			{
				try
				{
					if( trim( ( $val = $postAttrs[CampaignAttributes::getCols()->val][$k] ) ) != '' )
						$ca->insert( array
						(
							CampaignAttributes::getCols()->attr => $attr,
							CampaignAttributes::getCols()->val => $val,
							CampaignAttributes::getCols()->idCampaign => $postData[Campaign::getCols()->id]
						));
				}
				catch( \Exception $e )
				{
					$formErrorMessages = array( $e->getMessage() );
				}
			}
		} // endif post data

		$campaign = $this->_getFullCampaignByReqId();
		$this->view->campaign = (object) $campaign;

		$form = $this->_makeCampaignForm( $campaign );

		// set error messages from attributes if any
		if( isset($formErrorMessages) ) $form->setErrorMessages($formErrorMessages);

		if( count( $postData ) && $form->isValid( $postData ) )
		{
			// update campaign
			$c = new Campaign;
			$where = $c->getAdapter()->quoteInto( Campaign::getCols()->id . " = ? ", $postData[Campaign::getCols()->id] );
			$c->update( array
			(
				Campaign::getCols()->title 	=> $postData[Campaign::getCols()->title],
				Campaign::getCols()->from 	=> $postData[Campaign::getCols()->from],
				Campaign::getCols()->to 	=> $postData[Campaign::getCols()->to],
			), $where );
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
		$this->view->headScript()->appendFile($this->view->baseUrl( '/scripts/app/management/campaign/edit.js' ));
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
		$this->view->campaign = $campaign = $c->find( $campaignId )->current();

		$worker = new Worker();
		$this->view->status =
			$worker->getFullStatus( 'marketing', 'cli', 'campaign', array( 'id' => $campaign[Campaign::getCols()->id] ) );
	}

	public function workersStartAction()
	{
		$campaign = $this->_getFullCampaignByReqId();
		$this->view->campaign = (object) $campaign;

		$worker = new Worker();
		$worker->start( 'marketing', 'cli', 'campaign', array( 'id' => $campaign[Campaign::getCols()->id] ) );

		$this->_helper->redirector( 'edit', 'campaign', 'management', array( 'id' => $campaign[Campaign::getCols()->id] ) );
	}

	public function workersStopAction()
	{
		$campaign = $this->_getFullCampaignByReqId();
		$this->view->campaign = (object) $campaign;

		$worker = new Worker();
		$worker->stop( 'marketing', 'cli', 'campaign', array( 'id' => $campaign[Campaign::getCols()->id] ) );

		$this->_helper->redirector( 'edit', 'campaign', 'management', array( 'id' => $campaign[Campaign::getCols()->id] ) );
	}
}