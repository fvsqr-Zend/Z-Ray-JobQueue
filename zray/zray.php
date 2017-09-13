<?php

namespace ZendServerJobQueue;

$zre = new \ZRayExtension('JobQueue');
$zre->setMetadata(array(
    'logo' => __DIR__ . DIRECTORY_SEPARATOR . 'logo.png',
    'actionsBaseUrl' => $_SERVER['REQUEST_URI'] 
));

function shutdown() {
}

if (extension_loaded('Zend Job Queue')) {
 
    $q = new \ZendJobQueue();
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'JobQueue.php';
    
    $jq = new JobQueue();
    
    if ($q->getCurrentJobId()) {
        
        $zre->setEnabledAfter('ZendServerJobQueue\shutdown');
        
        register_shutdown_function('ZendServerJobQueue\shutdown');
        $zre->traceFunction(
            'ZendJobQueue::setCurrentJobStatus',
            function() {},
            array(
                $jq,
                'workerStatus'
            )
        );
        
        $zre->traceFunction(
            'ZendServerJobQueue\shutdown',
            function() {},
            array(
                $jq,
                'workerShutdown'
            )
        );
    }
    else {
        
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
}