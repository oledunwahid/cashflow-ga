<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © Mineral Alam Abadi Group.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by Team HR-GA
                </div>
            </div>
        </div>



    </div>


</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['Messages'])) { ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['Icon']; ?>',
                title: '<?php echo ($_SESSION['Icon'] == 'success') ? 'Success' : 'Error'; ?>',
                text: '<?php echo $_SESSION['Messages']; ?>',
                showConfirmButton: false
            });
            <?php unset($_SESSION['Messages']); ?>
            <?php unset($_SESSION['Icon']); ?>
        <?php } ?>
    });
</script>