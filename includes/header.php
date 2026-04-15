<!-- ================= META ================= -->
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- ================= WEBFONT ================= -->
<script src="../assets/js/plugin/webfont/webfont.min.js"></script>

<script>
WebFont.load({
    google: { families: ["Public Sans:300,400,500,600,700"] },
    custom: {
        families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons"
        ],
        urls: ["../assets/css/fonts.min.css"]
    },
    active: function () {
        sessionStorage.fonts = true;
    }
});
</script>

<!-- ================= CSS ================= -->
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/plugins.min.css">
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css">

<!-- OPTIONAL (kalau ada custom sendiri) -->
<link rel="stylesheet" href="../assets/css/custom.css">