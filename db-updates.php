<?php
use MapasCulturais\App;

$app = App::i();
$em = $app->em;
$conn = $em->getConnection();
return [
    'create project rede cultura viva' => function() use ($app) {
        $project = new MapasCulturais\Entities\Project;
        $owner = $app->repo('Agent')->find(1); // usuario admin

        $project->owner = $owner;
        $project->name = 'Rede Cultura Viva';
        $project->useRegistrations = true;
        $project->categories = ['Ponto de Cultura', 'Pontão de Cultura'];
        $project->registrationFrom = new \DateTime('2015-01-01');
        $project->registrationTo = new \DateTime('1000-01-01');
        $project->type = 9;
        $project->save(true);
    },
];
