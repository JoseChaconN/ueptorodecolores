<?php
include_once "../include/header.php";
$link = Conectarse();

$plantel_query = mysqli_query($link,"SELECT * FROM colegio WHERE id='1'"); 
while ($row = mysqli_fetch_array($plantel_query))
{
    $id = $row['id'];  
    $nkxs = desencriptar($row['nkxs']);
    $ekks = desencriptar($row['ekks']);
    $ckls = desencriptar($row['ckls']);
    $dominio = desencriptar($row['dominio']);
    $sucorreo = desencriptar($row['sucorreo']);  
    $dominio = desencriptar($row['dominio']);
    $direccm = desencriptar($row['direccm']);
    $telefono = desencriptar($row['telefono']);
    $clavemail = desencriptar($row['clavemail']);
    $correom=desencriptar($row['correom']);
    $rifcolm = desencriptar($row['rifcolm']);
    $ciudadm=desencriptar($row['ciudadm']);
    $estadom = desencriptar($row['estadom']);
    $tasa = $row['tasa'];
    $logoPlantel = $row['logoPlantel'];
    $administra = $row['administra'];
    $ced_admin = $row['ced_admin'];
} ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Ficha del Plantel</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos Principales</h6>
        </div>
        <div class="card-body">
            <form role="form" method="POST" action="plantel-actual.php" enctype="multipart/form-data" >
                <div class="form-row">
                    <div class="col-md-12  text-center">
                        <output id="list">
                            <img class='img-circle from-group' src="<?= '../img/'.$logoPlantel ?>" />
                        </output><br>
                        <label class="btn btn-primary">Logo<input type="file" name="logoPlantel" id="files" accept=".jpg, .jpeg, .png" style="display: none;"></label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="ckls">Codigo</label>
                        <input type="text" class="form-control" id="ckls" name="ckls" value="<?= $ckls ?>" >    
                    </div>                    
                    <div class="col-md-4">
                        <label for="nkxs">Nominación</label>
                        <input type="text" class="form-control" id="nkxs" name="nkxs" value="<?= $nkxs ?>" >    
                    </div>
                    <div class="col-md-4">
                        <label for="ekks">Nombre</label>
                        <input type="text" class="form-control" id="ekks" name="ekks" value="<?= $ekks ?>" >    
                    </div>
                    <div class="col-md-2">
                        <label for ="rifcolm">RIF</label><br>
                        <input type="text" class="form-control" id="rifcolm" name="rifcolm" value="<?= $rifcolm ?>" >
                    </div>
                    <div class="col-md-3">
                        <label for ="ciudadm">Ciudad</label><br>
                        <input type="text" class="form-control" id="ciudadm" name="ciudadm" value="<?= $ciudadm ?>" >
                    </div>
                    <div class="col-md-3">
                        <label for="estadom">Estado</label>
                        <input type="text" class="form-control" id="estadom" name="estadom" value="<?= $estadom ?>" >    
                    </div>
                    <div class="col-md-6">
                        <label for="direccm">Dirección</label>
                        <input type="text" class="form-control" id="direccm" name="direccm" value="<?= $direccm ?>" >    
                    </div>
                    <div class="col-md-3">
                        <label for="sucorreo" >Correo</label>
                        <input type="email" name="sucorreo" id="sucorreo" class="form-control" value="<?= $sucorreo ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="correom" >Correo Envío</label>
                        <input type="email" name="correom" id="correom" class="form-control" value="<?= $correom ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="clavemail" >Clave Mail</label>
                        <input type="text" name="clavemail" id="clavemail" class="form-control" value="<?= $clavemail ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="telefono">Telefono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" value="<?= $telefono ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="dominio" >Dominio</label>
                        <input type="text" name="dominio" id="dominio" class="form-control" value="<?= $dominio ?>">
                    </div>
                    <div class="col-md-6">
                        <label for ="administra">Administrador</label><br>
                        <input type="text" class="form-control" id="administra" name="administra" value="<?= $administra ?>" >
                    </div>
                    <div class="col-md-2">
                        <label for ="ced_admin">Cedula Admin.</label><br>
                        <input type="text" class="form-control" id="ced_admin" name="ced_admin" value="<?= $ced_admin ?>" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-4 offset-md-4" style="margin-top: 2%;" id="divBtnGuardar">
                        <button type="submit"  style="width: 100%;" class="btn btn-primary"><i class="fas fa-save fa-sm"></i> Guardar</button>
                    </div>    
                </div>
                <input type="hidden" name="idUser" value="<?= encriptar($idUser) ?>">
                <input type="hidden" id="guarda" value="<?= $guarda ?>">
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        $('#page-top').removeClass("sidebar-toggled");
        $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
    });
    function archivo(evt) 
    {
        var files = evt.target.files; // FileList object
        // Obtenemos la imagen del campo "file".
        for (var i = 0, f; f = files[i]; i++) 
        {
            //Solo admitimos imágenes.
            if (!f.type.match('image.*')) 
            {
                alert("FORMATO DE IMAGEN INCORRECTO");
                continue;
            }
            //ESTE ES EL CODIGO 
            var sizeByte = this.files[0].size;
            var siezekiloByte = parseInt(sizeByte / 1024);
            if(siezekiloByte > 500)
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Error!',
                  confirmButtonText:
                  '<i class="fa fa-thumbs-up"></i> Entendido',
                  text: 'El tamaño de la imagen supera el permitido por el Sistema verifique. (Maximo permitido 500kb)'
                })
                continue;
            }
            //HASTA AQUI
            var reader = new FileReader();
            reader.onload = (function(theFile) 
            {
                return function(e) 
                {
                // Insertamos la imagen
                    document.getElementById("list").innerHTML = ['<img class="img-circle" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
                };
            })(f);
            reader.readAsDataURL(f);
        }           
    }
    document.getElementById('files').addEventListener('change', archivo, false); 
    
    
</script>
<?php
include_once "../include/footer.php";                
?>
           