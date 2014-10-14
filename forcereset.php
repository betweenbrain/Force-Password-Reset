<?php defined('_JEXEC') or die;

/**
 * File       forcereset.php
 * Created    10/14/14 10:04 AM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2014 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v2 or later
 */
class plgUserForcereset extends JPlugin
{

	/**
	 * Constructor.
	 *
	 * @param   object &$subject The object to observe
	 * @param   array  $config   An optional associative array of configuration settings.
	 *
	 * @since   1.0.0
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->app = JFactory::getApplication();
		$this->db  = JFactory::getDbo();

		// Load the language file on instantiation
		$this->loadLanguage();
	}

	/**
	 * Triggered whenever a user is successfully logged in
	 *
	 * @access    public
	 *
	 * @param     array $options Array with: remember, return, entry_url, action, user - JUser Object, responseType
	 *
	 * @return    boolean
	 * @since     1.0.0
	 */
	public function onUserAfterLogin($options)
	{

		if ($this->app->isAdmin())
		{
			return false;
		}

		if ($options['user']->lastResetTime == '0000-00-00 00:00:00')
		{
			$this->forcePasswordReset($options['user']->id);
		}

		return true;
	}

	private function forcePasswordReset($id)
	{
		$sql               = new stdClass;
		$sql->id           = $id;
		$sql->requireReset = 1;

		$this->db->updateObject('#__users', $sql, 'id');

	}
}
