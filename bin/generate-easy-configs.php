<?php

use TomasVotruba\EasyStan\EasyConfig\EasyConfigGenerator;

require __DIR__ . '/../vendor/autoload.php';

// @todo run on repository build, not dynamically

$easyConfigGenerator = new EasyConfigGenerator();
$easyConfigGenerator->generate(__DIR__ . '/../../config/easy_levels/');
