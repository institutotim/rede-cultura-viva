<!DOCTYPE html>
<html>
    <head>
        <title><?php $this->getTitle(isset($entity) ? $entity : null) ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php $this->head(isset($entity) ? $entity : null); ?>
    </head>
    <body <?php $this->bodyProperties() ?> >
        <?php $this->bodyBegin(); ?>