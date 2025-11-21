function previewFile(inputId, previewId) {
const file = document.getElementById(inputId).files[0];
const preview = document.getElementById(previewId);


if (!file) return;


if (file.type.startsWith("image/")) {
const reader = new FileReader();
reader.onload = function(e) {
preview.src = e.target.result;
preview.style.display = 'block';
}
reader.readAsDataURL(file);
} else {
preview.style.display = 'none';
}
}