function message(icon, text) {
    Swal.fire({
        text: text,
        icon: icon,
        timerProgressBar: true,
        allowOutsideClick: false,
        showConfirmButton: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        timer: 2000
    });
}

function redirect(url) {
    window.location.href = url;
}