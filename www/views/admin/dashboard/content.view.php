<div class="card mb-5">
    <div class="d-flex align-items-start row">
        <div class="col-sm-8">
            <div class="card-body">
                <h5 class="card-title text-primary mb-3">Bienvenido de nuevo 🎉</h5>
                <p class="mb-6">¡Bienvenido al panel de administración! Desde aquí, tienes el control total para gestionar los servicios fotográficos, usuarios y la configuración del sistema. ¡Esperamos que esta herramienta te sea de gran utilidad!</p>

                <a href="<?= $appPath ?>/crear-proyecto" class="btn btn-label-warning">
                    <i class="fas fa-project-diagram mx-2"></i> Crea un evento
                </a>
            </div>
        </div>
        <div class="col-sm-4 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-6">
                <img src="<?= $imgPath ?>/man-with-laptop.png" height="175" class="scaleX-n1-rtl" alt="View Badge User">
            </div>
        </div>
    </div>
</div>
<div class="card bg-transparent shadow-none my-6 border-0">
    <div class="card-body row p-0 pb-6 g-6">
        <!-- <div class="col-12 col-lg-8 card-separator"> -->
        <div class="col-12 col-lg-8">
            <h5 class="mb-2">Anuncios<span class="h4"> importantes 👋🏻</span></h5>
            <div class="col-12 col-lg-5">
                <p>Aca encontrara información importante sobre la plataforma.
                </p>
            </div>
            <div class="d-flex justify-content-between flex-wrap gap-4 me-12">
                <?php foreach ($KPIs as $kpi) { ?>
                    <?= $kpi ?>
                <?php } ?>
            </div>
        </div>
        <!-- <div class="col-12 col-lg-4 ps-md-4 ps-lg-6">
            <div class="d-flex justify-content-between align-items-center" style="position: relative;">
                <div>
                    <div>
                        <h5 class="mb-1">Lorem ipsum</h5>
                        <p class="mb-9">Lorem ipsum</p>
                    </div>
                    <div class="time-spending-chart">
                        <h4 class="mb-2">231<span class="text-body">h</span> 14<span class="text-body">m</span></h4>
                        <span class="badge bg-label-success">+18.4%</span>
                    </div>
                </div>
                <div id="leadsReportChart" style="min-height: 152.8px;">
                    <div id="apexchartsyzb7x5li" class="apexcharts-canvas apexchartsyzb7x5li apexcharts-theme-light" style="width: 150px; height: 152.8px;"><svg id="SvgjsSvg1216" width="150" height="152.79999999999998" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                            <g id="SvgjsG1218" class="apexcharts-inner apexcharts-graphical" transform="translate(3, 0)">
                                <defs id="SvgjsDefs1217">
                                    <clipPath id="gridRectMaskyzb7x5li">
                                        <rect id="SvgjsRect1220" width="150" height="168" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                    <clipPath id="forecastMaskyzb7x5li"></clipPath>
                                    <clipPath id="nonForecastMaskyzb7x5li"></clipPath>
                                    <clipPath id="gridRectMarkerMaskyzb7x5li">
                                        <rect id="SvgjsRect1221" width="150" height="172" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                    </clipPath>
                                </defs>
                                <g id="SvgjsG1222" class="apexcharts-pie">
                                    <g id="SvgjsG1223" transform="translate(0, 0) scale(1)">
                                        <circle id="SvgjsCircle1224" r="47.05365853658536" cx="73" cy="73" fill="transparent"></circle>
                                        <g id="SvgjsG1225" class="apexcharts-slices">
                                            <g id="SvgjsG1226" class="apexcharts-series apexcharts-pie-series" seriesName="36h" rel="1" data:realIndex="0">
                                                <path id="SvgjsPath1227" d="M 73 5.7804878048780495 A 67.21951219512195 67.21951219512195 0 0 1 129.19050295799423 36.10704407237351 L 112.33335207059596 47.17493085066146 A 47.05365853658536 47.05365853658536 0 0 0 73 25.94634146341464 L 73 5.7804878048780495 z" fill="rgba(90,177,44,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-0" index="0" j="0" data:angle="56.71232876712329" data:startAngle="0" data:strokeWidth="0" data:value="23" data:pathOrig="M 73 5.7804878048780495 A 67.21951219512195 67.21951219512195 0 0 1 129.19050295799423 36.10704407237351 L 112.33335207059596 47.17493085066146 A 47.05365853658536 47.05365853658536 0 0 0 73 25.94634146341464 L 73 5.7804878048780495 z"></path>
                                            </g>
                                            <g id="SvgjsG1228" class="apexcharts-series apexcharts-pie-series" seriesName="56h" rel="2" data:realIndex="1">
                                                <path id="SvgjsPath1229" d="M 129.19050295799423 36.10704407237351 A 67.21951219512195 67.21951219512195 0 0 1 113.4408760548265 126.69355979694687 L 101.30861323837854 110.5854918578628 A 47.05365853658536 47.05365853658536 0 0 0 112.33335207059596 47.17493085066146 L 129.19050295799423 36.10704407237351 z" fill="rgba(102,199,50,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-1" index="0" j="1" data:angle="86.30136986301369" data:startAngle="56.71232876712329" data:strokeWidth="0" data:value="35" data:pathOrig="M 129.19050295799423 36.10704407237351 A 67.21951219512195 67.21951219512195 0 0 1 113.4408760548265 126.69355979694687 L 101.30861323837854 110.5854918578628 A 47.05365853658536 47.05365853658536 0 0 0 112.33335207059596 47.17493085066146 L 129.19050295799423 36.10704407237351 z"></path>
                                            </g>
                                            <g id="SvgjsG1230" class="apexcharts-series apexcharts-pie-series" seriesName="16h" rel="3" data:realIndex="2">
                                                <path id="SvgjsPath1231" d="M 113.4408760548265 126.69355979694687 A 67.21951219512195 67.21951219512195 0 0 1 87.35277177931896 138.66932892911984 L 83.04694024552327 118.96853025038388 A 47.05365853658536 47.05365853658536 0 0 0 101.30861323837854 110.5854918578628 L 113.4408760548265 126.69355979694687 z" fill="rgba(113,221,55,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-2" index="0" j="2" data:angle="24.657534246575352" data:startAngle="143.013698630137" data:strokeWidth="0" data:value="10" data:pathOrig="M 113.4408760548265 126.69355979694687 A 67.21951219512195 67.21951219512195 0 0 1 87.35277177931896 138.66932892911984 L 83.04694024552327 118.96853025038388 A 47.05365853658536 47.05365853658536 0 0 0 101.30861323837854 110.5854918578628 L 113.4408760548265 126.69355979694687 z"></path>
                                            </g>
                                            <g id="SvgjsG1232" class="apexcharts-series apexcharts-pie-series" seriesName="32h" rel="4" data:realIndex="3">
                                                <path id="SvgjsPath1233" d="M 87.35277177931896 138.66932892911984 A 67.21951219512195 67.21951219512195 0 0 1 32.55912394517351 126.69355979694689 L 44.69138676162146 110.58549185786282 A 47.05365853658536 47.05365853658536 0 0 0 83.04694024552327 118.96853025038388 L 87.35277177931896 138.66932892911984 z" fill="rgba(141,228,95,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-3" index="0" j="3" data:angle="49.315068493150676" data:startAngle="167.67123287671234" data:strokeWidth="0" data:value="20" data:pathOrig="M 87.35277177931896 138.66932892911984 A 67.21951219512195 67.21951219512195 0 0 1 32.55912394517351 126.69355979694689 L 44.69138676162146 110.58549185786282 A 47.05365853658536 47.05365853658536 0 0 0 83.04694024552327 118.96853025038388 L 87.35277177931896 138.66932892911984 z"></path>
                                            </g>
                                            <g id="SvgjsG1234" class="apexcharts-series apexcharts-pie-series" seriesName="56h" rel="5" data:realIndex="4">
                                                <path id="SvgjsPath1235" d="M 32.55912394517351 126.69355979694689 A 67.21951219512195 67.21951219512195 0 0 1 16.809497042005773 36.10704407237351 L 33.66664792940404 47.17493085066146 A 47.05365853658536 47.05365853658536 0 0 0 44.69138676162146 110.58549185786282 L 32.55912394517351 126.69355979694689 z" fill="rgba(170,235,135,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-4" index="0" j="4" data:angle="86.30136986301372" data:startAngle="216.986301369863" data:strokeWidth="0" data:value="35" data:pathOrig="M 32.55912394517351 126.69355979694689 A 67.21951219512195 67.21951219512195 0 0 1 16.809497042005773 36.10704407237351 L 33.66664792940404 47.17493085066146 A 47.05365853658536 47.05365853658536 0 0 0 44.69138676162146 110.58549185786282 L 32.55912394517351 126.69355979694689 z"></path>
                                            </g>
                                            <g id="SvgjsG1236" class="apexcharts-series apexcharts-pie-series" seriesName="16h" rel="6" data:realIndex="5">
                                                <path id="SvgjsPath1237" d="M 16.809497042005773 36.10704407237351 A 67.21951219512195 67.21951219512195 0 0 1 72.98826798196566 5.780488828689755 L 72.99178758737597 25.946342180082837 A 47.05365853658536 47.05365853658536 0 0 0 33.66664792940404 47.17493085066146 L 16.809497042005773 36.10704407237351 z" fill="rgba(198,241,175,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-5" index="0" j="5" data:angle="56.71232876712327" data:startAngle="303.28767123287673" data:strokeWidth="0" data:value="23" data:pathOrig="M 16.809497042005773 36.10704407237351 A 67.21951219512195 67.21951219512195 0 0 1 72.98826798196566 5.780488828689755 L 72.99178758737597 25.946342180082837 A 47.05365853658536 47.05365853658536 0 0 0 33.66664792940404 47.17493085066146 L 16.809497042005773 36.10704407237351 z"></path>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="SvgjsG1238" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"><text id="SvgjsText1239" font-family="Helvetica, Arial, sans-serif" x="73" y="93" text-anchor="middle" dominant-baseline="auto" font-size=".9375rem" font-weight="400" fill="#7e7f96" class="apexcharts-text apexcharts-datalabel-label" style="font-family: Helvetica, Arial, sans-serif;">Total</text><text id="SvgjsText1240" font-family="Public Sans" x="73" y="69" text-anchor="middle" dominant-baseline="auto" font-size="1.125rem" font-weight="500" fill="#d5d5e2" class="apexcharts-text apexcharts-datalabel-value" style="font-family: Public Sans;">231h</text></g>
                                </g>
                                <line id="SvgjsLine1241" x1="0" y1="0" x2="146" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                <line id="SvgjsLine1242" x1="0" y1="0" x2="146" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                            </g>
                            <g id="SvgjsG1219" class="apexcharts-annotations"></g>
                        </svg>
                        <div class="apexcharts-legend"></div>
                        <div class="apexcharts-tooltip apexcharts-theme-false">
                            <div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(90, 177, 44);"></span>
                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                    <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                            <div class="apexcharts-tooltip-series-group" style="order: 2;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(102, 199, 50);"></span>
                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                    <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                            <div class="apexcharts-tooltip-series-group" style="order: 3;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(113, 221, 55);"></span>
                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                    <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                            <div class="apexcharts-tooltip-series-group" style="order: 4;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(141, 228, 95);"></span>
                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                    <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                            <div class="apexcharts-tooltip-series-group" style="order: 5;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(170, 235, 135);"></span>
                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                    <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                            <div class="apexcharts-tooltip-series-group" style="order: 6;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(198, 241, 175);"></span>
                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                    <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="resize-triggers">
                    <div class="expand-trigger">
                        <div style="width: 324px; height: 160px;"></div>
                    </div>
                    <div class="contract-trigger"></div>
                </div>
            </div>
        </div> -->
    </div>
</div>