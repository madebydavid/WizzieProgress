<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class Locations implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'locations' ) )->bind('locations');
            return $controller;
        }

        public function locations(Request $request, Application $app) {
            
            return $app['twig']->render('locations.twig', array(
            ));
        }
        
        
    }
}
