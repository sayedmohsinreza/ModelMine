<?php
include "header.php";

$collapse_class = 'collapse';
$searchTB_text = '';
$extension_text ='';

if(isset($_POST['searchButton'])){
    $collapse_class = 'collapse';
    $searchTB_text = $_POST['searchTB'];
}


if(isset($_POST['advancedSearchButton'])){
    $collapse_class = '';
    $searchTB_text = $_POST['searchTB'];
    $extension_text = $_POST['extension'];
  
}


print '

<form name ="searchForm" method="post">';
print '<div class="alert alert-secondary">
<b>Custom Extension Search</b>
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




$start_page = 11;
$end_page = 1000;

for ($i=$start_page; $i < $end_page; $i++) { 

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
      

      $insert = $conn->query("INSERT INTO `list_repos`(`id`, `page`, `github_serial`, `repos_id`, `full_name`, `commits`, `contributors`, `json_output`, `created_at`) VALUES (null, '".$i."', ".((($i-1)*100)+$key).", '".$repo_result['id']."', '".$repo_result['full_name']."', '0', '0', '".$repo_json."', null )");
      if($insert <= 0){continue;}


      $total_commits_count = 0; 
      $commit_page=1;
      while (1) {
         $data_commits = array( 'page' => $commit_page, 'per_page'=> 100);
        $commits_json = callAPI('GET', $repo_result['url'].'/commits',$data_commits, $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);
        $commits_result = json_decode($commits_json, true);
        $commits_count = count($commits_result);
        $total_commits_count = $total_commits_count + $commits_count;

        if($commits_count==0){
          break;
        }
        $commit_page = $commit_page + 1;
      }
     
      

      $total_contributors_count = 0; 
      $contributor_page=1;
      while (1) {
          $data_contributors= array( 'page' => $contributor_page, 'per_page'=> 100);
          $contributors_json = callAPI('GET', $repo_result['url'].'/contributors', $data_contributors, $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);
          $contributors_result = json_decode($contributors_json, true);
          $contributors_count = count($contributors_result);
          $total_contributors_count = $total_contributors_count+ $contributors_count;

          if($contributors_count==0){
            break;
          }
          $contributor_page = $contributor_page + 1;
      }

      $update = $conn->query("UPDATE `list_repos` SET `commits`='".$total_commits_count."', `contributors`= '".$total_contributors_count."' WHERE repos_id =  ".$repo_result['id']);
    
      
      print '<tr>
      <td></td>
      <td>'.$repo_result['name'].'</td>
      <td>'.$repo_result['owner']['login'].'</td>
      <td>'.$repo_result['size'].'</td>
      <td>'.$repo_result['stargazers_count'].'</td>
      <td>'.$repo_result['created_at'].'</td>
      <td> '.$total_contributors_count.' </td>
      <td> '.$total_commits_count.' </td>
      <td>'.$repo_result['language'].'</td>';

  print '<td><a target="_blank" href="'.$repo_result['html_url'].'" title="View Record" data-toggle="tooltip">'.font_awesome_icon("fa fa-eye").'</a> <a target="_blank" href="'.$repo_result['url'].'" title="View API Record" data-toggle="tooltip">'.font_awesome_icon("fa fa-external-link").'</a>

  </td></tr>';


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