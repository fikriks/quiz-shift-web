<!-- Flash Messages with Notyf Toast Integration -->

<?php if (session()->has('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Flash data - success:', '<?= session('success') ?>'); // Debug
            // Check if toast object is available before using it
            if (typeof toast !== 'undefined' && toast && typeof toast.success === 'function') {
                toast.success('<?= esc(str_replace("'", "\'", session('success'))) ?>');
            } else {
                console.error('Toast object not available for success message');
            }
        });
    </script>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Flash data - error:', '<?= session('error') ?>'); // Debug
            // Check if toast object is available before using it
            if (typeof toast !== 'undefined' && toast && typeof toast.error === 'function') {
                toast.error('<?= esc(str_replace("'", "\'", session('error'))) ?>');
            } else {
                console.error('Toast object not available for error message');
            }
        });
    </script>
<?php endif; ?>

<?php if (session()->has('errors')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Flash data - errors:', '<?= json_encode(session('errors')) ?>'); // Debug
            // Check if toast object is available before using it
            if (typeof toast !== 'undefined' && toast && typeof toast.error === 'function') {
                <?php
                $errors = session('errors');
                if (is_array($errors)) {
                    foreach ($errors as $error): ?>
                        console.log('Validation error:', '<?= esc(str_replace("'", "\'", $error)) ?>'); // Debug
                        toast.error('<?= esc(str_replace("'", "\'", $error)) ?>');
                    <?php endforeach;
                } else { ?>
                    toast.error('<?= esc(str_replace("'", "\'", $errors)) ?>');
                <?php }
                ?>
            } else {
                console.error('Toast object not available for validation errors');
            }
        });
    </script>
<?php endif; ?>

<?php if (session()->has('info')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if toast object is available before using it
            if (typeof toast !== 'undefined' && toast && typeof toast.info === 'function') {
                toast.info('<?= esc(str_replace("'", "\'", session('info'))) ?>');
            }
        });
    </script>
<?php endif; ?>

<?php if (session()->has('warning')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if toast object is available before using it
            if (typeof toast !== 'undefined' && toast && typeof toast.warning === 'function') {
                toast.warning('<?= esc(str_replace("'", "\'", session('warning'))) ?>');
            }
        });
    </script>
<?php endif; ?>
