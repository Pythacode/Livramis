// Fonction pour ouvrir la fenêtre modale
function openModal(modal) {
    var modal = document.getElementById("modal-fenetre-" + modal);
    var overlay = document.getElementById("modal-overlay");
    modal.style.display = "flex";
    overlay.style.display = "block";
}

// Fonction pour fermer la fenêtre modale
function closeModal(modal) {
    var modal = document.getElementById("modal-fenetre-" + modal);
    var overlay = document.getElementById("modal-overlay");
    modal.style.display = "none";
    overlay.style.display = "none";
}

function reserve() {
    openModal("reserve")
}

function remove() {
    openModal("remove")
}

function remove_confirmed() {

    let sub_text = document.getElementById('sub-text');

    let confirm_button = document.getElementById('button-validate-delete');
    confirm_button.style.display = "none"

    let text = document.getElementById('text');
    text.innerText = "Supression en cours..."

    let load_img = document.getElementById('loading-delete');
    load_img.style.display = "inherit"

    let xhr = new XMLHttpRequest();

    xhr.open("DELETE", window.location.href);

    xhr.responseType = "json";

    xhr.send();

    xhr.onload = function(){
        text.innerText = xhr.status
        sub_text.innerText = JSON.stringify(xhr.statusText)
        sub_text.style.display = "inherit"
    };

    let home_button = document.getElementById('home-button');
    load_img.style.display = "none"
    home_button.style.display = "inherit"
    
} 