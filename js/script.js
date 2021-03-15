let btnSumAd = document.getElementById('sumAd');
let btnResAd = document.getElementById('resAd');
let btnSumNn = document.getElementById('sumNn');
let btnResNn = document.getElementById('resNn');
let btnSumHb = document.getElementById('sumHb');
let btnResHb = document.getElementById('resHb');
let btnAceptar = document.getElementById('aceptarModal');

let requestOptions = {
  method: 'GET',
  redirect: 'follow'
};

fetch("https://restcountries.eu/rest/v2/all", requestOptions)
.then(response => response.json())
.then(result => paises(result))
.catch(error => console.log('error', error));

let paises = result => {
    let id = 1;
    let paises = new Map();

    result.forEach(element => {
        paises.set(element.name, id);
        id++;
    });
    
    $('#destino').autocomplete({
        source: Object.fromEntries(paises),
        highlightClass: 'text-danger',
        treshold: 2,
    });
}

$('#fechas').daterangepicker({
    autoUpdateInput: false,
    locale: {
        format: 'MM/DD/YYYY',
        separator: ' - ',
        applyLabel: 'Aceptar',
        cancelLabel: 'Borrar',
        fromLabel: 'Desde',
        toLabel: 'A',
        customRangeLabel: 'Personalizar',
        weekLabel: 'S',
        daysOfWeek: [
            'Do',
            'Lu',
            'Ma',
            'Mi',
            'Ju',
            'Vi',
            'Sa'
        ],
        monthNames: [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ],
        firstDay: 1
    }
});

$('#fechas').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
});

$('#fechas').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
});

btnSumAd.addEventListener('click', e => {
    let contador = document.getElementById('adultos');
    contador.innerHTML = parseInt(contador.textContent) + 1;
});

btnResAd.addEventListener('click', e => {
    let contador = document.getElementById('adultos');
    let cantidad = parseInt(contador.textContent);

    if(cantidad > 1) {
        contador.innerHTML = cantidad - 1;
    }
});

btnSumNn.addEventListener('click', e => {
    let contador = document.getElementById('ninos');
    contador.innerHTML = parseInt(contador.textContent) + 1;
});

btnResNn.addEventListener('click', e => {
    let contador = document.getElementById('ninos');
    let cantidad = parseInt(contador.textContent);

    if(cantidad > 0) {
        contador.innerHTML = cantidad - 1;
    }
});

btnSumHb.addEventListener('click', e => {
    let contador = document.getElementById('habitaciones');
    contador.innerHTML = parseInt(contador.textContent) + 1;
});

btnResHb.addEventListener('click', e => {
    let contador = document.getElementById('habitaciones');
    let cantidad = parseInt(contador.textContent);

    if(cantidad > 1) {
        contador.innerHTML = cantidad - 1;
    }
});

btnAceptar.addEventListener('click', e => {
    let adultos = parseInt(document.getElementById('adultos').textContent);
    let ninos = parseInt(document.getElementById('ninos').textContent);
    let habitaciones = parseInt(document.getElementById('habitaciones').textContent);

    let turistas = document.getElementById('inputModal');
    turistas.setAttribute('value',`${adultos} Adulto(s) - ${ninos} Niño(s) - ${habitaciones} habitación(es)`);
    $('#Modal').modal('hide')
});