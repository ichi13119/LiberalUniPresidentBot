<?php
$str = 'すもももももももものうち';

$mecab = new \Mecab\Tagger();
$nodes = $mecab->parseToNode($str);
foreach ($nodes as $n) {
    $items = $n->getFeature();
    echo $items . PHP_EOL;
}