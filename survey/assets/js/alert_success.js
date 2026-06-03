document.body.setAttribute('onload', '');
// window.addEventListener('load', function() {

function success() {
    var alertBox = document.querySelector('.alert-box');
    if (document.referrer) {
        alertBox.classList.remove('hide');
        alertBox.classList.add('show');
        setTimeout(function() {
            alertBox.classList.remove('show');
            alertBox.classList.add('hide');
        }, 5000);
    }
    var closeBtn = document.querySelector('.close-alert');
    closeBtn.addEventListener('click', function() {
        alertBox.classList.remove('show');
        alertBox.classList.add('hide');
    });
}
// });