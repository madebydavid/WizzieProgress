<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class Error implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'index' ) )->bind('error');
            return $controller;
        }

        public function index(Request $request, Application $app) {
            
            return $app['twig']->render('error.twig', array(
                'errorMessage' => $request->get('errorMessage')
            ));
        }
        
        
    }
}
