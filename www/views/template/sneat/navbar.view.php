<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0   d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="<?= $imgPath ?><?= $avatar ?>" alt class="w-px-40 h-auto rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#!">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="<?= $imgPath ?><?= $avatar ?>" alt class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= $nombre ?></h6>
                                    <small class="text-muted"><?= $rol ?></small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#!" target="_blank" id="logout">
                            <i class="bx bx-power-off bx-md me-3"></i><span>Desconectarse</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function handleLogoutResponse(response) {
            if (response.status === "success") {
                let alertType = "success";
                let icon = "far fa-check-circle";
                let msg = response.message;
                let responseDiv = document.getElementById("message");
                responseDiv.innerHTML = "";
                const alert = BootstrapAlertFactory.createAlert({
                    message: msg,
                    type: alertType,
                    dismissible: true,
                    icon: icon,
                });
                responseDiv.appendChild(alert.generateAlert());
                setTimeout(function() {
                    window.location.href = response.url;
                }, 1000);
            } else if (response.status === "fail") {
                let alertType = "danger";
                let icon = "fas fa-exclamation-triangle";
                let msg = response.message;
                let responseDiv = document.getElementById("message");
                responseDiv.innerHTML = "";
                const alert = BootstrapAlertFactory.createAlert({
                    message: msg,
                    type: alertType,
                    dismissible: true,
                    icon: icon,
                });
                responseDiv.appendChild(alert.generateAlert());
            }
        }
        let trash = document.getElementById("trash").getAttribute("data-valor-base64");
        let appFolderAddress = AjaxHandler.decodificarBase64(trash);
        const logoutHandler = new AjaxHandler(appFolderAddress + '/logout', handleLogoutResponse);

        $("#logout").click(function(event) {
            event.preventDefault();
            logoutHandler.sendRequest(); // En este caso no enviamos datos adicionales
        });
    });
</script>