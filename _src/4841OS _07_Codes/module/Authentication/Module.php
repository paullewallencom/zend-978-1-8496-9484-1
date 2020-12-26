<?php

namespace Authentication;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
		
		$eventManager->attach(MvcEvent::EVENT_ROUTE, function (MvcEvent $event) {
			// Get the database adapter
			$dbAdapter = $event->getApplication()
					           ->getServiceManager()
						       ->get('db');

			// Our example is an in memory database, so the table never exists, but
			// better sure than sorry
			$result = $dbAdapter->query(
					"SELECT name FROM sqlite_master WHERE type='table' AND name='users'"
			)->execute();

			if ($result->current() === false) {
				try {
					// The user table doesn't exist yet, so let's just create 
				    // some sample data
					$result = $dbAdapter->query("
						CREATE TABLE `users` (
							`id` INT(10) NOT NULL,
							`username` VARCHAR(20) NOT NULL,
							`password` CHAR(32) NOT NULL,
							`email` VARCHAR(250) NOT NULL,
							PRIMARY KEY (`id`)
						)
					")->execute();
					
					// Now insert some users
					$dbAdapter->query("
						INSERT INTO `users` VALUES 
						(1, 'admin', '". md5("adminpassword"). "', 'admin@example.com')
					")->execute();
					
					// Now insert some users
					$dbAdapter->query("
						INSERT INTO `users` VALUES 
						(2, 'test', '". md5("testpassword"). "', 'testuser@example.org')
					")->execute();
					
					\Zend\Debug\Debug::dump(
							"Users admin/adminpassword and test/testpassword created."
					);
				} catch (\Exception $e) {
					\Zend\Debug\Debug::dump($e->getMessage());
				}
			}
		});
		
		// Do this event when dispatch is triggered, on the highest priority (1)
		$eventManager->attach(MvcEvent::EVENT_DISPATCH, function (MvcEvent $event) {
			// We don't have to redirect if we are in a 'public' area, so don't
			// even try
			if ($event->getRouteMatch()->getMatchedRouteName() === 'authentication')
				return;
			
			// See if we are authenticated, if not lets redirect to our login page
			if ($event->getApplication()->getServiceManager()->get('AuthService')->isAuthenticated() === false) {
				// Get the response from the event
				$response = $event->getResponse();
				
				// Clear current headers and add our Location redirection
				$response->getHeaders()
						 ->clearHeaders()
						 ->addHeaderLine('Location', '/authentication');

				// Set the status code to redirect
				$response->setStatusCode(302)
						 ->sendHeaders();

				// Don't forget to exit
				exit;
			}
		}, 1);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
