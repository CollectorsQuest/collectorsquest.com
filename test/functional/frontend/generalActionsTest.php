<?php

include dirname(__FILE__) . '/../../bootstrap/frontend.php';

$browser = new cqTestFunctional(new sfBrowser());

cqTest::resetClasses('Collector');
cqTest::loadFixtures(array('01_test_collectors/'));

$browser->
    get('/general/index')->

    with('request')->begin()->
    info(' 1.1 Homepage is ok')->
      isParameter('module', 'general')->
      isParameter('action', 'index')->
    end()->

    with('response')->begin()->
      isStatusCode(200)->
      checkElement('h1', '/NOW ON DISPLAY/i')->
    end();

$browser->
    info(' 1.2 login')->
    get('/login')->
    with('response')->
      click('Login', array('login'=>array(
        'username'=> 'ivan.tanev',
        'password'=> 'ivanpass',
      )))->
    with('response')->begin()->
      isRedirected()->
      followRedirect()->
    end()->
    with('user')->
      isAuthenticated();

$browser->
    info(' 1.3 Logout')->
    get('/logout')->
    with('response')->begin()->
      isRedirected()->
      followRedirect()->
    end()->
    with('user')->
      isAuthenticated(false);
