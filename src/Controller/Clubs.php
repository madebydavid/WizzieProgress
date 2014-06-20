<?php

namespace Controller {
    
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class Clubs implements ControllerProviderInterface {
        
        const CLUBS_PER_PAGE = 10;
        
        public function connect(Application $app) {
            $controller = $app['controllers_factory'];
            $controller->get("/", array( $this, 'clubs' ) )->bind('clubs');
            return $controller;
        }

        public function clubs(Request $request, Application $app) {
            
            /* simple pagination */
            $pageIndex = (int)$app['request']->get('pageIndex');
            
            $clubs = $app['orm.em']->getRepository('Model\Club')
            ->findBy(array(), array(), \Controller\Clubs::CLUBS_PER_PAGE, \Controller\Clubs::CLUBS_PER_PAGE * $pageIndex);
            
            $totalClubsCount = $app['orm.em']->createQuery('SELECT COUNT(q.id) FROM Model\Club q')
            ->getSingleScalarResult();
            
            $maxPageIndex = (int)($totalClubsCount / \Controller\Clubs::CLUBS_PER_PAGE);
            
            return $app['twig']->render('clubs.twig', array(
                    'clubs' => $clubs,
                    'pageIndex' => $pageIndex,
                    'maxPageIndex' => $maxPageIndex
            ));
            
            
        }

        
        
    }
}
