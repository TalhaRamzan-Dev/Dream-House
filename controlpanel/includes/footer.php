<?php
// admin/includes/footer.php
?>
</div><!-- container -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){
  if ($('#propsTable').length) {
    $('#propsTable').DataTable({ "order": [[0,"desc"]], "pageLength": 10 });
  }
});
</script>
</body>
</html>
