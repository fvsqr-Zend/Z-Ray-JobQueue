<?php
namespace ZendServerJobQueue;

use Zend\EventManager\EventInterface as Event;

class Module extends \ZRay\ZRayModule
{
    
    public function config()
    {
        
        return array(
            'extension' => array(
                'name' => 'JobQueue'
            ),
            'defaultPanels' => array(
                'jobList' => false,
                'jobWorkerDetail' => false
            ),
            'panels' => array(
                'customJobDetail' => array(
                    'display' => true,
                    'logo' => 'logo.png',
                    'menuTitle' => 'New Jobs',
                    'panelTitle' => 'New Jobs created by current Request',
                    'resources' => array(
                    )
                ),
                
                'customJobWorkerDetail' => array(
                    'display' => true,
                    'logo' => 'logo.png',
                    'menuTitle' => 'Job Info',
                    'panelTitle' => 'Job Info',
                    'resources' => array(
                    )
                )
                
            )
        );
    }
}
