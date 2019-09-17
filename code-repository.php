<?php
include "header.php";

$collapse_class = 'collapse';
$searchTB_text = '';
$extension_text ='';
$size_text ='';
$created_text ='';
$language_text ='';
$commits_text ='';

$stars_text ='';
$contributors_text ='';
if(isset($_POST['searchButton'])){
    $collapse_class = 'collapse';
    $searchTB_text = $_POST['searchTB'];
}


if(isset($_POST['advancedSearchButton'])){
    $collapse_class = '';
    $searchTB_text = $_POST['searchTB'];
    $extension_text = $_POST['extension'];
    $size_text =$_POST['size'];
    $created_text =$_POST['created'];
    $language_text =$_POST['language'];
    $commits_text =$_POST['commits'];

    $stars_text =$_POST['stars'];
    $contributors_text =$_POST['contributors'];
  
}


print '

<form name ="searchForm" method="post">';
print '<div class="alert alert-secondary">
<b>Custom Search</b>
<div class="input-group mb-3">
  <input name="searchTB" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="keywords for search" value ="'.$searchTB_text.'" >
  <div class="input-group-append">
  <button name="searchButton" type="submit" class="btn btn-primary" value="1">Search</button>
  <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseAdvancedSearch" aria-expanded="false" aria-controls="collapseAdvancedSearch">Advanced Search</button>
  </div>
</div>
</div>';

print '<div id="collapseAdvancedSearch" class="'.$collapse_class.' alert alert-secondary">
<hr>
<b>Primary Variables</b>
<hr>
<div class="row"><div class="col-sm-3">
'.create_input('Extension','text','extension',$extension_text,'','','Help: matches code files with a certain file extension.', false).'
</div><div class="col-sm-3">
'.create_input('Size','text','size',$size_text,'','','Help: matches repositories that are at least 30000 (30 MB).', false).'
</div><div class="col-sm-3">
'.create_input('Created','text','created',$created_text,'','','Help: matches repositories that were created after mentioned date format (YYYY-MM-DD).', false).'
</div><div class="col-sm-3">
'.create_input('language','text','language',$language_text,'','','Help: matches repositories with the word "rails" that are written in JavaScript.', false).'
</div></div>

<div class="row"><div class="col-sm-3">
'.create_input('Number of Commits','text','commits',$commits_text,'','','Help: matches code files with at least number of commits.', false).'
</div><div class="col-sm-3">

</div><div class="col-sm-3">

</div><div class="col-sm-3">

</div></div>


<hr>
<b>Secondary Variables</b>
<hr>
<div class="row"><div class="col-sm-3">
'.create_input('Number of stars','text','stars',$stars_text,'','','Help: matches repositories with the at least mentioned stars.', false).'
</div><div class="col-sm-3">
'.create_input('Number of contributors','text','contributors',$contributors_text,'','','Help: matches repositories with the at least mentioned contributors.', false).'
</div><div class="col-sm-3">

</div><div class="col-sm-3">

</div></div>



<button name="advancedSearchButton" type="submit" class="btn btn-primary" value="1">Advanced Search</button>

</div>';




print '</form>';


if(isset($_POST['searchButton']) || isset($_POST['advancedSearchButton'])){
$header_array = array('Accept: application/vnd.github.machine-man-preview+json', 'User-Agent: Awesome-Octocat-App');
print "<table id='mytable'  class='table table-striped table-bordered' style='width:100%'><thead><tr>";
print '<th>#</th><th>Name</th><th>Owner Name</th><th>Size</th><th>Stars</th><th>Created At</th><th>Contributions</th><th>Commits</th><th>Language</th>';
print '<th>Action</th></tr></thead><tbody>';

if(!empty($_POST['extension'] )){$searchTB_text.=' extension:'.$extension_text;}


$repo_result_count = 0;
$max_repository = 10;
$repositories = array();
for ($i=1; $i <1000; $i++) { 

    $data = array('q'=> $searchTB_text, 'page' => $i, 'per_page'=> 100);

    $url = 'https://api.github.com/search/code';
    $result_json = callAPI('GET', $url, $data, $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);
    $result = json_decode($result_json, true);

    if(isset($result['items'])){
      $data = $result['items'];
    }else{
      $data = array();
    }



  foreach ($data as $key => $file) {
      $repo = $file['repository'];

      $repo_json = callAPI('GET', $repo['url'], array(), $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);
      $repo_result = json_decode($repo_json, true);
      
      if(in_array($repo_result['full_name'], $repositories)){continue;}
      
      if($repo_result['size'] < $size_text && $size_text != ''){continue;}
      if($repo_result['created_at'] < $created_text && $created_text != ''){continue;}
      if($repo_result['language']!=$language_text && $language_text != ''){continue;}
      if($repo_result['stargazers_count'] < $stars_text && $stars_text != ''){continue;}

      $total_commits_count = 0; 
      $commit_page=1;
      while (1) {
        $data_commits = array( 'page' => $commit_page, 'per_page'=> 100);
        $commits_json = callAPI('GET', $repo_result['url'].'/commits',$data_commits, $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);
        $commits_result = json_decode($commits_json, true);
        $commits_count = count($commits_result);
        $total_commits_count = $total_commits_count + $commits_count;
        if($commits_text==''){$commits_value = 0;}else{$commits_value = $commits_text;}
        if($commits_count==0 || $total_commits_count > $commits_value){
          break;
        }
        $commit_page = $commit_page + 1;
      }
      if($total_commits_count < $commits_value && $commits_text != ''){continue;}
      

      $total_contributors_count = 0; 
      $contributor_page=1;
      while (1) {
        $data_contributors= array( 'page' => $contributor_page, 'per_page'=> 100);
          $contributors_json = callAPI('GET', $repo_result['url'].'/contributors', $data_contributors, $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);
          $contributors_result = json_decode($contributors_json, true);
          $contributors_count = count($contributors_result);
          $total_contributors_count = $total_contributors_count+ $contributors_count;
          if($contributors_text==''){$contributors_value = 0;}else{$contributors_value = $contributors_text;}
          if($contributors_count==0 || $total_contributors_count > $contributors_text){
            break;
          }
          $contributor_page = $contributor_page + 1;
      }

      if($total_contributors_count < $contributors_value && $contributors_text != ''){continue;}

      print '<tr>
      <td></td>
      <td>'.$repo_result['name'].'</td>
      <td>'.$repo_result['owner']['login'].'</td>
      <td>'.$repo_result['size'].'</td>
      <td>'.$repo_result['stargazers_count'].'</td>
      <td>'.$repo_result['created_at'].'</td>
      <td> '.(($total_contributors_count < $contributors_text)? $total_contributors_count : '>'.$contributions_text).' </td>
      <td> '.(($total_commits_count < $commits_text)? $total_commits_count :'>'.$commits_text) .' </td>
      <td>'.$repo_result['language'].'</td>';

  print '<td><a target="_blank" href="'.$repo_result['html_url'].'" title="View Record" data-toggle="tooltip">'.font_awesome_icon("fa fa-eye").'</a> <a target="_blank" href="'.$repo_result['url'].'" title="View API Record" data-toggle="tooltip">'.font_awesome_icon("fa fa-external-link").'</a>

  </td></tr>';
    $repositories[] = $repo_result['full_name'];
    $repo_result_count++;
    if($repo_result_count >= $max_repository){
    break;
  }
}

  if($repo_result_count >= $max_repository){
    break;
  }


}//for loop

print "</tbody></table>";

Print 'Total Count: '. $result['total_count'];
}

print '
<script type="text/javascript"> 
$(document).ready(function() {
     var t = $(\'#mytable\').DataTable({
      "order": [[ 3, "desc" ]]
      });
      t.on( \'order.dt search.dt\', function () {
        t.column(0, {search:\'applied\', order:\'applied\'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

} );
</script>
';
     
include "footer.php";     
?>