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
		$form->addElement( new Zend_Form_Element_Text( array
		(
			'name' 		 => Campaign::getCols()->title,
			'label' 	 => 'Title',
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
		$form->addElement( new Zend_Form_Element_Submit( array( 'name' => 'submit-button', 'label' => 'Save' ) ) );
		$form->setDefaults( $campaign );
		$this->view->form = $form;

		//$srv = new  Zend_Service_Twitter_Search('json');
		//var_dump( $srv->search('zend',array( 'lang' => 'en' ) ) );
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
		$exec = "/usr/bin/php ".PUBLIC_PATH ."/index.php -m marketing -c cli -a %s -p 'id=".$campaign['id_campaign']."&%s' 2>&1 &";

		$attrs = $errors = $outputs = array();
		foreach( $campaign['attrs'] as $attr )
			$attrs[$attr['attr']] = $attr['val'];

		foreach( $this->_workerlist as $action => $title )
		{
			switch( $action )
			{
				case 'campaigntw' :
					$args = "hashtag=".addslashes( $attrs['twitter_hashtag'] );
					break;
			}

			var_dump( passthru( exec( sprintf( $exec, $action, $args ), $r ) ) );
			if( $r != 0 )
			{
				$errors[] = "Starting worker for '$action' failed";
				$outputs[] = implode( "\n", $x );
			}
		}
		$this->view->errors = $errors;
		$this->view->outputs = $outputs;
	}
}