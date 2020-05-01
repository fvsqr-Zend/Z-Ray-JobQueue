<?php

namespace ZendServerJobQueue;

$zre = new \ZRayExtension('JobQueue');
$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$zre->setMetadata(array(
    'logo' => __DIR__ . DIRECTORY_SEPARATOR . 'logo.png',
    'actionsBaseUrl' => $uri
));

function shutdown() {
}

if (extension_loaded('Zend Job Queue') ) {

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'JobQueue.php';

    $jq = new JobQueue();

    if (\ZendJobQueue::getCurrentJobId()) {

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