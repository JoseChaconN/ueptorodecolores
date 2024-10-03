	<!-- ======= Footer ======= -->
  	<footer id="footer">
		<div class="footer-top">
	      <div class="container">
	        <div class="row">
	          <div class="col-lg-12 col-md-6 footer-contact text-center">
	            <p>
	              <strong>Dirección:</strong><?= DIRECCM ?> <br>
	              <strong>Telefono:</strong> <?= TELEMPM ?><br>
	              <strong>Email:</strong> <?= SUCORREO ?><br>
	            </p>
	          </div>
	        </div>
	      </div>
	    </div>
	    <div class="container d-md-flex py-4">
	      <div class="me-md-auto text-center text-md-start">
	        <div class="copyright">
	          &copy; <strong><span><?= DOMINIO ?></span></strong>. 
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
	
	<!-- Vendor JS Files -->
	
	<script src="../assets/vendor/aos/aos.js"></script>
	<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/vendor/php-email-form/validate.js"></script>
	<script src="../assets/vendor/purecounter/purecounter.js"></script>
	<script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="../assets/vendor/jquery/jquery.min.js"></script>
	<script src="../assets/vendor/jquery/jquery.js"></script>

	<!-- Template Main JS File -->
	<script src="../assets/js/main.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script type="text/javascript">
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
	</script>