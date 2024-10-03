            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; jesistemas.com 2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fin de sesión?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Haga clic en Cerrar sesión para proteger todos los accesos al sistema.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="../../logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) 
        {
            if (screen.width<1025) {
                $('#facilComp').hide();
                $('#facilCort').show();  
            }else{
                $('#facilComp').show(); 
                $('#facilCort').hide(); 
            }
        });
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
        function ValMon(e)
        {
            tecla = (document.all) ? e.keyCode : e.which;
            if (tecla==8)
            {
                return true;
            }
            patron =/[0-9,.]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        }
        function pulsaBuscar() {
            Swal.fire({
              icon: 'warning',
              html: 'Recuerda hacer click en<br><button class="btn btn-info" style="width:60%; margin-top:5%;"><span class="fas fa-search fa-sm" ></span> Buscar</button>',
              showConfirmButton: false,
              timer: 800,
              width: '35%'
            })
        }
        function formatear(event) 
        {
            $(event.target).val(function(index, value) 
            {
                return value.replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
            });
        }
        function MASK(form, n, mask, format) {
          if (format == "undefined") format = false;
          if (format || NUM(n)) {
            dec = 0, point = 0;
            x = mask.indexOf(".")+1;
            if (x) { dec = mask.length - x; }

            if (dec) {
              n = NUM(n, dec)+"";
              x = n.indexOf(".")+1;
              if (x) { point = n.length - x; } else { n += "."; }
            } else {
              n = NUM(n, 0)+"";
            } 
            for (var x = point; x < dec ; x++) {
              n += "0";
            }
            x = n.length, y = mask.length, XMASK = "";
            while ( x || y ) {
              if ( x ) {
                while ( y && "#0.".indexOf(mask.charAt(y-1)) == -1 ) {
                  if ( n.charAt(x-1) != "-")
                    XMASK = mask.charAt(y-1) + XMASK;
                  y--;
                }
                XMASK = n.charAt(x-1) + XMASK, x--;
              } else if ( y && "$0".indexOf(mask.charAt(y-1))+1 ) {
                XMASK = mask.charAt(y-1) + XMASK;
              }
              if ( y ) { y-- }
            }
          } else {
             XMASK="";
          }
          if (form) { 
            form.value = XMASK;
            if (NUM(n)<0) {
              form.style.color="#FF0000";
            } else {
              form.style.color="#000000";
            }
          }
          return XMASK;
        }
        function NUM(s, dec) {
          for (var s = s+"", num = "", x = 0 ; x < s.length ; x++) {
            c = s.charAt(x);
            if (".-+/*".indexOf(c)+1 || c != " " && !isNaN(c)) { num+=c; }
          }
          if (isNaN(num)) { num = eval(num); }
          if (num == "")  { num=0; } else { num = parseFloat(num); }
          if (dec != undefined) {
            r=.5; if (num<0) r=-r;
            e=Math.pow(10, (dec>0) ? dec : 0 );
            return parseInt(num*e+r) / e;
          } else {
            return num;
          }
        }

    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.js?1"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.js"></script>
    <!-- Page level custom scripts -->
    <script src="../../js/demo/datatables-demo.js"></script>
    <script src="../../js/numLet.js?2" type="text/javascript"></script> 
    <script src="../../sweealert/sweetalert2.all.min.js"></script>

</body>

</html>
 