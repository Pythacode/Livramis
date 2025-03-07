document.getElementById("signin").addEventListener("submit", function(event) {
    // Récupération des valeurs
    let username = document.getElementById("username").value.trim();
    let firstName = document.getElementById("first-name").value.trim();
    let lastName = document.getElementById("last-name").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm-password").value;

    let is_empty = false;
    
    if (username === "") { 
        is_empty = true;
    } else if (firstName === "") { 
        is_empty = true;
    } else if (lastName === "") { 
        is_empty = true;
    } else if (email === "") { 
        is_empty = true;
    } else if (password === "") { 
        is_empty = true;
    } else if (confirmPassword === "") { 
        is_empty = true;
    }

    if (is_empty) {
        event.preventDefault();
        document.getElementById("error").textContent = "Aucun champs ne doit être vide"
        return
    }

    if (!(password === confirmPassword)) {
        event.preventDefault();
        document.getElementById("error").textContent = "Les mots de passe ne correspondent pas"
        return
    }

});