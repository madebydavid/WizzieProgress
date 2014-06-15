<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class Students implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'students' ) )->bind('students');
            return $controller;
        }

        public function students(Request $request, Application $app) {
            
            return $app['twig']->render('students.twig', array(
            ));
        }
        
        
    }
}
