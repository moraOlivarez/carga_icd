<?php

namespace App\Http\Controllers\Load_ICD;
use Illuminate\Support\Facades\File;

class LoadManager {

   
    public function listFolderFiles($dir) { 
        $arr = array();
        $ffs = scandir($dir);
    
        foreach($ffs as $ff) {
            if($ff != '.' && $ff != '..') {
             //   $arr[$ff] = array();
                array_push( $arr, $ff );

                if(is_dir($dir.''.$ff)) {
                    $ff_child = $this->listFolderFiles( $dir.''.$ff.'\\');
                    foreach( $ff_child as $elemest){
                        array_push( $arr, $ff.'\\'.$elemest );
                    }
                   
                }
            }
        }
        return $arr;
    }


    public function actionResult(string $dir, string $dir_to_relocate, string $agreemen){
         
     
        //obtenemos un arreglo con los contratos prorporcionados
        $contratos = $this->prepareAgreements( $agreemen);
    
        //validamos que sean directofrios existentes 
        if(is_dir($dir) & is_dir( $dir_to_relocate) ){
        
       
        //obtenemos los archivos y directorios dentro del directorio pasado para buscar
        $files = $this->listFolderFiles($dir);

     // return $files;

       // unset( $files[0]);
       // unset( $files[1]);
      
       //obtenemos los directorios junto los archivos y sus nombre
       $files_list= $this->prepareDirectories( $files, $dir);

       //return $files_list;


        $matches = array();
        $agreement_match = array();
        foreach( $contratos as $contrato){
          
            //  busca dentro de cada archivo si esxiste la cadena 
                foreach($files_list as $x){
                        $data = fopen($x[0], "r");
    
                        if( $data){
                            while (!feof($data)){
                                    $buffer = fgets($data);
                                    if(strpos($buffer, $contrato) !== FALSE){
    
                                     //   array_push(  $matche, array( $archivo_nombre_ubicacion, $just_name."_a.csv" ));
                                            $matches[] =$x;
                                            $agreement_match[] = $contrato;
                                            break;
                                    }
                                                                   
                            }
                            fclose($data);
                                   
                        }  
    
                }
        }
            
      

    $move_agreements = $this->relocateAgreements($matches, $dir_to_relocate);
 
   //  $zx =  $this->deleteFile(  $matches  , $dir_to_relocate);

    // return $zx;

     return array(
                  'status' => $move_agreements
                , 'agreements_to_search' =>   $contratos
                , 'amount_agreements_to_search' => count( $contratos)
                , "files" => $matches
                , 'found_at' =>count(  array_unique( $agreement_match)));
  

  }
    
    return null;
    }

    private function deleteFile(array $matches, string $dir_to_relocate){

      //  $files= scandir($dir_to_delte);
       // $files = glob('path/to/temp/*'); // get all file names

        foreach($matches as $file){ // iterate files
              if(is_file($dir_to_relocate.''.$file[1])) {
                  return $dir_to_relocate.''.$file[1];
                      //  unlink($dir_to_relocate.''.$file[1]); // delete file
                }
        
            }

    }
   

   
  //  C:\xampp7\htdocs\carga_icd\storage\app\public\2021_10\Cygnus_20211004_000015139.csv

  private function relocateAgreements(array $matches, string $dir_to_relocate): int{

    foreach( $matches as $to_copy_and_move){
        //    return "".$dir_to_relocate."".$to_copy_and_move[1];
        //return  substr($to_copy_and_move[1], -31);
        //   origen                  ,      destination substr($to_copy_and_move[1], -31)
        $reduced_name= substr($to_copy_and_move[1], -31 );
        copy( $to_copy_and_move[0], "".$dir_to_relocate."". $reduced_name);
    }


    return 1;

  }
  private function prepareAgreements($agreemen): array{
    
    if(is_string($agreemen)){
        $agreement = preg_replace("/[\n\r]/",",", $agreemen);
         //str_ireplace(" ", ",", $agreemen);
      return  $contratos = explode(',',  $agreement);     
    }

    
  }


  private function prepareDirectories(array $files, string $dir): array{

    $files_list = array();
    foreach($files as $file){
        
      // return  
       $archivo_nombre_ubicacion = $dir.''.$file.'';
       if(!is_dir( $archivo_nombre_ubicacion)){

            if(file_exists($archivo_nombre_ubicacion)){
              $just_name= $file;
              //  mb_substr($file, 0, -4);
                      $just_name=  str_replace( ".csv" , "", $just_name);
                      $just_name=   str_replace( "_a", "", $just_name );
                
                    array_push($files_list, array( $archivo_nombre_ubicacion, $just_name."_a.csv" ));
                                    
                }
                /*
                else{
                    return  "no existe";
                }
                */
       }
           
                                    
    }
    return $files_list;
  }
}
// C:\xampp7\htdocs\carga_icd\storage\app\public\2021_10\Cygnus_20211001_000004383.csv