const modal = document.getElementById("myModal");
const span = document.getElementsByClassName("close")[0];

function showModal(gifUrl, redirectUrl) {
    const embedCode = `<a href="${redirectUrl}" target="_blank"><img src="${gifUrl}" alt="Generated GIF"></a>`;

    const embedCodeElement = document.getElementById('embed-code');
    embedCodeElement.value = embedCode;
    modal.style.display = "block";
}

document.getElementById('copy-button').addEventListener('click', function() {
    const embedCodeElement = document.getElementById('embed-code');
    embedCodeElement.select();
    document.execCommand('copy');
    alert('The embed code has been copied to the clipboard.');
});

span.onclick = function () {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}