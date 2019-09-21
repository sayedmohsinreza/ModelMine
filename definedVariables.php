<?php
define('APP_NAME', 'ModelMine');
define('APP_SUBTITLE', 'An application to mine models from GITHUB');
define('APP_DETAILS', 'The '.APP_NAME.' helps you to search for model based repositories (.uml, .xmi etc.). For example, you can search for .uml based repositories. The option is not integrate in Advanced Search in GitHub Website. Also Think of it the way, you want to search a repository that has language: Java and repository has model files(.uml or .xmi etc), then this app will help you to find those repository. Just like searching on GitHub, you sometimes want to see a few pages of search results so that you can find the item that best meets your needs. To satisfy that need, the '.APP_NAME.' provides best result based on search criterias.');



define('GITHUB_CREDENTIALS', array(	
	 array('username'=>'GITHUB_USERNAME', 'password' => 'GITHUB_PASSWORD'),
	 array('username'=>'GITHUB_USERNAME_2', 'password' => 'GITHUB_PASSWORD_2')
	)
);

define('GITHUB_INSTRUCTION',  '<div class="alert alert-danger">Note: This Application is develped based on GitHub Search API. The Search API has a custom rate limit. For requests using Basic Authentication, OAuth, or client ID and secret, you can make up to <b>30 requests per minute</b>. For unauthenticated requests, the rate limit allows you to make up to <b>10 requests per minute</b>. Also  To satisfy customer need, the GitHub Search API provides up to <b>1,000 results</b> for each search and those results can be fetched using <b>5000 requests</b> in <b>1 hour</b> For More details, visit <a href="https://developer.github.com/v3/search/#rate-limit" target="_blank">https://developer.github.com/v3/search/#rate-limit</a>.</div>');


?>
