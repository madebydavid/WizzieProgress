<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Form\FormError;

    class LoginForgotten implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            
            /* request password reset email handling */ 
            $controller->get(
                '/request', 
                array($this, 'requestDialog')
            )->bind('login-forgotten-request-dialog');
            
            $controller->post(
                '/request', 
                array($this, 'requestSubmit')
            )->bind('login-forgotten-request-submit');
            
            /* resetting of the password from link in email */
            $controller->get(
                '/reset/{token}', 
                array($this, 'resetDialog')
            )->assert('token', '.{43}')
             ->bind('login-forgotten-reset-dialog');
            
            $controller->post(
                '/reset/{token}', 
                array($this, 'resetSubmit')
            )->assert('token', '.{43}')
             ->bind('login-forgotten-reset-submit');
            
            
            return $controller;
        }
        
        private function getRequestForm(Application $app) {
            return $app['form.factory']->create(
                new \WizzieProgress\Form\LoginForgottenType()
            );
        }
        
        private function getResetForm(Application $app) {
            return $app['form.factory']->create(
                new \WizzieProgress\Form\LoginResetType()
            );
        }
        
        
        public function requestDialog(Application $app) {
            return $app['twig']->render('dialogs/login-forgotten.twig', array(
                'lastUsername' => $app['session']->get('_security.last_username'),
                'form' => $this->getRequestForm($app)->createView()
            ));
        }
        
        public function requestSubmit(Application $app) {
            
            $form = $this->getRequestForm($app);
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
                    
                    
                    $message = \Swift_Message::newInstance()
                        ->setSubject('W2C3 Student Progress App - Password Reset Request')
                        ->setFrom($app['config']['email.options']['sender'])
                        ->setTo(array($user->getEmail()))
                        ->setBody(
                            $app['twig']->render(
                                'emails/password-reset.twig', 
                                array('user' => $user)
                            )
                        );
                    
                    $app['mailer']->send($message);
                    
                    
                    /* this template closes the dialog */
                    return $app['twig']->render('dialogs/close.twig');
                    
                }
                
            }
            
            return $app['twig']->render('dialogs/login-forgotten.twig', array(
                'lastUsername' => $form['email']->getData(),
                'form' => $form->createView()
            ));
            
        }
        
        public function resetDialog(Application $app, $token) {
            return $app['twig']->render('dialogs/login-reset.twig', array(
                'lastUsername' => $app['session']->get('_security.last_username'),
                'form' => $this->getResetForm($app)->createView()
            ));
        }
        
        public function resetSubmit(Application $app, $token) {
             
            $form = $this->getResetForm($app);
            $form->bind($app['request']);
            
            $user = $app['orm.em']->getRepository('Model\User')->findOneBy(
                    array(
                        'reset_token' => $token
                    )
            );
            
            if (null == $user) {
                $form->addError(new FormError('Invalid or expired reset token'));
            } else {
                $seconds = ((new \DateTime())->getTimeStamp() - $user->getResetRequestDate()->getTimeStamp());
                if ($seconds > $app['config']['admin.options']['resetPasswordTokenValidFor']) {
                    $form->addError(new FormError('Invalid or expired reset token'));
                }
            }
            
            if ($form->isValid()) {
                
                $user->setSalt(sha1(uniqid(mt_rand(), true)));
                $user->setPassword(
                        $app['security.encoder_factory']->getEncoder($user)
                        ->encodePassword(
                                $form['password']->getData(), $user->getSalt()
                        )
                );
                
                $user->setResetToken(null);
                $user->setResetRequestDate(null);
                
                $app['orm.em']->persist($user);
                $app['orm.em']->flush();
                
                return $app['twig']->render('dialogs/close.twig');
            }
            
            return $app['twig']->render('dialogs/login-reset.twig', array(
                'form' => $form->createView()
            ));
            
            
        }
        
    }
}
