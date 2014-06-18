<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Form\FormError;

    class LoginForgotten implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            
            $controller->get('/dialog', array($this, 'dialog'))->bind('login-forgotten-dialog');
            $controller->post('/dialog', array($this, 'request'))->bind('login-forgotten-request');
            
            return $controller;
        }
        
        private function getForm(Application $app) {
            return $app['form.factory']->create(new \WizzieProgress\Form\LoginForgottenType());
        }
        
        public function dialog(Application $app) {
            return $app['twig']->render('dialogs/login-forgotten.twig', array(
                'lastUsername' => $app['session']->get('_security.last_username'),
                'form' => $this->getForm($app)->createView()
            ));
        }
        
        public function request(Application $app) {
            
            $form = $this->getForm($app);
            $form->bind($app['request']);
            
            if ($form->isValid()) {
                
                $user = $app['orm.em']->getRepository('Model\User')
                        ->findOneByEmail($form['email']->getData());
                
                if (null === $user) {
                    
                    $form->get('email')->addError(
                        new FormError('No user found for that email address')
                    );
                    
                } else {
                    
                    $hash = hash('sha256', uniqid(mt_rand(), true), true);
                    $hash = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
                    
                    $user->setResetToken($hash);
                    $user->setResetRequestDate(new \DateTime('now'));
                    
                    $app['orm.em']->persist($user);
                    $app['orm.em']->flush();
                    
                    /* this template closes the dialog */
                    return $app['twig']->render('dialogs/close.twig');
                    
                }
                
            }
            
            return $app['twig']->render('dialogs/login-forgotten.twig', array(
                'lastUsername' => $form['email']->getData(),
                'form' => $form->createView()
            ));
            
        }
        
    }
}
