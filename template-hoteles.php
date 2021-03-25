
<?php
    /* Template Name: HotelSearch */

    include('tbo-api/core.php');

    $CountryList = null;
    $CityList = null;
    $HotelList = null;

    // Obtener querystring
    $queries = array();
    parse_str($_SERVER['QUERY_STRING'], $queries);

    // Lista de paises
    $CountryList = get_countries();

    // Lista de ciudades
    if (array_key_exists( 'CountryCode', $queries )) {
        $CityList = get_cities($queries['CountryCode']);
    }

    // Lista de hoteles
    if (
        array_key_exists( 'CheckInDate', $queries ) &&
        array_key_exists( 'CheckOutDate', $queries ) &&
        // array_key_exists( 'CountryName', $queries ) &&
        // array_key_exists( 'CityName', $queries ) &&
        array_key_exists( 'CityId', $queries ) &&
        // array_key_exists( 'StarRating', $queries ) &&
        // array_key_exists( 'OrderBy', $queries ) &&
        // array_key_exists( 'IsNearBySearchAllowed', $queries ) &&
        // array_key_exists( 'GuestNationality', $queries ) &&
        // array_key_exists( 'ResultCount', $queries ) &&
        array_key_exists( 'NoOfRooms', $queries ) &&
        array_key_exists( 'AdultCount', $queries ) &&
        array_key_exists( 'ChildCount', $queries )
        ){
        $HotelList = get_hotels(
            $queries['CheckInDate'],
            $queries['CheckOutDate'],
            $queries['CityId'],
            $queries['NoOfRooms'],
            $queries['AdultCount'],
            $queries['ChildCount'],
            10 // ResultCount
        );
    }

    // http://localhost:10004/sample-page/?CountryCode=CO&CheckInDate=2021-09-25&CheckOutDate=2021-09-26&CityId=25921&NoOfRooms=1&AdultCount=1&ChildCount=1
    // http://localhost:10004/sample-page/?CountryCode=&CityCode=25871&CheckDates=03%2F12%2F2021+-+03%2F19%2F2021&NoOfRooms=1&AdultCount=1&ChildCount=1"
    // $HotelList = get_hotels(
    //     '2021-09-25',
    //     '2021-09-26',
    //     24078, // 24078 // 24081
    //     1,
    //     1,
    //     1,
    //     1
    //   );

    // header("content-type: application/json");
    // echo json_encode($CountryList);

    $base_url = "http://localhost:10004/wp-content/uploads/hotel-page";
?>



<?php
	get_header();
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turismo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="<?echo $base_url?>/css/estilos.css"> -->
    <style type="text/css">
        .modal-backdrop {
            z-index: -1050;
        }

        .formulario {
            margin: 15px 20px 40px 20px !important;
        }


        .boton {
            margin: 0px 20px 0px 0px !important;
        }

        .botonTarjeta {
            float: right;
            margin-right: 20px;
        }

        .restar, .sumar {
            width: 40px;
            margin-top: -7px;
        }

        .hotel {
            border: solid 1px #4e4e50;
            padding: 30px 0px 30px 0px;
            margin: 10px 100px 10px 100px;
            border-radius: 12px 12px 12px 0px;
            -webkit-box-shadow: -4px 4px 2px 0px rgba(74,62,74,0.4);
            -moz-box-shadow: -4px 4px 2px 0px rgba(74,62,74,0.4);
            box-shadow: -4px 4px 2px 0px rgba(74,62,74,0.4);
        }

        .imagen {
            text-align: center;
        }

        .imagenHotel {
            height: 200px;
            width: 300px;
            border-radius: 12px;
        }

        .ubicacion p, .ubicacion div {
            font-size: 14px;
            margin-bottom: 0px;
        }

        .ubicacion div {
            padding-bottom: 20px;
        }

        .descripcion p, .detalles p {
            font-size: 15px;
            margin-bottom: 0px;
        }

        .especificaciones {
            font-size: 15px;
        }

        .detalles p {
            padding-bottom: 20px;
        }

        .clasificacion {
            margin-top: 5px;
            margin-left: 100px;
            padding-bottom: 45px;
        }

        .califiacion {
            margin-left: 10px;
        }

        .califiacion p {
            background: #007bff;
            padding: 5px 7px 5px 5px;
            margin-right: 15px;
            border-radius: 7px;
            color: white;
            font-weight: bold;
        }

        .duracion p {
            font-size: 12px;
            margin-bottom: 0px;
        }

        .precio, .duracion {
            text-align: right;
            margin-right: 20px;
        }

        .precio .valor {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 0px;
        }

        .detalle p {
            font-size: 12px;
        }

        .alert p{
            margin: 0px;
            padding: 0px;
        }

        #alerta {
            display: none;
        }

        @media (max-width: 1270px) {
            .clasificacion {
                margin-left: 80px;
            }
        }

        @media (max-width: 1220px) {
            .formulario {
                display: block;
            }

            .grupoForm {
                padding-bottom: 10px;
                max-width: 100%;
            }

            .clasificacion {
                margin-left: 70px;
            }
        }

        @media (max-width: 1162px) {
            .imagenHotel {
                height: 200px;
                width: 250px;
            }

            .clasificacion {
                margin-left: 50px;
            }
        }

        @media (max-width: 1092px) {
            .clasificacion {
                margin-left: 30px;
            }
        }

        @media (max-width: 1030px) {
            .clasificacion {
                margin-left: 10px;
            }
        }

        @media (max-width: 991px) {
            .imagen {
                padding-bottom: 20px;
            }

            .imagenHotel {
                width: 80%;
            }

            .clasificacion {
                margin-left: 130%;
            }
        }

        @media (max-width: 965px) {
            .clasificacion {
                margin-left: 100%;
            }
        }

        @media (max-width: 900px) {
            .clasificacion {
                margin-left: 80%;
            }
        }

        @media (max-width: 850px) {
            .clasificacion {
                margin-left: 60%;
            }
        }

        @media (max-width: 800px) {
            .clasificacion {
                margin-left: 40%;
            }
        }

        @media (max-width: 767px) {
            .grupoForm {
                padding-bottom: 0px;
            }

            .puntaje {
                display: none;
            }

            .izq {
                padding-top: 15px;
            }

            .derxs {
                float: left !important;
            }

            .califiacion {
                margin-left: 50px;
            }

            .precio, .duracion {
                text-align: left;
            }

            .ubicacion div, .detalles p {
                padding-bottom: 5px;
            }

            .descripcion p{
                font-size: 14px;
            }

            .especificaciones {
                font-size: 14px;
            }

            .hotel {
                margin: 10px 50px 10px 50px;
            }
        }

        @media (max-width: 570px) {
            .botonTarjeta, .btn-disp {
                width: 100%;
                margin-left: 20px;
            }
        }
    </style> 
</head>

<!-- <body class="list-unstyled"> -->
    <div class="alert alert-warning alert-dismissible" id="alerta">
        <a href="#" class="close" id="cerrarAlerta">&times;</a>
        <p><strong>Precaución!</strong> <span id="txtAlerta">Indicates a warning that might need attention.</span></p>
    </div>

    <form action="" method="get" autocomplete="off">
        <div class="row form-row text-center formulario">
            <div class="col-lg-7 col-md-12 grupoForm">
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <!-- <input class="form-control" type="text" name="destino" id="destino" placeholder="¿A que pais vas?"> -->
                        <select id="CountryCode" name="CountryCode" placeholder="Pais destino" class="form-control">
                            <option value="">Selecciones un pais ...</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <!-- <input class="form-control" type="text" name="ciudadDestino" id="ciudadDestino" placeholder="¿A que ciudad vas?" disabled> -->
                        <select id="CityCode" name="CityCode" placeholder="Ciudad destino" class="form-control">
                            <option value="">Selecciones una ciudad...</option>
                        </select>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <input class="form-control" type="datetime" name="CheckDates" id="CheckDates" placeholder="Check in - Check out" value="">
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12 grupoForm">
                <div class="row">
                    <div class="col-lg-8 col-md-8">
                        <input class="form-control" type="text" name="turistas" id="inputModal" value="1 Adulto(s) - 0 Niño(s) - 1 Habitación(es)" data-toggle="modal" data-target="#Modal" readonly>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <input class="btn btn-secondary btn-block boton" type="submit" id="btnBuscar" value="Buscar">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <section>
        <article class="row hotel">
            <div class="col-lg-4 imagen">
                <img class="imagenHotel" src="<?echo $base_url?>/img/hotel3.jpg">
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-12">
                        <div class="titulo">
                            <h2><strong>Sophia House</strong></h2>
                        </div>
                        <div class="ubicacion">
                            <p>Cartagena de Indias Mostrar en el mapa a 1,4 km del centro</p>
                            <div>
                                - Cerca de la playa
                            </div>
                        </div>
                        <div class="descripcion">
                            <p>Apartamento de 1 dormitorio</p>
                        </div>
                        <div class="detalles">
                            <p><strong>Apartamento entero ­ •­ 1 dormitorio •­ 1 baño</strong></p>
                        </div>
                        <div class="especificaciones">
                            1 litera
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 izq">
                        <div class="puntaje">
                            <div class="row">
                                <div class="col clearfix">
                                    <div class="float-left derxs">
                                        <p class="clasificacion text-info"><strong>Excepcional</strong></p>
                                    </div>
                                    <div class="float-right califiacion derxs">
                                        <p>10.0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="duracion">
                            <p>9 noches, 1 adulto</p>
                        </div>
                        <div class="precio">
                            <p class="valor">COP 400.000</p>
                            <div class="detalle">
                                <p>Se pueden aplicar otros cargos</p>
                            </div>
                        </div>
                        <div class="botonTarjeta">
                            <input type="button" class="btn btn-primary btn-disp" value="Ver disponibilidad">
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Turistas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-7">
                            <p>Adulto(s):</p>
                        </div>
                        <div class="col-lg-1">
                            <p><span id="adultos">1</span></p>
                        </div>
                        <div class="col-lg-4">
                            <input class="btn btn-secondary sumar" id="sumAd" type="submit" value="+">
                            <input class="btn btn-secondary restar" id="resAd" type="submit" value="-">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <p>Niño(s):</p>
                        </div>
                        <div class="col-lg-1">
                            <p><span id="ninos">0</span></p>
                        </div>
                        <div class="col-lg-4">
                            <input class="btn btn-secondary sumar" id="sumNn" type="submit" value="+">
                            <input class="btn btn-secondary restar" id="resNn" type="submit" value="-">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7">
                            <p>Habitación(es):</p>
                        </div>
                        <div class="col-lg-1">
                            <p><span id="habitaciones">1</span></p>
                        </div>
                        <div class="col-lg-4">
                            <input class="btn btn-secondary sumar" id="sumHb" type="submit" value="+">
                            <input class="btn btn-secondary restar" id="resHb" type="submit" value="-">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="aceptarModal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
<!-- </body> -->


<script src="https://code.jquery.com/jquery-1.12.4.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-4-autocomplete/dist/bootstrap-4-autocomplete.min.js" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<!-- Handlers -->
<script type="text/javascript">
    let btnSumAd = document.getElementById('sumAd');
    let btnResAd = document.getElementById('resAd');
    let btnSumNn = document.getElementById('sumNn');
    let btnResNn = document.getElementById('resNn');
    let btnSumHb = document.getElementById('sumHb');
    let btnResHb = document.getElementById('resHb');
    let btnAceptar = document.getElementById('aceptarModal');
    // let inputDestino = document.getElementById('destino');
    // let inputCiudadDestino = document.getElementById('ciudadDestino');
    let btnBuscar = document.getElementById('btnBuscar');
    let alerta = document.getElementById('alerta');
    let equisAlerta = document.getElementById('cerrarAlerta');

    //window.location.href = window.location.href + '?destino=paris';

    let requestOptions = {
    method: 'GET',
    redirect: 'follow'
    };

    $('#CheckDates').daterangepicker({
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

    $('#CheckDates').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('#CheckDates').on('cancel.daterangepicker', function(ev, picker) {
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

    // inputDestino.addEventListener('blur', e => {
    //     if (!window.location.href.includes('?destino=')) {
    //         window.location.href = window.location.href + '?destino=' + inputDestino.value;
    //     }
    // });

    // if (window.location.href.includes('destino')) {
    //     inputDestino.setAttribute('value', getParameterByName("destino"));
    //     // inputCiudadDestino.removeAttribute('disabled');
    // }

    btnBuscar.addEventListener('click', e => {
        e.preventDefault;

        // let pais = inputDestino.value;
        // let ciudad = inputCiudadDestino.value;
        let fechas = document.getElementById('CheckDates').value;
        let turistas = document.getElementById('inputModal').value;

        let txtAlerta = document.getElementById('txtAlerta');

        // if (pais.length !== 0) {} else {
        //     console.log('que pasa: ', !(pais.length === 0));
        //     txtAlerta.innerHTML = 'El campo del pais destino se encuentra vacío';
        //     alerta.style.display = 'block';
        // }
        // if (ciudad.length !== 0) {} else {
        //     txtAlerta.innerHTML = 'El campo de ciudad destino se encuentra vacío';
        //     alerta.style.display = 'block';
        // }
        if (fechas.length !== 0) {} else {
            txtAlerta.innerHTML = 'El campo de check-in y check-out se encuentra vacío';
            alerta.style.display = 'block';
        }
        if (turistas.length !== 0) {} else {
            txtAlerta.innerHTML = 'El campo de pasajeros se encuentra vacía';
            alerta.style.display = 'block';
        }
        
        var drp = $('#CheckDates').data('daterangepicker');
        check_in = drp.startDate.toISOString();
        check_out = drp.endDate.toISOString();

        url = new URL(location.href);
        url.searchParams.set("CityId", city_select[0].selectize.getValue());
        url.searchParams.set("CheckInDate", check_in);
        url.searchParams.set("CheckOutDate", check_out);
        url.searchParams.set("NoOfRooms",  1);
        url.searchParams.set("AdultCount", 1);
        url.searchParams.set("ChildCount", 1);

        window.location.href = url.href;

    });


    equisAlerta.addEventListener('click', e =>{
        alerta.style.display = 'none';
    });

    country_select = $('#CountryCode')
    country_select.selectize({ sortField: 'text'});
    country_select[0].selectize.on("blur", ()=> {
        country_code = country_select[0].selectize.getValue();
        url = new URL(location.href);
        url.searchParams.set("CountryCode", country_code);
        url.searchParams.delete("CityCode");
        window.location.href = url;
    });

    city_select = $('#CityCode')
    city_select.selectize({ sortField: 'text'});
    city_select[0].selectize.disable();

    
    function fill_countrylist(contry_map) {
        contry_map.forEach((val, key) => {
            country_select[0].selectize.addOption({value:key, text:val})
        });
        // country_select[0].selectize.refreshOptions();
    }
    
    
    function fill_citylist(city_map) {
        city_select[0].selectize.enable();
        city_map.forEach((val, key) => {
            city_select[0].selectize.addOption({value:key, text:val})
        });
        // city_select[0].selectize.refreshOptions();
    }
    
    
    // function getParameterByName(name) {
    //     name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    //     var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    //     results = regex.exec(location.search);
    //     return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    // }

    
</script>

<!-- Data -->
<script type="application/javascript">
    let CountryList = <?php echo json_encode($CountryList); ?> ;
    let CityList    = <?php echo json_encode($CityList); ?> ;
    let HotelList   = <?php echo json_encode($HotelList); ?> ;

    // $( document ).ready(function() {
        // Fill Countrylist
        if (!CountryList) {
            
        }else if ( CountryList && CountryList["CountryListResponse"]["Status"]["StatusCode"] == "01") {
            var mapa = new Map();
            CountryList["CountryListResponse"]["CountryList"]["Country"].forEach(element => {
                mapa.set(element["@attributes"]["CountryCode"], element["@attributes"]["CountryName"] );
            });
            fill_countrylist(mapa);
        }else{
            let txtAlerta = document.getElementById('txtAlerta');
            txtAlerta.innerHTML = 'Error al cargar la lista de paises.';
            alerta.style.display = 'block';
        }

        // Fill Citylist
        if (!CityList) {
        }else if ( CityList["DestinationCityListResponse"]["Status"]["StatusCode"] == "01") {
            var mapa = new Map();
            CityList["DestinationCityListResponse"]["CityList"]["City"].forEach(element => {
                mapa.set(element["@attributes"]["CityCode"], element["@attributes"]["CityName"] );
            });
            fill_citylist(mapa);
        }else{
            let txtAlerta = document.getElementById('txtAlerta');
            txtAlerta.innerHTML = 'Error al cargar la lista de ciudades.';
            alerta.style.display = 'block';
        }


        // Fill HotelList
        if (!HotelList) {
        }else if ( HotelList["HotelSearchResponse"]["Status"]["StatusCode"] == "01") {
            var mapa = new Map();
            HotelList["HotelResultList"]["HotelResult"].forEach(element => {
                
                /*
                    HotelInfo:
                        HotelAddress: "AL Mina Road P.O. Box 49789 "
                        HotelCode: "1347149"
                        HotelDescription: "location: Ideally located, The hotel is a short drive to Dubai International Airport, offers a quick access to the Dubai world Trade Centre to Jumeira Beach in just a few minutes and is only five minu "
                        HotelName: "Smana Hotel Al Raffa"
                        HotelPicture: "https://api.tbotechnology.in/imageresource.aspx?img=9eMP+0FIICgCIk6ZClzZH9Cs+1gwAq6BFWcc22yNLMF/UJIXMdxPdTX9IMA+gOFHAhyYwG4mGXJMcwGbaMkwO8RtGve3xXDaZGC76EZq9Gu4/3T8hDjRkpUxipNSXNHC4NmqiIRkKkY="
                        HotelPromotion:
                        __proto__: Object
                        Latitude: "25.249460"
                        Longitude: "55.282760"
                        Rating: "ThreeStar"
                        TagIds: "25"
                        TripAdvisorRating: "3.0"
                        TripAdvisorReviewURL: "http://www.tripadvisor.com/Hotel_Review-g295424-d1590818-Reviews-Smana_Hotel_Al_Raffa-Dubai_Emirate_"
                        __proto__: Object
                        IsHalal: "false"
                        IsPackageRate: "false"
                        IsPkgProperty: "false"
                        MappedHotel: "true"
                        MinHotelPrice:
                        @attributes:
                        B2CRates: "false"
                        Currency: "USD"
                        OriginalPrice: "29.93"
                        TotalPrice: "29.93"
                        __proto__: Object
                        __proto__: Object
                        ResultIndex: "1"
                */
                
            });
            fill_citylist(mapa);
        }else{
            // console.log("error")
            let txtAlerta = document.getElementById('txtAlerta');
            txtAlerta.innerHTML = 'Error al cargar la lista de ciudades.';
            alerta.style.display = 'block';
        }

    // });

</script>


<?php
    get_footer();
    // phpinfo();
?>



