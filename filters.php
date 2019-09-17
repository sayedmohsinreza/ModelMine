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

 $select = $conn->query("SELECT * FROM `list_repos` ");
 $result = mysqli_fetch_all($select, MYSQLI_ASSOC);
print "<table id='mytable'  class='table table-striped table-bordered' style='width:100%'><thead><tr>";
print '<th>#</th><th>Name</th><th>Owner Name</th><th>Size</th><th>Stars</th><th>Created At</th><th>Contributions</th><th>Commits</th><th>Language</th><th>Link</th>';
print '<th>Action</th></tr></thead><tbody>';



  foreach ($result as $key => $row) {
      $repo_result = json_decode($row['json_output'], true);
      
      if($repo_result['size'] < $size_text && $size_text != ''){continue;}
      if($repo_result['created_at'] < $created_text && $created_text != ''){continue;}
      if($repo_result['language']!=$language_text && $language_text != ''){continue;}
      if($repo_result['stargazers_count'] < $stars_text && $stars_text != ''){continue;}
      if($row['commits'] < $commits_text && $commits_text != ''){continue;}
      if($row['contributors'] < $commits_text && $contributors_text != ''){continue;}

      print '<tr>
      <td></td>
      <td>'.$repo_result['name'].'</td>
      <td>'.$repo_result['owner']['login'].'</td>
      <td>'.$repo_result['size'].'</td>
      <td>'.$repo_result['stargazers_count'].'</td>
      <td>'.$repo_result['created_at'].'</td>
      <td> '.$row['contributors'].' </td>
      <td> '.$row['commits'].' </td>
      <td>'.$repo_result['language'].'</td>
      <td>'.$repo_result['html_url'].'</td>';


  print '<td><a target="_blank" href="'.$repo_result['html_url'].'" title="View Record" data-toggle="tooltip">'.font_awesome_icon("fa fa-eye").'</a> <a target="_blank" href="'.$repo_result['url'].'" title="View API Record" data-toggle="tooltip">'.font_awesome_icon("fa fa-external-link").'</a>

  </td></tr>';
    
}

  


print "</tbody></table>";
}
print '
<script type="text/javascript"> 
$(document).ready(function() {
     var t = $(\'#mytable\').DataTable({
      dom: \'Bfrtip\',
      buttons: [
        \'copy\', \'excel\', \'pdf\'
      ],
      order: [[ 3, "desc" ]]
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