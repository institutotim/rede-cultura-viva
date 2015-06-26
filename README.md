# Rede Cultura Viva

Ferramenta permitirá mapeamento e articulação entre Pontos de Cultura e funcionará como rede social de troca e colaboração.

[Mais detalhes](www.brasil.gov.br/cultura/2015/06/primeira-versao-de-cadastro-de-pontos-de-cultura-e-apresentada)

## Guia de desenvolvimento

A construção da ferramenta foi utilizado o [Mapas Culturais](https://github.com/hacklabr/mapasculturais). Esse guia não existiria sem a ajuda do @rafaelchavesfreitas.

A partir da experiência adquirida durante a construção do tema, alguns detalhes me chamaram atenção na estrutura do Mapas Culturais e apresentarei a seguir.

### Iniciando

A maneira mais rápida de iniciar o desenvolvimento é clonar o repositório do mapas culturais e subir uma isntância do ubuntu com todas as dependências instaladas.

```git clone git@github.com:hacklabr/mapasculturais.git```

```cd mapasculturais```

```vagrant up```

Depois de executar os comandos você poderá verificar no endereço abaixo o mapas culturais funcionando:

``` http://localhost:8000 ```

### Tema em branco

O tema é simples e sem conteúdo extra, além de não possuir funcionalidades herdads do tema pai. Apenas as chamadas obrigatórias fazem parte do tema. 

[Clique aqui para baixar](https://github.com/institutotim/rede-cultura-viva/releases/tag/0.1)

### Api php do Mapas Culturais

Para facilitar a leitura do código do Mapas Culturais gerar documentação é essencial, ó comando abaixo deve ser executado dentro de um instalação válida do Mapas Culturais.

``` ./scripts/apigen.sh ```

Ela irá gerar uma documentação parecida com a do [doctrine](http://www.doctrine-project.org/api/orm/2.4/).

### db-updates.php

Sobre como o arquivo funciona dentro do tema e como executar o que tem nele.

### Habilitar página de conteúdo

Você precisa adicionar ao ``` Theme.php ``` o seguinte método:

```
    protected static function _getTexts(){
        return array(
            'site: name' => App::i()->config['app.siteName'],
            'site: description' => App::i()->config['app.siteDescription']
		);
	}
```

É necessário também adicionar a pasta ``` pages ``` ao diretório do tema. Onde ficará os textos.

Outros dois arquivos precisam ser criados ``` _left.md ``` e ``` _right.md ```.

## Documentação do Tema

### Frontend

* [node](http://nodejs.org/)
* [npm](http://npmjs.com/)
* [browserify](http://browserify.org)
* [stylus](http://learnboost.github.io/stylus/)
* [foundation](http://foundation.zurb.com/docs/) (só css)
* [mithril](http://lhorie.github.io/mithril/) (só js)

### Iniciando

Configurando este tema na instalação do mapas culturais recém criada.

Depois de configurar, você precisar compilar os assets. Existem outras tarefas configuradas, você pode ver todas em ``` gulpfile.js ```

``` cd [pasta do tema] ```

``` npm install ```

``` ./node_modules/gulp/bin/gulp.js fy ```

### Usando autenticação oAuth

Configurando o [Login Cidadão](https://github.com/PROCERGS/login-cidadao/tree/dev) para autenticar no mapa.


### Controllers

Explicar o conceito de controller disponível no tema e como utilizar para receber chamadas.

#### Registrando

```
$app->registerController('sample', 'CulturaViva\SampleController');

```

### Verificando se usuário está logado

```
if (!$app->user->is('guest')) {
	// usuário está logado
}
```

### Registrando Metadado

```
$def = new \MapasCulturais\Definitions\Metadata('cultura_viva_ids', [
	'label' => 'Id do Agente, Agente Coletivo e Registro da inscrição',
	'private' => true
]);
$app->registerMetadata($def, 'MapasCulturais\Entities\User');

```

### Mapa de entidades dos Pontos de cultura

Explicar que um Ponto de cultura ou Pontão é composto por 3 agentes. Agente Individual(responsável), Agente Coletivo(entidade) e Agente individual(o ponto de cultura propriamente dito). Se o Ponto tiver sede própria deverá ser criado um novo espaço.

### Compartilhar variáveis do Mapas Culturais no JS

Adicionar o método a seguir no Theme.php:

``` 
protected function _printJsObject($var_name = 'CulturaViva', $print_script_tag = true) {

        if ($print_script_tag)
            echo "\n<script type=\"text/javascript\">\n";

        echo " var {$var_name} = " . json_encode($this->jsObject) . ';';

        if ($print_script_tag)
            echo "\n</script>\n";
    }
```

Chamar o método recém criado no head do html:

```
function head() {
...
	$this->_printJsObject();
}
```


Para passar a variável do PHP para o JS:

```
$this->jsObject['ids'] = json_decode($app->user->cultura_viva_ids);
```

