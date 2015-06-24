<?php
namespace CulturaViva;
use MapasCulturais\App;

class Theme extends \MapasCulturais\Theme{

	static function getThemeFolder() {
		return __DIR__;
	}

	protected function _init() {
		$app = App::i();

		$this->_layout = 'home';

		$app->applyHookBoundTo($this, 'theme.init:before');

		$app->hook('<<GET|POST|PUT|DELETE>>(<<*>>.<<*>>):before', function() use ($app){
			if($this->id !== 'sample' && $this->id !== 'site' && $this->id != 'auth'){
				$app->pass();
			}
		});

		$this->_enqueueStyles();
		$this->_enqueueScripts();

		$app->applyHookBoundTo($this, 'theme.init:after');
	}

	public function register() {
		$app = App::i();

		$app->registerController('sample', 'MapasCulturais\Themes\SCDC\SampleController');
		$def = new \MapasCulturais\Definitions\Metadata('_jsons1', [
			'label' => 'json 1',
			'private' => true,
			'validations'=> [
				'v::json()' => 'json inválido'
			]
		]);

		$app->registerMetadata($def, 'MapasCulturais\Entities\Agent');
	}

	protected function _enqueueScripts(){
		$app = App::i();

		$app->applyHookBoundTo($this, 'theme.enqueueScripts:before');

		$this->enqueueScript('main', 'main', 'assets/js/main.js');

		$app->applyHookBoundTo($this, 'theme.enqueueScripts:after');
	}

	protected function _enqueueStyles(){
		$app = App::i();

		$app->applyHookBoundTo($this, 'theme.enqueueStyles:before');

		$this->enqueueStyles('main', 'main', 'assets/css/main.css');

		$app->applyHookBoundTo($this, 'theme.enqueueStyles:after');
	}

	function head() {
		parent::head();

		$app = App::i();

		$this->printStyles('main');

		$app->applyHook('mapasculturais.styles');

		$this->_printJsObject();

		$this->printScripts('main');

		$app->applyHook('mapasculturais.scripts');
	}
}