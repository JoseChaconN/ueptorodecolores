<?php
include_once "../include/header.php";
$margen_izq_fis = $_SESSION['margen_izq_fis'];
$margen_sup_fis = $_SESSION['margen_sup_fis'];
$margen_cop_fis = $_SESSION['margen_cop_fis'];
$margen_izq_HB = $_SESSION['margen_izq_HB'];
$margen_sup_HB = $_SESSION['margen_sup_HB'];
$margen_cop_HB = $_SESSION['margen_cop_HB'];?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Margenes de la factura</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!--h6 class="m-0 font-weight-bold text-primary">Estudiante a buscar</h6-->
        </div>
        <div class="card-body">
            <form>
                <div class="col-md-12 row">
                    <div class="form-group col-md-6">
                        <label for="formGroupExampleInput">Margen Superior Fiscal</label>
                        <input type="text" class="form-control" id="margen_sup_fis" onClick="this.select()" value="<?= $margen_sup_fis ?>">
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="formGroupExampleInput">Margen Superior H.Blanca</label>
                        <input type="text" class="form-control" id="margen_sup_HB" onClick="this.select()" value="<?= $margen_sup_HB ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="formGroupExampleInput">Margen Izquierdo Fiscal</label>
                        <input type="text" class="form-control" id="margen_izq_fis" onClick="this.select()" value="<?= $margen_izq_fis ?>" >
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="formGroupExampleInput">Margen Izquierdo H.Blanca</label>
                        <input type="text" class="form-control" id="margen_izq_HB" onClick="this.select()" value="<?= $margen_izq_HB ?>" >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="formGroupExampleInput">Espaciado Copia Fiscal</label>
                        <input type="text" class="form-control" id="margen_cop_fis" onClick="this.select()" value="<?= $margen_cop_fis ?>" >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="formGroupExampleInput">Espaciado Copia H.Blanca</label>
                        <input type="text" class="form-control" id="margen_cop_HB" onClick="this.select()" value="<?= $margen_cop_HB ?>" >
                    </div>
                </div>
                <div class="form-group col-md-4 offset-4">
                    <button type="button" onclick="guardaCambio()" style="width: 100%;" class="btn btn-primary"><i class="fas fa-file fa-sm"></i> Guardar</button>
                </div>
                
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) 
    {
        if (screen.width<1025) {
            $('#page-top').removeClass("sidebar-toggled");
            $('#accordionSidebar').addClass("navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled");
        }
        $('.collapse-item').removeClass("active");
        $('.collapse').removeClass("show");
        if (screen.width>1024) {
            $('#configura').addClass("show");
        }
        $('#margenFactura').addClass("active");
    });
    
    function guardaCambio() 
    {
        supF=$('#margen_sup_fis').val()
        izqF=$('#margen_izq_fis').val()
        copF=$('#margen_cop_fis').val()
        supH=$('#margen_sup_HB').val()
        izqH=$('#margen_izq_HB').val()
        copH=$('#margen_cop_HB').val()
        
        $.post('margen-actual.php',{'mgSupF':supF,'mgIzqF':izqF,'mgCopF':copF,'mgSupH':supH,'mgIzqH':izqH,'mgCopH':copH},function(data)
        {
            if(data.isSuccessful){
                Swal.fire({
                  icon: 'success',
                  title: 'Excelente',
                  text: 'Cambios realizado exitosamente!'
                })
            }else
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Cambio no realizado!'
                })
            }
        }, 'json');

    }
</script>
<?php
include_once "../include/footer.php";                
?>
           