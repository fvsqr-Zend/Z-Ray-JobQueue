<?php

namespace ZendServerJobQueue;

$zre = new \ZRayExtension('JobQueue');
$zre->setMetadata(array(
    'logo' => __DIR__ . DIRECTORY_SEPARATOR . 'logo.png',
    'actionsBaseUrl' => $_SERVER['REQUEST_URI'] 
));

if (extension_loaded('Zend Job Queue')) {
 
    $q = new \ZendJobQueue();
    #if (!$q->getCurrentJobId()) {
        
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

        $zre->attachAction('getJobDetail', 'ZendJobQueue::createHttpJob', function($jobId){ error_log('init 666 '."\n", 3, '/tmp/init'); return 'xyz' . $jobId . $jobId . $jobId; });
        
        error_log('init 6 '."\n", 3, '/tmp/init');
    #}
}