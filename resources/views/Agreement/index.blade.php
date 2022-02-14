<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Styles -->
        <style>
         
        </style>
    </head>
    <body>
       <div class=" container-fluid col-10 pt-3">
       <form class="border border-secondary col-12 pt-3"  >
       <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="form-group">
                <label for="exampleFormControlInput1" title="Caracter. Debe existir el Directorio">Directorio a buscar</label>
             
                <input type="text" class="form-control" id="dir_to_search" placeholder="/www/httodocs/qwy">
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1" title="Caracter. Debe existir el Directorio">Directorio a reubicar</label>
                <input type="text" class="form-control" id="dir_to_relocate" placeholder="/www/httodocs/xqwx"  >
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Contratos</label>
                <textarea wrap="on" class="form-control" id="agreement" placeholder="SDJCT01"  rows="10" cols="50"></textarea>
              
            </div>
            <button class="btn btn-primary col-6 mb-3" type="button" id="search_process">Buscar y Cargar</button>
            
            <div id ="alert_text_content mb-1">

            </div>
        </form>
            
 
 

       </div>
    </body>
</html>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type ="text/javascript">

const element_dir_location = document.querySelector("#dir_to_search");
const element_dir_relocation = document.querySelector("#dir_to_relocate");
const element_agreement = document.querySelector("#agreement");
var alert_text_content = document.getElementById("#alert_text_content");

const to_search = document.querySelector("#search_process");
    
to_search.addEventListener('click', consult);
 


function consult(){

     
   var spinig =  Swal.fire({
        title: 'Procesando...',
        html: 'Por favor espere',
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    var params = { 
        location :element_dir_location.value,
        relocation: element_dir_relocation.value,
        agreement: element_agreement.value

    };

     
    $.ajax({
        type: "POST",
        url: "{{url('/search')}}",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')    },
        data: params,
        
        success: function(response){
            const result = response.result ;

           // console.log(response.result);
            if( result != null ){
                spinig.close();
                Swal.fire(
                    'Exito. 😎',
                      'Contratos Buscados: '+ response.result.amount_agreements_to_search +'<br>'
                    + 'Contratos Encontrados: '+ response.result.found_at +'<br>'
                    + 'Tiempo de ejecución: '+Math.round( response.execution, 2) +' segundos<br>'
                    
                    ,
                    'success'
                    );

            
                   
            }else{
                Swal.fire(
                    'Error. 😐',
                    'Verifique los datos que ingreso.',
                    'error'
                    );
           
            }
           
               
        },
        error: function (response){

            Swal.fire(
                    'Algo anda mal!',
                     ''+response,
                    'success'
                    );
            
            
        }

        
    });
 
};
 

</script>

