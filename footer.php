	<!-- ======= Footer ======= -->
  	<footer id="footer">
		<div class="footer-top">
	      <div class="container">
	        <div class="row">

	          <div class="col-lg-3 col-md-6 footer-contact">
	            <h3>Dirección</h3>
	            <p><?= DIRECCM .' '. CIUDADM.' - '.ESTADOM ?><br>
	              <strong>Telefono: </strong><?= TELEMPM ?><br>
	              <strong class="oculta">Email: </strong><span class="oculta"><?= SUCORREO ?></span><br>
	            </p>
	          </div>

	          <div class="col-lg-3 col-md-6 footer-links">
	            <h4>Links más usados</h4>
	            <ul>
	              <li><i class="bx bx-chevron-right"></i> <a href="index.php">Inicio</a></li><?php
	              if(isset($_SESSION['usuario']))
	              { ?>
	              	<li><i class="bx bx-chevron-right"></i> <a href="cierra.php">Salir</a></li><?php 
	              }else
	              {?>
	              	<li><i class="bx bx-chevron-right"></i> <a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;">Ingresar</a></li><?php 
	              }?>
	              <li><i class="bx bx-chevron-right"></i> <a href="index.php#why-us">Procesos</a></li><?php
	              if(isset($_SESSION['usuario']))
	              {?>
	              	<li><i class="bx bx-chevron-right"></i> <a href="index.php#features">Reportes</a></li><?php
	              }else
	              {
	              	?>
	              	<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Reportes</a></li><?php
	              } ?>
	              <li><i class="bx bx-chevron-right"></i> <a href="index.php#bancos">Bancos</a></li>
	              

	            </ul>
	          </div>

	          <div class="col-lg-3 col-md-6 footer-links">
	            <h4>Otros Links</h4>
	            <ul>
	            	<li><i class="bx bx-chevron-right"></i> <a href="contacto.php">Contacto</a></li><?php
	              	if(!isset($_SESSION['usuario']))
	              	{?>
	              		<li><i class="bx bx-chevron-right"></i> <a href="preinscripcion.php">Inscribirme</a></li><?php
	              	}
		            if(isset($_SESSION['usuario']))
		            {?>
	              		<li><i class="bx bx-chevron-right"></i> <a <?php if($grado<60){ echo 'href="list-tareas-pri.php"';} else { echo 'href="list-tareas.php"';} ?>>Materiales</a></li>
	              		<li><i class="bx bx-chevron-right"></i> <a <?php if($grado<60){ echo 'href="list-videos-pri.php"';} else { echo 'href="list-videos.php"';} ?>>Video Clases</a></li>
	              		<?php if ($habi==1) {?>
	              		<li><i class="bx bx-chevron-right"></i> <a href="planilla.php" target="_blank">Planilla de Inscripción</a></li><?php 
	              		}
	              	}else
	              	{?>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Materiales</a></li>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Video Clases</a></li>
	              		<?php if ($habi==1) {?>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Planilla de Inscripción</a></li><?php
	              		}
	              	}?>
	              	
	            </ul>
	          </div>
	          <div class="col-lg-3 col-md-6 footer-links">
	            <h4>Otros Links</h4>
	            <ul>
	            	<li><i class="bx bx-chevron-right"></i> <a href="manual.php" target="_blank">Manual Usuario</a></li><?php
		            if(isset($_SESSION['usuario']))
		            {
		            	if ($morosida==0 && $pagado>0 && $habi==1) 
		            	{?>
		              		<li><i class="bx bx-chevron-right"></i> <a href="cons-est.php" target="_blank">Constancia Estudio</a></li>
		              		<li><i class="bx bx-chevron-right"></i> <a href="carnet.php" target="_blank">Carnet</a></li><?php 
		              	}
	              	}else
	              	{?>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Constancia Estudio</a></li>
	              		<li><i class="bx bx-chevron-right"></i><a data-bs-toggle="modal" data-bs-target="#login1" style="cursor: pointer;"> Carnet</a></li><?php 

	              	}?>
	              	<li><i class="bx bx-chevron-right"></i> <a data-bs-toggle="modal" style='cursor: pointer' data-bs-target="#requisitos">Requisitos</a></li>
	              	<li><i class="bx bx-chevron-right"></i> <a href="contacto.php">Como llegar</a></li>
	            </ul>
	          </div>
	        </div>
	      </div>
	    </div>
	    <div class="container d-md-flex py-4">
	      <div class="me-md-auto text-center text-md-start">
	        <div class="copyright">
	          &copy; <strong class="oculta"><span><?= DOMINIO ?></span></strong> 
	        </div>
	        <div class="credits">
	          	Sistema desarrollado por <a target="_BLANK" href="https://jesistemas.com">jesistemas.com</a>
	        </div>
	      </div>
	      <div class="social-links text-center text-md-right pt-3 pt-md-0"><?php 
	      	if(FACEBOOK!=""){?>
	      		<a href="<?= FACEBOOK ?>" class="facebook"><i class="bx bxl-facebook"></i></a><?php
	      	}
	        if(INSTAGRAM!=""){?>
	        	<a href="<?= INSTAGRAM ?>" target="_blank" class="instagram"><i class="bx bxl-instagram"></i></a><?php
	        }?>
	      </div>
	    </div>
	</footer>
	<div id="preloader"></div>
	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
	<div class="modal fade" id="login1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Iniciar Sesión</h5>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">
		        <form method="post" action="login.php" >
		        	<center><img src="assets/img/logo.png?5" style="width:60%;"></center>
		          <div class="mb-3">
		            <label for="recipient-name" class="col-form-label"><i class="ri-user-follow-line"></i> Cedula ó Usuario del Estudiante:</label>
		            <input type="text" required class="form-control" placeholder="Ingrese solo numeros" id="usuario" name="usuario">
		          </div>
		          <div class="mb-3">
		            <label for="message-text" class="col-form-label"><i class="ri-lock-unlock-fill"></i> Contraseña:</label>
		            <input type="password" required class="form-control" id="passwordalum" name="passwordalum">
		            <input type="hidden" name="dispo" value="desktop">
		          </div>
		          <div class="col-md-12">
		          	<button type="submit" style="width:100%;" class="btn btn-primary"><i class="ri-key-2-fill"></i> Ingresar</button>
		          </div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <div class="col-md-12">
		        	<a href="index.php"><button type="button" style="width:100%;" class="btn btn-info"><i class="ri-lock-unlock-line"></i> Olvide Contraseña</button></a>
		        </div>
		        <div class="col-md-12">
		        	<a href="preinscripcion.php"><button type="button" style="width:100%;" class="btn btn-success"><i class="ri-check-line"></i> Inscribirme!</button></a>
		        </div>
		      </div>
		    </div>
	  </div>
	</div>
	<div class="modal fade" id="requisitos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="staticBackdropLabel">REQUISITOS INICIALES NUEVOS INGRESOS<br> Año Escolar <?= PROXANOE ?></h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <p style="text-align: justify;" >
	        	01 Planilla de inscripción emitida por la página web <br>
	        	01 Fotocopia de la cédula de la madre, padre y / o representantes.<br>
	        	01 Referencia bancaria (de uno de los padres).<br>
	        	01 Constancia de trabajo vigente (de uno de los padres).<br>
	        	01 Carta de exposición de motivos por la selección de este plantel.<br>
		        01 Si la solicitud es para educación preescolar debe traer tarjeta de vacunas.<br><br>
		        Ante cualquier duda, contactar con los teléfonos del plantel (<a href="tel:<?= TELEMPM ?>"><?= TELEMPM ?></a> ) en horas de oficina.<br><br>
		        O a través del correo <a href="contacto.php"><?= SUCORREO ?></a> 
		    </p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Vendor JS Files -->
	
	<script src="assets/vendor/aos/aos.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!--script src="assets/vendor/php-email-form/validate.js"></script-->
	<script src="assets/vendor/purecounter/purecounter.js"></script>
	<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="assets/vendor/jquery/jquery.min.js"></script>
	<script src="assets/vendor/jquery/jquery.js"></script>

	<!-- Template Main JS File -->
	<script src="assets/js/main.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) 
    	{
    		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      		return new bootstrap.Tooltip(tooltipTriggerEl)
    		})
    		if (screen.width<768) { 
	          $('.oculta').hide();
	        }else{ 
	          $('.oculta').show();
	        }
		});
		function bachi() 
		{
			Swal.fire({
	            icon: 'info',
	            title: 'Informacion!',
	            confirmButtonText:
	            '<i class="fa fa-thumbs-up"></i> Entendido',
	            text: 'Sr.(a) Representante esta opción es solo valida para bachillerato.'
	        })	
		}
		function ValCed(e)
	    {
	      tecla = (document.all) ? e.keyCode : e.which;
	      if (tecla==8)
	      {
	        return true;
	      }
	      patron =/[0-9]/;
	      tecla_final = String.fromCharCode(tecla);
	      return patron.test(tecla_final);
	    }
	    function ValCed2(e)
	    {
	      tecla = (document.all) ? e.keyCode : e.which;
	      if (tecla==8)
	      {
	        return true;
	      }
	      patron =/[0-9,]/;
	      tecla_final = String.fromCharCode(tecla);
	      return patron.test(tecla_final);
	    }
	</script>