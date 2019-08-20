<?php
require_once 'vendor/autoload.php';

printf("\e[42m set up browser and session\e[0m\n");
$driver = new \Behat\Mink\Driver\Selenium2Driver('chrome');
$driver->setDesiredCapabilities([
    'chromeOptions' => [
        "w3c" => false
    ]
]);
$chromeSession = new \Behat\Mink\Session($driver);
$mink = new \Behat\Mink\Mink();
$mink->registerSession('selenium', $chromeSession);
$mink->setDefaultSessionName('selenium');

// navigate to the test page
$session = $mink->getSession();
$session->visit('https://www.lovecrafts.com.dothraki.lovecrafts.cool');

// get DOM
$homePage = $session->getPage();
$session->wait(10000, 'document.readyState == "complete"');

// find sign up/in element
$createAccount = $homePage->find('css', 'ul.quicklinks > li.login-register > a');

if (!$createAccount) {
    throw new Exception("Cannot find");
}
$createAccount->click();

// filling in sign up form with randomasing email
$number = rand(0, 56768);
$email = "james.bond{$number}@gmail.com";

$session->wait(3000);
$emailField = $homePage->find('css', '#email-register');
$emailField->setValue($email);

$passField = $homePage->find('css', '#pass-register');
$passField->setValue('bondjamesbond');

$session->wait(2000);
$createAccountButton = $homePage->find('css', '.create-account');
$createAccountButton->click();
$session->wait(5000);


// find user-account dropdown
$myProfile = $homePage->find('css', 'ul.quicklinks > li.user-account.has-dropdown > a');
$myProfile->click();
$session->wait(3000);

$myProfileDrop = $homePage->find('css', 'ul > li.user-account.has-dropdown.expanded > div > ul > li:nth-child(3) > a');
$myProfileDrop->click();
$session->wait(3000);

// verifying sign up
$userEmail = $homePage->find('css', 'div.box-content > p:nth-child(2)');
$currentEmail = $userEmail->getText();

if($currentEmail != $email) {
    throw new Exception("Wrong user!");
}

// search
$searchField = $homePage->find('css', '#site-header > div.wrapper > form > input[type=search]');
$searchField->setValue('millamia');

$searchButton = $homePage->find('css','.search-button');
$searchButton->click();

$categoryPage = $session->getPage();
$actualTitle = $categoryPage->find('css', '#product-list-container > div > div.main > ol > li:nth-child(8) > figure > a > figcaption > div.texts > h3');

if (!$actualTitle) {
    throw new Exception("Cannot find");
}
$expectedTitle = "MillaMia Naturally Soft Super Chunky 10 Ball Value Pack";
if ($actualTitle->getText() != $expectedTitle) {
    throw new Exception("Something went wrong");
}
else {
    echo '***WOW***';
}

$session->wait(3000);

$session->stop();
printf("\e[1;37;42m Test passed.\e[0m\n");
?>

