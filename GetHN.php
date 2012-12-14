<?php
date_default_timezone_set('UTC');
require_once __DIR__.'/Fetcher.php';
require_once __DIR__.'/Model.php';
require_once __DIR__.'/Parser.php';

$fetcher = new Fetcher();
$fetcher->fetch();

$parser = new Parser($fetcher->getDom());

$parser->extractLinks();

foreach ($parser->getLinks() as $id => $link)
    if (!Model::instance()->hasLink($id))
        Model::instance()->saveLink($id, $link);
    else
        Model::instance()->updateLink($id, $link['points'], $link['comments']);
