<?php
// /app/app.php


$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Dominikzogg\Silex\Provider\DoctrineOrmManagerRegistryProvider());
$app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions) use ($app) {
    $extensions[] = new Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension($app['doctrine']);
    return $extensions;
}));

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
        'translator.messages' => array(),
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/view',
    'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
));

$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type"      => "yml",
                "namespace" => "Model",
                "path"      => realpath(__DIR__."/../config/doctrine"),
            ),
        ),
    ),
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
        'db.options' => $app['config']['db.options']
));

$app->register(new Silex\Provider\SessionServiceProvider());

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

$app['encoder'] = new \Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder();
$app['security.firewalls'] = array(
    'global' => array(
        'pattern' => '^.*$',
        'anonymous' => true,
        'form' => array('login_path' => '/login', 'check_path' => '/login/check'),
        'logout' => array('logout_path' => '/login/logout', 'target_url' => '/'),
        'users' => $app->share(function($app) {
            $inMemoryUsers = new Symfony\Component\Security\Core\User\InMemoryUserProvider(array( 
                $app['config']['admin.options']['username'] => array(
                    'roles' => array('ROLE_ADMIN'),
                    'password' => $app['encoder']->encodePassword($app['config']['admin.options']['password'], null)
                )
            ));

            $dbUsers = new Symfony\Bridge\Doctrine\Security\User\EntityUserProvider(
                $app['doctrine'],
                '\Model\User',
                'email'
            );

            return new Symfony\Component\Security\Core\User\ChainUserProvider(array(
                $inMemoryUsers, $dbUsers
            ));

        })
    )
    
);

$app->register(new Silex\Provider\SecurityServiceProvider(array()));

$app['security.access_rules'] = array(
    array('^/user(s)?', 'ROLE_ADMIN'),
    array('^/location(s)', 'ROLE_ADMIN'),
    array('^/student(s)', 'ROLE_USER'),
    array('^/login', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/login/forgotten/dialog', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/.*', 'ROLE_USER')
);

$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH')
);

$app['security.encoder_factory'] = $app->share(function ($app) {
    return new Symfony\Component\Security\Core\Encoder\EncoderFactory(array(
        'Model\User' => $app['security.encoder.digest'],
        'Symfony\Component\Security\Core\User\User' => $app['security.encoder.digest']
    ));
});

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
        'http_cache.cache_dir' => __DIR__.'/../cache/',
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = @$app['config']['swiftmailer.options'];


require __DIR__.'/routes.php';

return $app;
