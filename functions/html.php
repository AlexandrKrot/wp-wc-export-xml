<?php
/**
 * Pechenki html
 */
class Phtml
{


  public static function inputText($data=array()){
      $data = array_merge(['type'=>'text'],$data);
      $out = '<input '.self::convertInput($data).'>';
      return $out;
  }

  	public static function inputNumber($data=array()){
      $data = array_merge(['type'=>'number'],$data);
      $out = '<input '.self::convertInput($data).'>';
      return $out;
  }

  public static function select($data=array()){
      $data = array_merge(['data'=>[],
      'value'=>'',
      'name'=>'',
    ],$data);

      $out ="<select name='".$data['name']."'>";
      foreach ($data['data'] as $key => $value) {
        $out .='<option value="'.$key.'"  '.(($key==$data['value'])?'selected':'').' >'.$value.'</option>';
      }
      $out .='</select >';
      return $out;
  }

  public static function convertInput($data=array()){
      $out = '';
       foreach($data as $k=>$v){
         $out .= $k.'="'.$v.'" ';
       }
    return $out;
  }

  public static function arrayDataConver($data,$id,$value){

      $out=[];
       foreach((array)$data as $k=>$v){
         $item = (array)$v;
         $out[$item[$id]] = $item[$value] ;
       }
     return $out;

  }



}
