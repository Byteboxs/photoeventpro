<style>
    /* Estilos base mejorados */
    .product-card {
        height: 100%;
        transition: all 0.3s;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        position: relative;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .product-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    .product-img-container {
        position: relative;
        overflow: hidden;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .product-img {
        max-height: 80%;
        max-width: 80%;
        object-fit: contain;
        transition: transform 0.5s;
    }

    .product-card:hover .product-img {
        transform: scale(1.1);
    }

    .product-info {
        padding: 18px;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 12px;
        height: 2.6rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        color: #2c3e50;
    }

    .product-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 8px;
    }

    .product-rating {
        margin: 10px 0;
        display: flex;
        align-items: center;
    }

    .rating-stars {
        color: #ffc107;
        font-size: 1rem;
        letter-spacing: -1px;
    }

    .rating-count {
        color: #6c757d;
        font-size: 0.8rem;
        margin-left: 5px;
    }

    .product-description {
        font-size: 0.95rem;
        color: #6c757d;
        margin-bottom: 15px;
        height: 4rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        line-height: 1.4;
    }

    .add-to-cart-btn {
        width: 100%;
        border-radius: 6px;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.9rem;
        padding: 8px 0;
        transition: all 0.3s;
        letter-spacing: 0.5px;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }

    /* Etiqueta de descuento */
    .discount-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        z-index: 2;
    }

    /* Indicador de stock */
    .stock-indicator {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .in-stock {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    /* Servicios deshabilitados */
    .product-card.disabled {
        opacity: 0.7;
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        box-shadow: none;
        pointer-events: none;
    }

    .product-card.disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .product-card.disabled .product-img {
        filter: grayscale(100%);
    }

    .product-card.disabled .product-info {
        position: relative;
    }

    .disabled-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .disabled-message {
        background-color: rgba(108, 117, 125, 0.9);
        color: white;
        padding: 5px 15px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.85rem;
        transform: rotate(-5deg);
    }
</style>

<div class="container py-5">
    <h2 class="text-center mb-4">Mis Servicios</h2>
    <!-- <small class="d-block text-center text-uppercase text-body-secondary small mb-4">Seleccione los servicios deseados</small> -->
    <?= $servicios ?>

    <!-- <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <div class="col">
            <div class="product-card disabled">
                <div class="product-img-container">
                    <img src="https://placehold.co/400" class="product-img" alt="iPhone 14">
                </div>
                <div class="product-info">
                    <div class="disabled-overlay">
                        <span class="disabled-message">Inactivo</span>
                    </div>
                    <h3 class="product-title">iPhone 14 Pro Max 256GB</h3>
                    <p class="product-price">$1,099.99</p>
                    <p class="product-description">El nuevo iPhone 14 Pro Max con chip A16 Bionic, pantalla Super Retina XDR y cámara de 48MP.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="product-card">
                <div class="product-img-container">
                    <img src="https://placehold.co/400" class="product-img" alt="MacBook Air">
                </div>
                <div class="product-info">
                    <div class="stock-indicator in-stock"><i class="fas fa-camera-retro"></i> Ver</div>
                    <h3 class="product-title">MacBook Air M2</h3>
                    <p class="product-price">$1,199.99</p>
                    <div class="product-rating">
                        <div class="rating-stars">★★★★★</div>
                        <span class="rating-count">(76)</span>
                    </div>
                    <p class="product-description">MacBook Air con chip M2, pantalla Liquid Retina de 13.6" y 8GB de RAM.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="product-card">
                <div class="discount-badge">-20%</div>
                <div class="product-img-container">
                    <img src="https://placehold.co/800x600" class="product-img" alt="Galaxy Watch">
                </div>
                <div class="product-info">
                    <div class="stock-indicator in-stock">Últimas unidades</div>
                    <h3 class="product-title">Samsung Galaxy Watch 6 Classic</h3>
                    <p class="product-price">$349.99</p>
                    <div class="product-rating">
                        <div class="rating-stars">★★★★½</div>
                        <span class="rating-count">(53)</span>
                    </div>
                    <p class="product-description">Smartwatch con bisel giratorio, monitoreo avanzado de salud y batería de larga duración.</p>
                    <button class="btn btn-primary add-to-cart-btn">Añadir al carrito</button>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="product-card">
                <div class="discount-badge">-20%</div>
                <div class="product-img-container">
                    <img src="https://placehold.co/800x600" class="product-img" alt="Galaxy Watch">
                </div>
                <div class="product-info">
                    <div class="stock-indicator in-stock">Últimas unidades</div>
                    <h3 class="product-title">Samsung Galaxy Watch 6 Classic</h3>
                    <p class="product-price">$349.99</p>
                    <div class="product-rating">
                        <div class="rating-stars">★★★★½</div>
                        <span class="rating-count">(53)</span>
                    </div>
                    <p class="product-description">Smartwatch con bisel giratorio, monitoreo avanzado de salud y batería de larga duración.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="product-card">
                <div class="discount-badge">-20%</div>
                <div class="product-img-container">
                    <img src="https://placehold.co/800x600" class="product-img" alt="Galaxy Watch">
                </div>
                <div class="product-info">
                    <div class="stock-indicator in-stock">Últimas unidades</div>
                    <h3 class="product-title">Samsung Galaxy Watch 6 Classic</h3>
                    <p class="product-price">$349.99</p>
                    <div class="product-rating">
                        <div class="rating-stars">★★★★½</div>
                        <span class="rating-count">(53)</span>
                    </div>
                    <p class="product-description">Smartwatch con bisel giratorio, monitoreo avanzado de salud y batería de larga duración.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="product-card">
                <div class="discount-badge">-20%</div>
                <div class="product-img-container">
                    <img src="https://placehold.co/800x600" class="product-img" alt="Galaxy Watch">
                </div>
                <div class="product-info">
                    <div class="stock-indicator in-stock">Últimas unidades</div>
                    <h3 class="product-title">Samsung Galaxy Watch 6 Classic</h3>
                    <p class="product-price">$349.99</p>
                    <div class="product-rating">
                        <div class="rating-stars">★★★★½</div>
                        <span class="rating-count">(53)</span>
                    </div>
                    <p class="product-description">Smartwatch con bisel giratorio, monitoreo avanzado de salud y batería de larga duración.</p>
                </div>
            </div>
        </div>
    </div> -->
</div>