<?php
include "header.php";

//$urlTB = 'https://api.github.com/repos/FelipeCortez/Calendala';
$owner_username ='FelipeCortez'; 
$repository_username = 'Calendala';
$fileFormatTB = 'uml';
$start_commit_no = '1';

if(isset($_POST['searchButton']) || isset($_REQUEST['url'])){    
    //$urlTB = $_POST['urlTB'];
    $owner_username =$_POST['owner_username']; 
    $repository_username = $_POST['repository_username'];
    $fileFormatTB = $_POST['fileFormatTB'];
    $start_commit_no = $_POST['start_commit_no'];
}


print GITHUB_INSTRUCTION;
print '<form name ="searchForm" method="post">
<div class="alert alert-secondary">
<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Search</span>
  </div>
  <input name="fileFormatTB" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="File Format for search" value ="'.$fileFormatTB.'" >
   <span class="input-group-text">format/extension files in  </span>
  <input name="owner_username" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="repository owner name" value ="'.$owner_username.'" >
  <span class="input-group-text">owner\'s </span>
  <input name="repository_username" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="repository name" value ="'.$repository_username.'" >
    <span class="input-group-text">repository.</span>
</div>

<div class="input-group">
  <div class="input-group-prepend">
    <span class="input-group-text">Search from commit no </span>
  </div>
  <input name="start_commit_no" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="start commit no." value ="1" >
   <span class="input-group-text"> to  next 4000 commits </span>
  <select class="custom-select" name="github_account" required>
    <option selected value="0">Choose GITHUB account</option>';
    foreach (GITHUB_CREDENTIALS as $key => $credential) {
      if(isset($_POST['github_account']) && $_POST['github_account'] == $key){$select_str = 'selected';}else{$select_str = '';}
      print '<option value="'.$key.'" '.$select_str.'>USERNAME: '.$credential['username'].', PASSWORD: '.substr($credential['password'], 0, 2).'*****'.substr($credential['password'], -2).'</option>';
    }
    
  print '</select>
  
  <button name="searchButton" type="submit" class="btn btn-primary" value="1">Do it now!</button>
</div>

</div>';


print '</form>';


if(isset($_POST['searchButton'])){
    $header_array = array('Accept: application/vnd.github.machine-man-preview+json', 'User-Agent: Awesome-Octocat-App');
    print "<table id='mytable'  class='table table-striped table-bordered' style='width:100%'><thead><tr>";
    print '<th>#</th>
            <th>Sha</th>
            <th>Author name</th>
            <th>Author email</th>
            <th>Author date</th>
            <th>Commiter name</th>
            <th>commiter email</th>
            <th>commiter date</th>
            <th>Message</th>
            <th>Filename</th>
            <th>File status</th>';
    print '</tr></thead><tbody>';


      $total_commits_count = 0; 
      $commit_page = intval($start_commit_no/100) + 1;
      $request_count = 0;
      $account_no = $_POST['github_account'];

      while (1) {
        $data_commits = array( 'page' => $commit_page, 'per_page'=> 100);
        $commits_json = callAPI('GET', 'https://api.github.com/repos/'.$owner_username.'/'.$repository_username.''.'/commits',$data_commits, $header_array, GITHUB_CREDENTIALS[$account_no]['username'], GITHUB_CREDENTIALS[$account_no]['password']);

        $commits_result = json_decode($commits_json, true);

         foreach ($commits_result as $key => $commit) {
              
              $empty_array = array();
              $commit_json = callAPI('GET', 'https://api.github.com/repos/'.$owner_username.'/'.$repository_username.'/commits/'.$commit['sha'], $empty_array, $header_array, GITHUB_CREDENTIALS[$account_no]['username'], GITHUB_CREDENTIALS[$account_no]['password']);
              $request_count  = $request_count + 1;
              $commit_result = json_decode($commit_json, true);
              
        foreach ($commit_result['files'] as $key => $file) {    
              if(pathinfo($file['filename'], PATHINFO_EXTENSION) != $fileFormatTB){
                continue;
              }           
              print '<tr>
              <td></td>
              <td>'.$commit_result['sha'].'</td>
              <td>'.$commit_result['commit']['author']['name'].'</td>
              <td>'.$commit_result['commit']['author']['email'].'</td>
              <td>'.$commit_result['commit']['author']['date'].'</td>
              <td>'.$commit_result['commit']['committer']['name'].' </td>
              <td>'.$commit_result['commit']['committer']['email'].' </td>
              <td>'.$commit_result['commit']['committer']['date'].'</td>
              <td>'.$commit_result['commit']['message'].'</td>
              <td>'.$file['filename'].'</td>
              <td>'.$file['status'].'</td>
              </tr>';
        }
      }

        $commits_count = count($commits_result);
        $total_commits_count = $total_commits_count + $commits_count;

        if($commits_count==0 || $request_count >= 4000){
          break;
        }
        $commit_page = $commit_page + 1;
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