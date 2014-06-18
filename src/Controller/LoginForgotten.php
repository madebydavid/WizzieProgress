<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;

    class LoginForgotten implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            
            $controller->get('/dialog', array($this, 'dialog'))->bind('login-forgotten-dialog');
            
            
            return $controller;
        }
        
        
        public function dialog(Application $app) {
            return $app['twig']->render('dialogs/login-forgotten.twig', array(
                'error' => $app['security.last_error']($app['request']),
                'lastUsername' => $app['session']->get('_security.last_username'),
                'form' => $this->getForm($app)->createView()
            ));
        }
        
        private function getForm(Application $app) {
            return $app['form.factory']->create(new \WizzieProgress\Form\LoginForgottenType());
        }
        
        
    }
}
