<style>
    .text-body-secondary {
        --bs-text-opacity: 1;
        color: var(--bs-secondary-color) !important;
    }
</style>
<div class="card p-0 mb-6">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between p-0 pt-6">
        <div class="app-academy-md-25 card-body py-0 pt-6 ps-12">
        </div>
        <div class="app-academy-md-50 card-body d-flex align-items-md-center flex-column text-md-center mb-6 py-6">
            <span class="card-title mb-4 px-md-12 h4">
                Revive tu Graduación: Selecciona y Personaliza tus <span class="text-primary text-nowrap">Fotos</span>.
            </span>
            <p class="mb-4">¡Felicitaciones por tu graduación! Sabemos el esfuerzo que has dedicado para llegar hasta aquí, y queremos ayudarte a preservar cada instante de este día tan significativo. ¡Bienvenido a tu espacio fotográfico!</p>
        </div>
        <div class="app-academy-md-25 d-flex align-items-end justify-content-end">
            <img src="http://localhost:8080/photoeventpro/public/static/img/pencil-rocket.png" alt="pencil rocket" height="180" class="scaleX-n1-rtl">
        </div>
    </div>
</div>

<div class="card bg-success text-white text-right mb-6">
    <div class="card-header d-flex flex-wrap justify-content-between gap-4">
        <div class="card-title mb-0 me-1 text-white">
            <h5 class="mb-0 text-white">¿Listo para elegir tus mejores momentos?
            </h5>
            <p class="mb-0 text-white">Tienes <?= $numServices ?> servicios</p>
        </div>
    </div>
</div>

<div class="row mb-12 g-6">
    <?= $services ?>
    <!-- <div class="col-md-6 col-lg-3">
        <div class="card h-100">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/default_product.jpg" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="javascript:void(0)" class="btn btn-outline-warning">Configurar</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"> Card title</h5>
                <h6 class="card-subtitle"><span class="badge bg-label-info"><i class="fas fa-print"></i></span> Support card subtitle</h6>
            </div>
            <img class="img-fluid" src="http://localhost:8080/photoeventpro/public/static/img/service-default-2.webp" alt="Card image cap">
            <div class="card-body">
                <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, quasi. Veniam eum est excepturi sint ullam vitae, possimus nam quae autem natus non inventore libero eligendi, consectetur quidem pariatur eaque?</p>
                <a href="javascript:void(0)" class="btn btn-outline-success">Ver</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">

        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Foto album</h5>
                <h6 class="card-subtitle"> <span class="badge bg-label-success"><i class="fas fa-download"></i></span> Impresion</h6>
                <img class="img-fluid d-flex mx-auto my-6 rounded" src="http://localhost:8080/photoeventpro/public/static/img/service-default-3.webp" alt="Card image cap">
                <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo, quasi. Veniam eum est excepturi sint ullam vitae, possimus nam quae autem natus non inventore libero eligendi, consectetur quidem pariatur eaque?</p>
                <a href="javascript:void(0)" class="btn btn-outline-primary">Descargar</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <h6 class="card-subtitle">Support card subtitle</h6>
            </div>
            <img class="img-fluid" src="http://localhost:8080/photoeventpro/public/static/img/1.png" alt="Card image cap">
            <div class="card-body">
                <p class="card-text">Bear claw sesame snaps gummies chocolate.</p>
                <a href="javascript:void(0);" class="card-link">Card link</a>
                <a href="javascript:void(0);" class="card-link">Another link</a>
            </div>
        </div>
    </div> -->
</div>

<!-- <div class="row mb-12 g-6">
    <div class="col-md">
        <div class="card">
            <div class="d-flex flex-md-row flex-column">
                <div>
                    <img class="card-img card-img-left" src="http://localhost:8080/photoeventpro/public/static/img/47.png" alt="Card image">
                </div>
                <div>
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="card">
            <div class="d-flex flex-md-row flex-column">
                <div>
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
                    </div>
                </div>
                <div>
                    <img class="card-img card-img-right" src="http://localhost:8080/photoeventpro/public/static/img/50.png" alt="Card image">
                </div>
            </div>
        </div>
    </div>
</div> -->


<!-- <div class="card-group mb-12">
    <div class="card">
        <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/4.png" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        </div>
        <div class="card-footer">
            <small class="text-body-secondary">Last updated 3 mins ago</small>
        </div>
    </div>
    <div class="card">
        <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/5.png" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
        </div>
        <div class="card-footer">
            <small class="text-body-secondary">Last updated 3 mins ago</small>
        </div>
    </div>
    <div class="card">
        <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/1.png" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
        </div>
        <div class="card-footer">
            <small class="text-body-secondary">Last updated 3 mins ago</small>
        </div>
    </div>
</div> -->

<!-- <h6 class="pb-1 mb-6 text-body-secondary">Grid Card</h6>
<div class="row row-cols-1 row-cols-md-3 g-6 mb-12">
    <div class="col">
        <div class="card h-100">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/23.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/24.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/25.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/18.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/19.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/20.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>
</div> -->





<!-- <h6 class="pb-1 mb-6 text-body-secondary">Masonry</h6>
<div class="row g-6" data-masonry="{&quot;percentPosition&quot;: true }" style="position: relative; height: 817.799px;">
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 0%; top: 0px;">
        <div class="card">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/5.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title that wraps to a new line</h5>
                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 33.3333%; top: 0px;">
        <div class="card p-4">
            <figure class="p-4 mb-0">
                <blockquote class="blockquote">
                    <p>A well-known quote, contained in a blockquote element.</p>
                </blockquote>
                <figcaption class="blockquote-footer mb-0 text-body-secondary">Someone famous in <cite title="Source Title">Source Title</cite></figcaption>
            </figure>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 66.6666%; top: 0px;">
        <div class="card">
            <img class="card-img-top" src="http://localhost:8080/photoeventpro/public/static/img/3.png" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 33.3333%; top: 152.283px;">
        <div class="card bg-primary text-white text-center p-4">
            <figure class="mb-0">
                <blockquote class="blockquote">
                    <p>A well-known quote, contained in a blockquote element.</p>
                </blockquote>
                <figcaption class="blockquote-footer mb-0 text-white">Someone famous in <cite title="Source Title">Source Title</cite></figcaption>
            </figure>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 33.3333%; top: 272.566px;">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This card has a regular title and short paragraph of text below it.</p>
                <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 33.3333%; top: 458.466px;">
        <div class="card">
            <img class="card-img" src="http://localhost:8080/photoeventpro/public/static/img/4.png" alt="Card image cap">
        </div>
    </div>
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 0%; top: 505.233px;">
        <div class="card p-4 text-end">
            <figure class="mb-0">
                <blockquote class="blockquote">
                    <p>A well-known quote, contained in a blockquote element.</p>
                </blockquote>
                <figcaption class="blockquote-footer mb-0 text-body-secondary">Someone famous in <cite title="Source Title">Source Title</cite></figcaption>
            </figure>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4" style="position: absolute; left: 66.6666%; top: 521.233px;">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is another card with title and supporting text below. This card has some additional content to make it slightly taller overall.</p>
                <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
            </div>
        </div>
    </div>
</div> -->