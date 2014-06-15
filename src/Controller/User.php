<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Validator\Constraints as Assert;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\NoResultException;
    use Doctrine\ORM\NonUniqueResultException;
    use Symfony\Component\Form\FormError;
    
    class User implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/{id}/", array($this, 'index'))->assert('id', '\d+')->bind('user');
            $controller->post("/{id}/", array($this, 'save'))->assert('id', '\d+');
            return $controller;
        }

        public function index(Application $app, $id) {

            $user = $this->getUser($app, $id);
            $form = $this->getForm($app, $user);
            
            return $app['twig']->render('user.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
            
        }
        
        public function save(Application $app, $id) {
            
            $user = $this->getUser($app, $id);
            
            $form = $this->getForm($app, $user);
            $form->bind($app['request']);
            
            if ($form->isValid()) {

                $user->setSalt(sha1(uniqid(mt_rand(), true)));
                $user->setPassword(
                    $app['security.encoder_factory']->getEncoder($user)
                        ->encodePassword($user->getPassword(), $user->getSalt())
                );
                   
                
                $app['orm.em']->persist($user);
                $app['orm.em']->flush();
                
                return $app->redirect($app['url_generator']->generate('users'));
            } 
            
            return $app['twig']->render('user.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
            
        }
        
        private function getUser(Application $app, $id) {
            if (0 == $id) {
                return new \Model\User();
            } 
            return $app['orm.em']->getRepository('Model\User')->findOneById($id);
        }
        
        private function getForm(Application $app, \Model\User $user) {
            return $app['form.factory']->create(new \WizzieProgress\Form\UserType(), $user);
        }
        
    }
}
