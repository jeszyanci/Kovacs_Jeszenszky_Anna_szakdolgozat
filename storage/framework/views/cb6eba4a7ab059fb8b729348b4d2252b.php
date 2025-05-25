<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="<?php echo e(asset('../resources/js/main/myClasses/Repair.js')); ?>"></script>
<script async src="<?php echo e(asset('../resources/js/main/newRepair.js')); ?>"></script>

<section>
    <div class="menu" style="display: inline-flex;">
        <div id="backBtn" class="button box" onclick=" window.location='<?php echo e(url('/')); ?>'">Vissza</div>
        <div id="saveOrder" class="button box">Mentés</div>
        <div class="printBtn button box">Nyomtatás</div>
    </div>
</section>
<section>
    <div class="formHolder"></div>
</section>

<body mainBody></body>

<?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/newRepairPage.blade.php ENDPATH**/ ?>