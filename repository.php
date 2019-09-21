<?php
include "header.php";

$collapse_class = 'collapse';
$searchTB_text = '';
$size_text = '';
$stars_text = '';
$language_text = '';
$created_text = '';
$pushed_text = '';
if(isset($_POST['searchButton'])){
    $collapse_class = 'collapse';
}
if(isset($_POST['advancedSearchButton'])){
    $collapse_class = '';
}

if(isset($_POST['searchButton']) || isset($_POST['advancedSearchButton'])){
   $searchTB_text = $_POST['searchTB'];
    $size_text = $_POST['size'];
    $stars_text = $_POST['stars'];
    $language_text = $_POST['language'];
    $created_text = $_POST['created'];
    $pushed_text = $_POST['pushed'];
}

print GITHUB_INSTRUCTION;
print '<form name ="searchForm" method="post">';

print '<div class="alert alert-secondary">
Repository Search
<div class="input-group mb-3">
  <input name="searchTB" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="keywords for search" value ="'.$searchTB_text.'">
  <select class="custom-select" name="github_account" required>
    <option selected value="0">Choose GITHUB account</option>';
    foreach (GITHUB_CREDENTIALS as $key => $credential) {
      if(isset($_POST['github_account']) && $_POST['github_account'] == $key){$select_str = 'selected';}else{$select_str = '';}
      print '<option value="'.$key.'" '.$select_str.'>USERNAME: '.$credential['username'].', PASSWORD: '.substr($credential['password'], 0, 2).'*****'.substr($credential['password'], -2).'</option>';
    }
    
  print '</select>
  <div class="input-group-append">
  <button name="searchButton" type="submit" class="btn btn-primary" value="1">Search</button>
  <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseAdvancedSearch" aria-expanded="false" aria-controls="collapseAdvancedSearch">Advanced Search</button>
  </div>

</div>
<small id="BasicHelp" class="form-text text-muted">N.B. One or more search keywords. For example: UML.</small>
</div>';

print '<div id="collapseAdvancedSearch" class="'.$collapse_class.' alert alert-secondary">



<div class="row"><div class="col-sm-4">
'.create_input('Size','text','size',$size_text,'','','Help: matches repositories that are at least 30000 (30 MB).', false).'
</div><div class="col-sm-4">
'.create_input('Number of stars','text','stars',$stars_text,'','','Help: matches repositories with the at least 500 stars.', false).'
</div><div class="col-sm-4">
'.create_input('language','text','language',$language_text,'','','Help: matches repositories with the word "rails" that are written in JavaScript.', false).'
</div></div>


<div class="row"><div class="col-sm-4">
'.create_input('Created','text','created',$created_text,'','','Help: matches repositories that were created before 2011.', false).'
</div><div class="col-sm-4">
'.create_input('Pushed','text','pushed',$pushed_text,'','','Help: matches repositories with the word "css" that were pushed to after January 2013.', false).'
</div><div class="col-sm-4">

</div></div>

<!---
<div class="row"><div class="col-sm-3">
'.create_input('name','text','name','','','','Help: repositories with "jquery" in their name.', false).'
</div><div class="col-sm-3">
'.create_input('description','text','description','','','','Help: repositories with "jquery" in their name or description.', false).'
</div><div class="col-sm-3">
'.create_input('readme','text','readme','','','','Help: repositories mentioning "jquery" in their README file.', false).'
</div><div class="col-sm-3">
'.create_input('owner_name','text','owner_name','','','','Help: matches a specific repository name.', false).'
</div></div>
-->
<!--
<div class="row"><div class="col-sm-3">
'.create_input('Size','text','size',$size_text,'','','Help: matches repositories that are at least 30000 (30 MB).', false).'
</div><div class="col-sm-3">

'.create_input('Number of followers','text','followers','','','','Help: matches repositories with 10,000 or more followers ', false).'

</div><div class="col-sm-3">
'.create_input('Number of forks','text','forks','','','','Help: matches repositories with at least 205 forks.', false).'
</div><div class="col-sm-3">
'.create_input('Number of stars','text','stars','','','','Help: matches repositories with the at least 500 stars.', false).'
</div></div>

<div class="row"><div class="col-sm-3">
'.create_input('Created','text','created','','','','Help: matches repositories that were created before 2011.', false).'
</div><div class="col-sm-3">
'.create_input('Pushed','text','pushed','','','','Help: matches repositories with the word "css" that were pushed to after January 2013.', false).'
</div><div class="col-sm-3">
'.create_input('language','text','language',$language_text,'','','Help: matches repositories with the word "rails" that are written in JavaScript.', false).'
</div><div class="col-sm-3">
'.create_input('topic','text','topic','','','','Help: matches repositories that have been classified with the topic "jekyll.".', false).'
</div></div>
-->
<!---
<div class="row"><div class="col-sm-3">
'.create_input('Number of topics','text','topics','','','','Help: matches repositories that have more than three topics.', false).'
</div><div class="col-sm-3">
'.create_input('license','text','license','','','','Help: matches repositories that are licensed under Apache License 2.0.', false).'
</div><div class="col-sm-3">
'.create_select('Repository Type', 'type', array('public', 'private'), '', '', false, false, 'Help: matches whether a repository is public or private.', false).'
</div><div class="col-sm-3">
'.create_select('Mirror', 'mirror', array('Mirror', 'Not Mirrored'), '', '', false, false, 'Help: matches hether or not they\'re a mirror and are hosted elsewhere', false).'
</div></div>
<!---
<div class="row"><div class="col-sm-3">
'.create_select('Archieved', 'archieved', array('Yes', 'No'), '', '', false, false, 'Help: whether or not they\'re archived.', false).'
</div><div class="col-sm-3">
'.create_input('good-first-issues','text','good-first-issues','','','','Help: matches repositories with more than two issues labeled good-first-issue', false).'
</div><div class="col-sm-3">
'.create_input('help-wanted-issues','text','help-wanted-issues','','','','Help: matches repositories with more than four issues labeled help-wanted', false).'
</div><div class="col-sm-3">

</div></div>
-->


<button name="advancedSearchButton" type="submit" class="btn btn-primary" value="1">Advanced Search</button>

</div>';




print '</form>';


if(isset($_POST['searchButton']) || isset($_POST['advancedSearchButton'])){
    $header_array = array('Accept: application/vnd.github.machine-man-preview+json', 'User-Agent: Awesome-Octocat-App');

    if(!empty($_POST['size'] )){$searchTB_text.=' size:>='.$size_text;}
    if(!empty($_POST['stars'] )){$searchTB_text.=' stars>:'.$stars_text;}
    if(!empty($_POST['language'] )){$searchTB_text.=' language:'.$language_text;}
    if(!empty($_POST['created'] )){$searchTB_text.=' created:>='.$created_text;}
    if(!empty($_POST['pushed'] )){$searchTB_text.=' pushed:>='.$pushed_text;}

    $page =1;
    $count_repo = 0;
    $account_no = $_POST['github_account'];
    while (1) {
        $data = array('q'=> $searchTB_text, 'page' => $page, 'per_page'=> 100);
        $url = 'https://api.github.com/search/repositories';
        $result_json = callAPI('GET', $url, $data, $header_array, GITHUB_CREDENTIALS[$account_no]['username'], GITHUB_CREDENTIALS[$account_no]['password']);
        $result = json_decode($result_json, true);
        if(!isset($result['total_count'])){
          printf('<div class="alert alert-danger">Search operation is stopped. Reason is given below.</div>');
          lau($result);
        }
        if(isset($result['items'])){
          $data = $result['items'];
        }else{
          $data = array();
        }

        if($page==1){
          $total_count = $result['total_count'];
            $table_str= "<table id='mytable'  class='table table-striped table-bordered' style='width:100%'><thead><tr>";
            $table_str.= '<th>#</th><th>Name</th><th>Owner Name</th><th>Size</th><th>Stars</th><th>Watchers</th><th>Language</th><th>Created Date</th><th>Last Updated</th><th>Last Pushed</th>';
            $table_str.= '<th>Action</th></tr></thead><tbody>';
        }


          foreach ($data as $key => $row) {
              $table_str.= '<tr>
              <td></td>
              <td>'.$row['name'].'</td>
              <td>'.$row['owner']['login'].'</td>
              <td>'.$row['size'].'</td>
              <td>'.$row['stargazers_count'].'</td>
              <td>'.$row['watchers_count'].'</td>
              <td>'.$row['language'].'</td>
              <td>'.$row['created_at'].'</td>
              <td>'.$row['updated_at'].'</td>
              <td>'.$row['pushed_at'].'</td>
              ';


              $table_str.= '<td><a target="_blank" href="'.$row['html_url'].'" title="View Record" data-toggle="tooltip">'.font_awesome_icon("fa fa-eye").'</a></td></tr>';
              $count_repo = $count_repo + 1;
          }

          if($page >=10){
            break;
          }

          $page = $page + 1;
      
      }

      $table_str.= "</tbody></table>";
      $max_str='';
      if($count_repo >= 1000){
          $max_str = '(GITHUB Limitation: Maximum 1000 entries are returned. For more details at <a target="_blank" href ="https://developer.github.com/v3/search/#search-repositories">Click Here</a>).';
      } 
      $count_str = '<div class="alert alert-success">Showing <b>'.$count_repo.'</b> of <b>'.$total_count.'</b> repositories. '.$max_str.'</div>';


      print $count_str.$table_str ;
}


print '
<script type="text/javascript"> 
$(document).ready(function() {
     var t = $(\'#mytable\').DataTable({
      dom: \'Bfrtip\',
      buttons: [
        \'copy\', \'excel\', \'pdf\'
      ],
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