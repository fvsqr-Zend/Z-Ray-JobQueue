<?php

namespace ZendServerJobQueue;

$zre = new \ZRayExtension('JobQueue');
$zre->setMetadata(array(
    'logo' => __DIR__ . DIRECTORY_SEPARATOR . 'logo.png',
    'actionsBaseUrl' => $_SERVER['REQUEST_URI'] 
));

if (extension_loaded('Zend Job Queue')) {
 
    $q = new \ZendJobQueue();
        
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'JobQueue.php';
    
    $q = new \ZendJobQueue();
    $jq = new JobQueue();
    
    $zre->setEnabledAfter('ZendJobQueue::ZendJobQueue');
    
    $zre->traceFunction(
       'ZendJobQueue::createHttpJob', 
        function() {},
        array(
            $jq,
            'afterJobStart'
        )
    );
}