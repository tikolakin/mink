<?php
require_once 'vendor/autoload.php';

printf("\e[42m set up browser and session\e[0m\n");
$driver = new \Behat\Mink\Driver\Selenium2Driver('chrome');
$chromeSession = new \Behat\Mink\Session($driver);
$mink = new \Behat\Mink\Mink();
$mink->registerSession('selenium', $chromeSession);
$mink->setDefaultSessionName('selenium');

// navigate to the test page
$session = $mink->getSession();
$session->visit('http://www.lovecrafts.com');

// get DOM
$homePage = $session->getPage();
// find link in DOM
$feedNavItems = $homePage->find('css', '.lc-feed-navitems');
$link = $feedNavItems->findLink("Yarn");
if (!$link) {
  throw new Exception("Cannot find link");
}
// click link to navigate to another page
$link->click();
$session->wait(10000, 'document.readyState == "complete"');
// get DOM of a new page
$categoryPage = $session->getPage();
$actualTitle = $categoryPage->find('css', 'h1');
if (!$actualTitle) {
  throw new Exception("Cannot find link");
}
$expectedTitle = "Knitting & Crochet Yarn & Wool";
if ($actualTitle->getText() != $expectedTitle) {
  throw new Exception(sprintf("Cannot verify that actual title '%s' matches with expected '%s'", $actualTitle, $expectedTitle));
}
$session->stop();
printf("\e[1;37;42m Test passed.\e[0m\n");