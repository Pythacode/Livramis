document.getElementById("image-container").addEventListener("click", function () {
    document.getElementById("image").click(); // Ouvre l'explorateur de fichiers
});

document.getElementById("image").addEventListener("change", function (event) {
    const file = event.target.files[0]; // Récupère le fichier sélectionné
    if (file) {
        let fileSize = file.size / 1024; // Taille en Ko

        if (fileSize > 100) {
            alert("Le fichier est trop grand ! (Max : 100 Ko)");
            this.value = "";
            return;
        }
        // Affiche le nom du fichier
        document.getElementById("image-name").textContent = file.name;

        // Affiche l'image en arrière-plan du div
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("image-container").style.backgroundImage = `url(${e.target.result})`;
        };
        reader.readAsDataURL(file);
    }
});

const quill = new Quill('#resume', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'font': [] }],
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote'],
            [{ 'align': [] }],
            [{ 'color': [] }, { 'background': [] }],
            ['link'],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'direction': 'rtl' }],
            ['clean'] // Pour réinitialiser
        ]
    }
});

document.getElementById("add_book").addEventListener("submit", function(event) {
    // Récupération des valeurs
    let title = document.getElementById("title").value.trim();
    let author = document.getElementById("author").value.trim();
    var resume = quill.root.innerHTML;

    let is_empty = false;
    
    if (title === "") { 
        is_empty = true;
    } else if (author === "") { 
        is_empty = true;
    }

    if (is_empty) {
        event.preventDefault();
        document.getElementById("error").textContent = "Les champs avec \"*\" doivent être remplis"
        return
    }

    document.getElementById("resume-input").value = resume

});