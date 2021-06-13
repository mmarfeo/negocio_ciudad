let slick_load = false;
function mostrar_negocios(negocios) {

    loading.hide();

    let div = $('#home_business');
    let data_empty = true;

    if (slick_load === true) {
        div.slick('unslick');
        div.html('');
        slick_init();
    }

    slick_load = false;
    negocios.forEach(function(negocio){
        let html = negocio_card_html(negocio);
        div.slick('slickAdd', html);
        slick_load = true;
        data_empty = false;
    });

    if (data_empty === true) {
        div.html('<h5>No encontramos coincidencias.</h5>')
    }
}
function negocio_card_html(negocio) {

    let url = `negocio/${negocio.plantilla_nombre.toLowerCase()}?n=${negocio.slug.toLowerCase()}`;
    if (validar_url(negocio.url)) {
        url = negocio.url;
    }

    return `<div class="business_container">
                <div class="business_img">
                    <img src="${SERVER_LOGOS + negocio.img_logo}" class="card-img-top" alt="${negocio.nombre}" onerror="this.onerror=null; this.src='img/negocio.png'">
                </div>
                <div class="business_title">
                    <h5>${negocio.nombre}</h5>
                </div>
                <div class="business_description"></div>
                <div class="business_button">
                    <a href="${url}" class="btn btn-outline-info" target="_blank"><i class="fas fa-paper-plane"></i> Entrar</a>
                </div>
            </div>`;
}

function buscar(search) {
    let parametros = {
        c : 'negocios',
        a : 'buscar',
        search : search
    };
    get_data(parametros, mostrar_negocios);
}