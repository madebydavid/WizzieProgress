<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;

    class Users implements ControllerProviderInterface {
        
        const USERS_PER_PAGE = 10;
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'index' ) )->bind('users');
            return $controller;
        }

        public function index(Application $app) {
            
            /* simple pagination */
            $pageIndex = (int)$app['request']->get('pageIndex');
            
            $users = $app['orm.em']->getRepository('Model\User')
                ->findBy(array(), array(), \Controller\Users::USERS_PER_PAGE, \Controller\Users::USERS_PER_PAGE * $pageIndex);
            
            $totalUsersCount = $app['orm.em']->createQuery('SELECT COUNT(q.id) FROM Model\User q')
                ->getSingleScalarResult();
            
            $maxPageIndex = (int)($totalUsersCount / \Controller\Users::USERS_PER_PAGE);
            
            return $app['twig']->render('users.twig', array(
                'users' => $users,
                'pageIndex' => $pageIndex,
                'maxPageIndex' => $maxPageIndex
            ));
            
        }
        
    }
}
