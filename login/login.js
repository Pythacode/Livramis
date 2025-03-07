document.getElementById("login").addEventListener("submit", function(event) {
    // Récupération des valeurs
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value;

    let is_empty = false;
    
    if (username === "") { 
        is_empty = true;
    } else if (password === "") { 
        is_empty = true;
    }

    if (is_empty) {
        event.preventDefault();
        document.getElementById("error").textContent = "Aucun champs ne doit être vide"
        return
    }

});