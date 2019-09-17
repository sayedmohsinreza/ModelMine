<?php
include "header.php";

$urlTB = 'https://api.github.com/repos/FelipeCortez/Calendala';
$fileFormatTB = 'uml';

if(isset($_POST['searchButton']) || isset($_REQUEST['url'])){    
    $urlTB = $_POST['urlTB'];
    $fileFormatTB = $_POST['fileFormatTB'];
}



print '<form name ="searchForm" method="post">
<div class="alert alert-secondary">
<b>Filtered by</b><input name="urlTB" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="repo api url" value ="'.$urlTB.'" >
<input name="fileFormatTB" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="File Format for search" value ="'.$fileFormatTB.'" >
<button name="searchButton" type="submit" class="btn btn-primary" value="1">Search</button></div>';




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
      $commit_page=1;
      while (1) {
        $data_commits = array( 'page' => $commit_page, 'per_page'=> 100);
        $commits_json = callAPI('GET', $urlTB.'/commits',$data_commits, $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);

        $commits_result = json_decode($commits_json, true);

         foreach ($commits_result as $key => $commit) {
          $empty_array = array();
              $commit_json = callAPI('GET', $urlTB.'/commits/'.$commit['sha'], $empty_array, $header_array, GITHUB_USERNAME, GITHUB_PASSWORD);
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

        if($commits_count==0){
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