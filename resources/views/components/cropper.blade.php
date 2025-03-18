
<!-- Estilos y scripts de Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let cropper;
    const imagenInput = document.getElementById("imagen");
    const preview = document.getElementById("preview");
    const imagenBase64 = document.getElementById("imagen_base64");

    imagenInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(preview, {
                    aspectRatio: 1, // Mantiene la imagen cuadrada 1:1
                    viewMode: 2,
                    autoCropArea: 1,
                    crop(event) {
                        const canvas = cropper.getCroppedCanvas({
                            width: 400,
                            height: 400,
                        });

                        imagenBase64.value = canvas.toDataURL("image/jpeg");
                    }
                });
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>