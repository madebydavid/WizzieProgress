<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class Index implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'index' ) )->bind('index');
            return $controller;
        }

        public function index(Request $request, Application $app) {
            
            return $app['twig']->render('index.twig', array(
            ));
        }
        
        
    }
}
