<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<link rel="stylesheet" href="<?php echo e(asset('../resources/css/login.css')); ?>">

<section>
    <div class="login-holder box">
        <div>
            <div>E-mail</div>
            <input param="email" type="text">
        </div>
        <div style="margin-top: 10px">
            <div>Jelszó</div>
            <input param="password" type="password">
        </div>
        <div style="display: flex; justify-content: center; padding-top: 15px;">
            <div>
                <div btn-login class="button">Bejelentkezés</div>
            </div>
        </div>
    </div>    
</section>


<script>
    window.onload = async function() {
        $('[btn-login]').on('click', e => {
            let email = $('input[param=email]').val();
            let pw    = $('input[param=password]').val();
    
            $.ajax({
                type: 'GET',
                url:  '<?php echo e(url("/login/signIn")); ?>',
                data: {
                    email:    email,
                    password: pw
                },
                success: (response) => {
                    window.location="<?php echo e(route('mainPage')); ?>";
                },
                error: e => {
                    $('input[param=email], input[param=password]').css('border', '1px solid red');
                }
            });
        });
    }
</script><?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/login.blade.php ENDPATH**/ ?>