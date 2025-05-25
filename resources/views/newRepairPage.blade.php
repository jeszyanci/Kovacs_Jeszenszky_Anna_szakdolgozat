@include('layout')

<script src="{{ asset('../resources/js/main/myClasses/Repair.js') }}"></script>
<script async src="{{ asset('../resources/js/main/newRepair.js')}}"></script>

<section>
    <div class="menu" style="display: inline-flex;">
        <div id="backBtn" class="button box" onclick=" window.location='{{ url('/') }}'">Vissza</div>
        <div id="saveOrder" class="button box">Mentés</div>
        <div class="printBtn button box">Nyomtatás</div>
    </div>
</section>
<section>
    <div class="formHolder"></div>
</section>

<body mainBody></body>

