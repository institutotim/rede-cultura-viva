<?php
namespace CulturaViva;
use MapasCulturais\App;
use MapasCulturais\Definitions;

class Theme extends \MapasCulturais\Theme{

	static function getThemeFolder() {
		return __DIR__;
	}

	protected function _init() {
		$app = App::i();

		$this->_layout = 'home';

		$app->applyHookBoundTo($this, 'theme.init:before');

		$app->hook('<<GET|POST|PUT|DELETE>>(<<*>>.<<*>>):before', function() use ($app){
			$actions = array('sample', 'site', 'auth', 'user');
			// /agents/single/{id} : PUT
			if (!in_array ($this->id, $actions)) {
				$app->pass();
			}
		});


		$this->_enqueueStyles();
		$this->_enqueueScripts();

        $app->applyHookBoundTo($this, 'theme.init:after');

        $app->hook('auth.createUser:after', function($user, $data) use ($app) {
            $project = $app->repo('Project')->find(1); //By(['owner' => 1], ['id' => 'asc'], 1);

            // define o agente padrão (profile) como rascunho
            $app->disableAccessControl(); // não sei se é necessário desabilitar
			$user->profile->status = \MapasCulturais\Entities\Agent::STATUS_DRAFT;
            $user->profile->save(true);

            // criando o agente coletivo vazio
            $entidade = new \MapasCulturais\Entities\Agent;
			$entidade->parent = $user->profile;
			$entidade->name = '';
            $entidade->status = \MapasCulturais\Entities\Agent::STATUS_DRAFT;
            $entidade->save(true);

            // criando a inscrição
            //$projeto = $projects[0];

            // relaciona o agente responsável, que é o proprietário da inscrição
            $registration = new \MapasCulturais\Entities\Registration;
            $registration->owner = $user->profile;
			$registration->project = $project;
			// inserir que as inscricoes online estao ativadas
			$registration->save(true);

			$user->cultura_viva_ids = json_encode([
				'agente_individual' => $user->profile->id,
				'agente_coletivo'   => $entidade->id,
				'inscricao'         => $registration->id
			]);
			$user->save(true);

            // relaciona o agente coletivo
            $registration->createAgentRelation($entidade, 'entidade', false, true, true);
			$app->enableAccessControl();

			//$app->em->flush(); sem o true no save, ele cria um transaction no bd
        });
		
		if (!$app->user->is('guest')) {
			$this->jsObject['ids'] = json_decode($app->user->cultura_viva_ids);
		}
	}

	protected $agent_metadata = [
		'chave'=> [
			'label'=>'Minha chave azul',
			'private' => true,
            'validations' => array(
                'required' => 'A chave azul é obrigatório.'
			)
		],
		'chave2'=> ['label'=>'Minha chave azul 2', 'private' => true, ],
	];

	public function register() {
		$app = App::i();

		$app->registerController('sample', 'CulturaViva\SampleController');
		//$url = $app->createUrl('site');
		$def = new \MapasCulturais\Definitions\Metadata('cultura_viva_ids', [
			'label' => 'Id do Agente, Agente Coletivo e Registro da inscrição',
			'private' => true
		]);
		$app->registerMetadata($def, 'MapasCulturais\Entities\User');

		foreach ($this->agent_metadata as $k => $v) {
			$def = new \MapasCulturais\Definitions\Metadata($k, $v);
			$app->registerMetadata($def, 'MapasCulturais\Entities\Agent', 1);
			$app->registerMetadata($def, 'MapasCulturais\Entities\Agent', 2);
		}

		$metalist_definition = new Definitions\MetaListGroup('projetos', [
			'title' => ['label' => 'Nome'],
			'value' => [
				'label' => 'Projeto',
				'validations' => [
					'required' => 'O json dos projetos é obrigatório',
					"v::json()" => "Json inválido"
				]
			]
		]);
		$app->registerMetaListGroup('agent', $metalist_definition);

	}

	protected function _enqueueScripts(){
		$app = App::i();

		$app->applyHookBoundTo($this, 'theme.enqueueScripts:before');

		$this->enqueueScript('main', 'main', 'js/main.js');
		$app->applyHookBoundTo($this, 'theme.enqueueScripts:after');
	}

	protected function _enqueueStyles(){
		$app = App::i();

		$app->applyHookBoundTo($this, 'theme.enqueueStyles:before');

		$this->enqueueStyle('main', 'main', 'css/main.css');

		$app->applyHookBoundTo($this, 'theme.enqueueStyles:after');
	}

	function head() {
		parent::head();

		$app = App::i();

		$this->printStyles('main');

		$app->applyHook('mapasculturais.styles');

		$this->_printJsObject();

	}

	function footer() {
		$this->printScripts('main');
		$app = App::i();

		$app->applyHook('mapasculturais.scripts');
	}

    protected static function _getTexts(){
        return array(
            'site: name' => App::i()->config['app.siteName'],
			'site: description' => App::i()->config['app.siteDescription']
		);
	}

    protected function _printJsObject($var_name = 'CulturaViva', $print_script_tag = true) {

        if ($print_script_tag)
            echo "\n<script type=\"text/javascript\">\n";

        echo " var {$var_name} = " . json_encode($this->jsObject) . ';';

        if ($print_script_tag)
            echo "\n</script>\n";
    }


}