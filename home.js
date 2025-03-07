function elementDepasseConteneur(element, conteneur) {
    return (
        element.scrollHeight > conteneur.clientHeight || 
        element.scrollWidth > conteneur.clientWidth
    );
}

// Exemple d'utilisation
const books = document.getElementsByClassName("book");

for (let i=0;i<books.length;i++) {
    if (elementDepasseConteneur(books[i].children[1], books[i])) {
        var watch_more = document.createElement('div');
        watch_more.innerHTML = "<a href='" + books[i].children[0].innerHTML + "' class='no-opened'>Voir plus</a>"
        watch_more.className = "watch-more"
        books[i].appendChild(watch_more);
    }
}