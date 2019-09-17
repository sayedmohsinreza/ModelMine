<?php

//------------------ Font awesome Icon  --------------//
function font_awesome_icon($icon_class='',$aria_hidden='true'){
    return '   <i class="'.$icon_class.'" aria-hidden="'.$aria_hidden.'"></i>';
}
//------------------ Font awesome Icon  --------------//
/*-----------USAGE-------------------

$str .= create_input('First Name','text','f_name','','','','Example: Sayed Mohsin',true);
$str .= create_input('Email','email','email_name','','','','Example: smrezaiit@gmail.com',false);
$str .= create_input('Select File','file','file','','','','',false);

-----------USAGE-------------------*/
function create_input($label,$type,$name,$value='',$class='',$placeholder='',$help='',$required=false){
    if($required==false){$required='';$asterisk='';}else {$required='required';$asterisk='<span class="text-danger">*</span>';}
    if($class=='')$class='form-control';
    if($placeholder=='')$placeholder = $label;
    $str ='
    <div class="form-group">
    <label for="'.$name.'">'.$label.$asterisk.'</label>
    <input type="'.$type.'" class="'.$class.'" id="'.$name.'" name="'.$name.'" aria-describedby="'.$name.'Help" placeholder="'.$placeholder.'" value="'.$value.'" '.$required.'>
    <small id="'.$name.'Help" class="form-text text-muted">'.$help.'</small>
    </div>
    ';
    return $str;
}


/*-----------USAGE-------------------

$gender_arr = array('Male'=>'Male' ,'Female'=>'Female' );
$str .= create_select('Gender','gender',$gender_arr,'','',false, true);

-----------USAGE-------------------*/
function create_select($label, $name, $arr, $value='',$class='',$multiple=false, $required=false, $help='', $data_live_search=true){
    if($data_live_search) {$live = 'data-live-search="true"';} else{ $live = '';};
    if($required==false){$required='';$asterisk='';}else {$required='required';$asterisk='<span class="text-danger">*</span>';}
    if($class=='')$class='form-control';
    if($multiple==false)$multiple='';else $multiple='multiple';
    $str ='
    <div class="form-group">
    <label for="'.$name.'">'.$label.$asterisk.'</label>
    <select '.$multiple.' class="'.$class.' selectpicker" id="'.$name.'" '.$live.' name="'.$name.'" '.$required.'>';
    $str .=  '<option value="">SELECT</option>';
    reset($arr);
    while (list($key, $val) = each($arr)){
        if($value==$key){
         $str.= '<option value="'.$key.'" selected="selected" >'.$val.'</option>';
     }else{
         $str.= '<option value="'.$key.'">'.$val.'</option>';
     }
 }
 $str.='</select>';
 $str.=' <small id="'.$name.'Help" class="form-text text-muted">'.$help.'</small>';
 $str .='</div>';
 return $str;
}

?>