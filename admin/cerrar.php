<?php
$url_pagina = "http://localhost/Restaurante/";

session_start();
session_unset();
session_destroy();

header('Location:' . $url_pagina);
exit;
