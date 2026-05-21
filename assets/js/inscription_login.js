const identifiantInput = document.getElementById("identifiant");
const nomInput = document.getElementById("nom");
const prenomInput = document.getElementById("prenom");
const categorieInput = document.getElementById("categorie");
const form = document.getElementById("registerForm");

function remplirInfos(id){
    fetch("get_user_info.php?id=" + encodeURIComponent(id))
    .then(res => res.json())
    .then(data => {
        if(data.success){
            nomInput.value = data.nom;
            prenomInput.value = data.prenom;
            categorieInput.value = data.categorie;
        } else {
            nomInput.value = "";
            prenomInput.value = "";
            categorieInput.value = "";
            alert("Identifiant invalide");
        }
    });
}

identifiantInput.addEventListener("blur", () => {
    const id = identifiantInput.value.trim();
    if(id) remplirInfos(id);
});

window.addEventListener("DOMContentLoaded", () => {
    const id = identifiantInput.value.trim();
    if(id) remplirInfos(id);
});

form.addEventListener("submit", function(e){
    if(!nomInput.value){
        e.preventDefault();
        alert("Veuillez entrer un identifiant valide.");
    }
});


// assets/js/mot_identifiant.js

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.querySelector(".request-button");

    btn.addEventListener("click", () => {
        console.log("Redirection vers WhatsApp...");
    });
});