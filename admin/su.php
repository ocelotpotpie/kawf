<?php

$user->req("ForumAdmin");

if(!isset($aid)) {
    Header("Location: /admin/");
    Header("Location: /admin/?message=" . urlencode("No aid!"));
    exit();
}

$user = new AccountUser;
$user->find_by_aid((int)$aid);
if (!$user->valid()) {
    $error = "invalid_aid";
    Header("Location: /admin/?message=" . urlencode("Invalid aid $aid"));
    exit();
}

$user->setcookie();
Header("Location: /");

?>
