<style>
    :root {
        --primary-color: #3a86ff;
        --secondary-color: #8338ec;
        --accent-color: #ff006e;
        --light-bg: #f8f9fa;
        --dark-text: #212529;
        --border-color: #dee2e6;
        --hover-bg: #e9ecef;
    }

    .app-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header-title {
        font-weight: 600;
        margin-bottom: 0;
    }

    .header-icon {
        margin-right: 10px;
        font-size: 1.5rem;
    }

    .btn-custom {
        background-color: var(--primary-color);
        border: none;
        color: white;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-custom:hover {
        background-color: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-custom-secondary {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .btn-custom-secondary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .image-library {
        /* background-color: white; */
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        height: 65vh;
        overflow-y: auto;
        padding: 0.4rem;
    }

    .library-title {
        font-size: 1.1rem;
        /* font-weight: 600; */
        /* color: var(--dark-text); */
        /* margin-bottom: 1rem; */
        padding-bottom: 0.5rem;
        /* border-bottom: 2px solid var(--border-color); */
    }

    .preloaded-image {
        cursor: grab;
        transition: all 0.2s ease;
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        width: 100%;
    }

    .preloaded-image:hover {
        transform: scale(1.03);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .preloaded-image.dragging {
        opacity: 0.6;
        transform: scale(1.05);
    }

    .album-workspace {
        /* background-color: white; */
        /* border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); */
        height: 70vh;
        padding: 1.5rem;
    }

    .album-container {
        display: flex;
        flex-wrap: nowrap;
        gap: 20px;
        overflow-x: auto;
        padding: 15px 5px;
        width: 100%;
        scrollbar-width: thin;
        scrollbar-color: var(--primary-color) var(--light-bg);
        -ms-overflow-style: none;
        scroll-behavior: smooth;
    }

    .album-container::-webkit-scrollbar {
        height: 6px;
    }

    .album-container::-webkit-scrollbar-track {
        background: var(--light-bg);
        border-radius: 10px;
    }

    .album-container::-webkit-scrollbar-thumb {
        background-color: var(--primary-color);
        border-radius: 10px;
    }

    .album-sheet {
        display: flex;
        gap: 10px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 15px;
        transition: transform 0.3s ease;
    }

    .album-sheet:hover {
        transform: translateY(-5px);
    }

    .album-page {
        width: 180px;
        height: 270px;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: rgba(248, 249, 250, 0.5);
    }

    .album-page:hover {
        border-color: var(--primary-color);
        background-color: rgba(58, 134, 255, 0.05);
    }

    .album-page.drag-over {
        border-color: var(--primary-color);
        background-color: rgba(58, 134, 255, 0.1);
        transform: scale(1.02);
        box-shadow: 0 0 15px rgba(58, 134, 255, 0.2);
    }

    .album-page-label {
        position: absolute;
        bottom: 5px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(255, 255, 255, 0.9);
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.8rem;
        color: var(--dark-text);
        font-weight: 500;
        z-index: 10;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .album-page img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        border-radius: 6px;
    }

    .scroll-controls {
        display: flex;
        align-items: center;
        position: relative;
        width: 100%;
    }

    .scroll-button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        z-index: 2;
        transition: all 0.2s ease;
    }

    .scroll-button:hover {
        background-color: #2563eb;
        transform: scale(1.1);
    }

    #scrollLeft {
        position: absolute;
        left: -15px;
    }

    #scrollRight {
        position: absolute;
        right: -15px;
    }

    .tooltip-info {
        position: absolute;
        top: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 0.8rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        margin: 5px;
    }

    .page-actions {
        position: absolute;
        top: 0;
        right: 0;
        display: none;
        flex-direction: column;
        gap: 5px;
        margin: 5px;
        z-index: 15;
    }

    .album-page:hover .page-actions {
        display: flex;
    }

    .page-action-btn {
        background-color: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-text);
        font-size: 0.8rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .page-action-btn:hover {
        background-color: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }

    .workspace-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* margin-bottom: 1rem; */
    }

    .workspace-title {
        font-size: 1.2rem;
        font-weight: 600;
        /* color: var(--dark-text); */
    }

    .album-progress {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 1rem;
    }

    .progress-bar-container {
        flex-grow: 1;
        height: 8px;
        background-color: var(--light-bg);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: var(--primary-color);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .empty-page-message {
        color: #6c757d;
        font-size: 0.9rem;
        text-align: center;
    }

    .control-btn {
        border: none;
        background: transparent;
        color: var(--primary-color);
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .control-btn:hover {
        color: #2563eb;
        transform: scale(1.1);
    }

    .helper-tip {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 15px;
        max-width: 300px;
        z-index: 1000;
        font-size: 0.9rem;
        color: var(--dark-text);
        border-left: 4px solid var(--primary-color);
        display: none;
    }

    .helper-tip.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {

        .album-workspace,
        .image-library {
            height: auto;
            min-height: 400px;
        }

        .album-sheet {
            flex-direction: column;
            align-items: center;
        }
    }

    @media (max-width: 768px) {
        .image-library {
            margin-bottom: 20px;
        }

        .album-page {
            width: 150px;
            height: 225px;
        }
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
            background-color: white !important;
        }

        #layout-navbar,
        ol.breadcrumb,
        h2.text-center.mb-4,
        footer,
        header.app-header {
            display: none !important;
        }

        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
        }

        .container {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        .card {
            border: none !important;
            background-color: transparent !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 0 !important;
            page-break-after: avoid !important;
        }

        .card-body {
            padding: 0 !important;
        }

        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        .album-container {
            display: flex !important;
            flex-wrap: wrap !important;
            justify-content: flex-start !important;
            padding: 0 !important;
            border: none !important;
            margin: 0 !important;
            width: 100% !important;
            page-break-after: avoid !important;
            page-break-before: avoid !important;
            max-height: 28cm !important;
        }

        .album-sheet {
            max-width: 8.5cm !important;
            min-width: 8.5cm !important;
            min-height: 7cm !important;
            max-height: 7cm !important;
            margin-right: 1mm !important;
            margin-bottom: 0.5cm !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            vertical-align: top !important;
            background-color: white !important;
            padding: 2mm !important;
            box-sizing: border-box !important;
            border: 3px solid black !important;
            page-break-inside: avoid !important;
        }

        .album-sheet:nth-child(3n) {
            margin-right: 0 !important;
        }

        .album-page {
            width: 49% !important;
            height: 100% !important;
            border: 1px solid #ccc !important;
            border-radius: 3px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            overflow: hidden !important;
            background-color: #fff !important;
            border: 2px dashed #dee2e6 !important;
            margin: 0 !important;
        }

        .album-page:first-child {
            margin-right: 2% !important;
        }

        .album-page img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
        }

        .album-page-label {
            width: 100% !important;
            padding: 1mm !important;
            font-size: 8pt !important;
            color: #333 !important;
            text-align: center !important;
            background-color: #f5f5f5 !important;
        }

        html,
        body {
            height: auto !important;
            overflow: visible !important;
        }

        .album-container::after {
            content: none !important;
        }

        .album-sheet:last-child {
            page-break-after: avoid !important;
        }

        .service-info {
            display: inline !important;
        }

    }

    .service-info {
        font-weight: bold;
        font-size: 20px;

    }
</style>
<header class="app-header mb-4 rounded">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-camera-retro header-icon"></i>
                <h2 class="header-title">Imágenes seleccionadas por el usuario</h2>
            </div>
            <div>
                <button id="printAlbumBtn" class="btn btn-label-primary me-2">
                    <i class="fas fa-print me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</header>
<div class="card">
    <div class="card-body">
        <p class="service-info" style="display: none">Cliente: <?= $nombreCompleto ?></p>
        <p class="service-info" style="display: none">Servicio: <?= $servicio ?></p>
        <p class="service-info" style="display: none">Evento: <?= $evento ?></p>
        <div id="albumContainer" class="album-container">
            <?= $selectedPictures ?>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const printButton = document.getElementById('printAlbumBtn');
        if (printButton) {
            printButton.addEventListener('click', function() {
                window.print();
            });
        }
    });
</script>