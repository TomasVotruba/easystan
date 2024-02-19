<?php

use TomasVotruba\EasyStan\EasyConfig\EasyConfigGenerator;

require __DIR__ . '/../vendor/autoload.php';

// for testing the config generator
// @todo run on repository build, not dynamically

$easyConfigGenerator = new EasyConfigGenerator();
$easyConfigGenerator->generate();
