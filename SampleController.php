<?php
namespace MapasCulturais\Themes\SCDC;
use MapasCulturais\Traits;
use MapasCulturais\Entities;

class SampleController extends \MapasCulturais\Controller{
	use Traits\Controller;



		// upload do agent - avatar
		// upload do agent coletivo - avatar
		// upload de portfolio - 


    function GET_test(){
        var_dump($this->data);
	}

	function ALL_saveJson1() {
		$this->requireAuthentication();
		var_dump($this->data);


		$app = \MapasCulturais\App::i();
		$agent = $app->repo('Agent')->find($this->data['id']);
		$profile = $app->user->profile;
		$agents = $app->user->agents; // agente e agente coletivo

		$profile->dump();
	}
}