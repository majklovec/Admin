<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/cakephp/admin
 */

class AdminController extends AdminAppController {

	/**
	 * List out all models and plugins.
	 */
	public function index() {
		$plugins = Admin::getModels();
		$counts = array();

		// Gather record counts
		foreach ($plugins as $plugin) {
			foreach ($plugin['models'] as $model) {
				if ($model['installed']) {
					$object = Admin::introspectModel($model['class']);

					if ($object->hasMethod('getCount')) {
						$count = $object->getCount();
					} else {
						$count = $object->find('count', array(
							'cache' => array($model['class'], 'count'),
							'cacheExpires' => '+24 hours'
						));
					}

					$counts[$model['class']] = $count;
				}
			}
		}

		$this->set('plugins', $plugins);
		$this->set('counts', $counts);
	}

	/**
	 * Analyze all models and output important information.
	 */
	public function models() {
		$this->set('plugins', Admin::getModels());
	}

	/**
	 * Display all configuration grouped by system.
	 */
	public function config() {
		$config = Configure::read();
		ksort($config);
		unset($config['debug']);

		$this->set('configuration', $config);
	}

}