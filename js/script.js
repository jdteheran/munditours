let btnSumAd = document.getElementById('sumAd');
let btnResAd = document.getElementById('resAd');
let btnSumNn = document.getElementById('sumNn');
let btnResNn = document.getElementById('resNn');
let btnSumHb = document.getElementById('sumHb');
let btnResHb = document.getElementById('resHb');
let btnAceptar = document.getElementById('aceptarModal');
let inputDestino = document.getElementById('destino');
let inputCiudadDestino = document.getElementById('ciudadDestino');
let btnBuscar = document.getElementById('btnBuscar');
let alerta = document.getElementById('alerta');
let equisAlerta = document.getElementById('cerrarAlerta');

//window.location.href = window.location.href + '?destino=paris';

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
    turistas.setAttribute('value',`${adultos} Adulto(s) - ${ninos} Niño(s) - ${habitaciones} Habitación(es)`);
    $('#Modal').modal('hide')
});

inputDestino.addEventListener('blur', e => {
    if (!window.location.href.includes('?destino=')) {
        window.location.href = window.location.href + '?destino=paris';
    }
});

if (window.location.href.includes('destino')) {
    inputDestino.setAttribute('value','Paris');
    inputCiudadDestino.removeAttribute('disabled');
}

btnBuscar.addEventListener('click', e => {
    let pais = inputDestino.value;
    let ciudad = inputCiudadDestino.value;
    let fechas = document.getElementById('fechas').value;
    let turistas = document.getElementById('inputModal').value;

    let txtAlerta = document.getElementById('txtAlerta');

    console.log('mirame: ', turistas);

    if (pais.length !== 0) {
        if (ciudad.length !== 0) {
           if (fechas.length !== 0) {
                if (turistas.length !== 0) {

                } else {
                    txtAlerta.innerHTML = 'El campo de pasajeros se encuentra vacía';
                    alerta.style.display = 'block';
                }
            } else {
                txtAlerta.innerHTML = 'El campo de check-in y check-out se encuentra vacío';
                alerta.style.display = 'block';
            }
        } else {
            txtAlerta.innerHTML = 'El campo de ciudad destino se encuentra vacío';
            alerta.style.display = 'block';
        }
    } else {
        console.log('que pasa: ', !(pais.length === 0));
        txtAlerta.innerHTML = 'El campo del pais destino se encuentra vacío';
        alerta.style.display = 'block';
    }

    e.preventDefault;
});


equisAlerta.addEventListener('click', e =>{
    alerta.style.display = 'none';
});