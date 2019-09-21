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

print GITHUB_INSTRUCTION;
print '<form name ="searchForm" method="post">';
print '<div class="alert alert-secondary">
Code Search
<div class="input-group mb-3">
  <input name="searchTB" type="text" class="form-control" aria-label="Text input with segmented dropdown button" placeholder="keywords for search" value ="'.$searchTB_text.'" >
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


<div class="row"><div class="col-sm-6">
'.create_input('Extension','text','extension',$extension_text,'','','Help: matches code files with a certain file extension.', false).'
</div><div class="col-sm-3">

</div>
</div>



<button name="advancedSearchButton" type="submit" class="btn btn-primary" value="1">Advanced Search</button>

</div>';




print '</form>';


if(isset($_POST['searchButton']) || isset($_POST['advancedSearchButton'])){

    $header_array = array('Accept: application/vnd.github.machine-man-preview+json', 'User-Agent: Awesome-Octocat-App');
    if(!empty($_POST['extension'] )){$searchTB_text.=' extension:'.$extension_text;}



    $page =1;
    $count_file = 0;
    $account_no = $_POST['github_account'];
    while (1) {
        $data = array('q'=> $searchTB_text, 'page' => $page, 'per_page'=> 100);
        $url = 'https://api.github.com/search/code';
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
              
            $table_str.= '<th>#</th><th>File Name</th><th>File Path</th><th>Repository Name</th><th>Repository Owner Name</th><th>File Score</th>';
            $table_str.= '<th>Action</th></tr></thead><tbody>';
        }
        foreach ($data as $key => $file) {
              $row = $file['repository'];
              $table_str.= '<tr>
              <td></td>
              <td>'.$file['name'].'</td>
              <td>'.$file['path'].'</td>
              <td>'.$row['name'].'</td>
              <td>'.$row['owner']['login'].'</td>
              <td>'.$file['score'].'</td>';

              $table_str.= '<td><a target="_blank" href="'.$row['html_url'].'" title="View Repository URL" data-toggle="tooltip">'.font_awesome_icon("fa fa-eye").'</a> | <a target="_blank" href="'.$file['html_url'].'" title="View File URL" data-toggle="tooltip">'.font_awesome_icon("fa fa-external-link").'</a></td></tr>';
              $count_file = $count_file + 1;
        }
        if($page >=10){
            break;
        }

        $page = $page + 1;
        

    }
    $table_str.= "</tbody></table>";
    $max_str='';
    if($count_file >= 1000){
        $max_str = '(GITHUB Limitation: Maximum 1000 entries are returned. For more details at <a target="_blank" href ="https://developer.github.com/v3/search/#search-repositories">Click Here</a>.)';
    } 
    $count_str = '<div class="alert alert-success">Showing <b>'.$count_file.'</b> of <b>'.$total_count.'</b> repositories. '.$max_str.'</div>';

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