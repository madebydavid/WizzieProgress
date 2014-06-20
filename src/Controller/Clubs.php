<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class Clubs implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'clubs' ) )->bind('clubs');
            return $controller;
        }

        public function clubs(Request $request, Application $app) {
            
            return $app['twig']->render('clubs.twig', array(
            ));
        }
        
        
    }
}
