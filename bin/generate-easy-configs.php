<?php

use TomasVotruba\EasyStan\EasyConfig\EasyConfigGenerator;

require __DIR__ . '/../vendor/autoload.php';

// for testing the config generator

$easyConfigGenerator = new EasyConfigGenerator();
$easyConfigGenerator->generate();
