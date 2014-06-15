<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class Users implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'users' ) )->bind('users');
            return $controller;
        }

        public function users(Request $request, Application $app) {
            
            return $app['twig']->render('users.twig', array(
            ));
        }
        
        
    }
}
