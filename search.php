<?php
include "header.php";




print '<form name ="searchForm" method="post">';
print '<div class="alert alert-secondary">
<div class="input-group mb-3">
  <div class="input-group-prepend">
     <select name="typeCB" class="custom-select" id="inputGroupSelect01">
    <option selected>Choose...</option>
    <option value="1">Repositories</option>
    <option value="2">User</option>
  </select>
  </div>
  <input name="searchTB" type="text" class="form-control" aria-label="Text input with segmented dropdown button">
  <div class="input-group-append">
  <button name="searchButton" type="submit" class="btn btn-outline-primary" value="1">Search</button>
  </div>
</div>
  <small><b>Example 1:</b> tetris+language:assembly, <b>Example 2:</b> topic:ruby+topic:rails</small>
</div>';

print '</form>';


if(isset($_POST['searchButton'])){

require_once(__DIR__ . '/github-php-client-master/client/GitHubClient.php');


$client = new GitHubClient();
$client->setCredentials("sayedmohsinreza", "#Denver123#");

$repository_url = '/search/repositories';
$data = array('q', $_POST['searchTB']);

$respones = $client->repos->searchRepositories($data);

print '<pre>';
print_r($respones);
print '</pre>';

}


     
include "footer.php";     
?>