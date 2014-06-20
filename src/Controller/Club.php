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
    
    class Club implements ControllerProviderInterface {
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/{id}/", array($this, 'index'))->assert('id', '\d+')->bind('club');
            $controller->post("/{id}/", array($this, 'save'))->assert('id', '\d+');
            return $controller;
        }

        public function index(Application $app, $id) {

            $club = $this->getClub($app, $id);
            $form = $this->getForm($app, $club);
            
            return $app['twig']->render('club.twig', array(
                'form' => $form->createView(),
                'club' => $club
            ));
            
        }
        
        public function save(Application $app, $id) {
            
            $club = $this->getClub($app, $id);
            
            $form = $this->getForm($app, $club);
            $form->bind($app['request']);
            
            if ($form->isValid()) {

                
                $app['orm.em']->persist($club);
                $app['orm.em']->flush();
                
                return $app->redirect($app['url_generator']->generate('clubs'));
            } 
            
            return $app['twig']->render('club.twig', array(
                'form' => $form->createView(),
                'club' => $club
            ));
            
        }
        
        private function getClub(Application $app, $id) {
            if (0 == $id) {
                return new \Model\Club();
            } 
            return $app['orm.em']->getRepository('Model\Club')->findOneById($id);
        }
        
        private function getForm(Application $app, \Model\Club $club) {
            return $app['form.factory']->create(new \WizzieProgress\Form\ClubType(), $club);
        }
        
    }
}
