// ===========================
// admin.js - centralisé
// ===========================
document.addEventListener('DOMContentLoaded', () => {
    console.log("Admin JS chargé ✅");

    // ===========================
    // Gestion clics sur les cartes (Dashboard)
    // ===========================
    const cards = document.querySelectorAll('.card a');
    cards.forEach(card => {
        card.addEventListener('click', (e) => {
            console.log(`Accès à : ${card.textContent}`);
        });
    });

    // ===========================
    // Confirmation suppression (Actualités + Documents)
    // ===========================
    const deleteLinks = document.querySelectorAll('.delete-link, .documents-table a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            if(!confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
                e.preventDefault();
            }
        });
    });

    // ===========================
    // Prévisualisation image avant upload (Actualités)
    // ===========================
    const fileInput = document.querySelector('input[type="file"]');
    if(fileInput){
        const preview = document.createElement('img');
        preview.style.maxWidth = '120px';
        preview.style.marginBottom = '10px';
        fileInput.parentNode.insertBefore(preview, fileInput.nextSibling);

        fileInput.addEventListener('change', function(){
            const file = this.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = function(e){
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        });
    }

    // ===========================
    // Toggle champs documents (Cours/TD/Eval/Norme)
    // ===========================
    const typeSelect = document.getElementById('type');
    if(typeSelect){
        const blocMatiere = document.getElementById('bloc-matiere');
        const blocNorme = document.getElementById('bloc-norme');

        const toggleFields = () => {
            const val = typeSelect.value;
            blocMatiere.style.display = ["Cours","TD","Interro","Devoir"].includes(val) ? "block" : "none";
            blocNorme.style.display = val === "Normes" ? "block" : "none";
        };

        typeSelect.addEventListener('change', toggleFields);
        toggleFields(); // état initial
    }

});


// ===== Admin Annonces =====
document.addEventListener('DOMContentLoaded', function() {
    const deleteLinks = document.querySelectorAll('.documents-table a');

    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e){
            if(!confirm("Supprimer cette annonce ?")) {
                e.preventDefault();
            }
        });
    });
});


function toggleFields(){
    const table = document.body.dataset.table;

    const matiere = document.getElementById('bloc-matiere');
    const norme = document.getElementById('bloc-norme');

    if(!matiere || !norme) return;

    if(["cours","td","evaluations"].includes(table)){
        matiere.style.display = "block";
    } else {
        matiere.style.display = "none";
    }

    norme.style.display = (table === "normes") ? "block" : "none";
}

window.addEventListener("DOMContentLoaded", () => {
    toggleFields();
});
// Confirmation avant suppression
document.addEventListener('DOMContentLoaded', function() {
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if(!confirm('Supprimer cette annonce ?')) {
                e.preventDefault();
            }
        });
    });
});


function toggleFields() {
    const type = document.getElementById('type').value;
    const blocMatiere = document.getElementById('bloc-matiere');
    const blocNorme = document.getElementById('bloc-norme');

    const showMatiere = ['Cours','TD','Interro','Devoir'].includes(type);

    blocMatiere.style.display = showMatiere ? 'flex' : 'none';
    blocNorme.style.display = (type === 'Normes') ? 'block' : 'none';
}

// preview image
const fileInput = document.getElementById('file-input');
const preview = document.getElementById('file-preview');

if(fileInput){
    fileInput.addEventListener('change', function(){
        const file = this.files[0];

        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
}