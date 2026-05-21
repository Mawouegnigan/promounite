/* ===== Message de bienvenue lettre par lettre ===== */
const message = document.getElementById('welcome-message');
if(message){
    const text = message.textContent;
    message.textContent = '';
    let i = 0;
    function typeWriter() {
        if (i < text.length) {
            message.textContent += text.charAt(i);
            i++;
            setTimeout(typeWriter, 80);
        }
    }
    typeWriter();
}

/* ===== Bande défilante des annonces ===== */
const annoncesWrapper = document.querySelector('.annonces-wrapper');
let annoncesPaused = false;
if(annoncesWrapper){
    let scrollAnn = 0;
    function scrollAnnonces() {
        if(!annoncesPaused){
            scrollAnn += 1;
            if(scrollAnn >= annoncesWrapper.scrollWidth) scrollAnn = -annoncesWrapper.clientWidth;
            annoncesWrapper.style.transform = `translateX(${-scrollAnn}px)`;
        }
        requestAnimationFrame(scrollAnnonces);
    }
    scrollAnnonces();

    annoncesWrapper.addEventListener('click', () => {
        annoncesPaused = !annoncesPaused;
    });
}

/* ===== Carrousel centré ===== */
const carouselSlide = document.getElementById('carouselSlide');
const slides = document.querySelectorAll('.carousel-item');
const prevBtn = document.querySelector('.carousel-btn.prev');
const nextBtn = document.querySelector('.carousel-btn.next');
let currentIndex = 0;
let slidePaused = false;

function showSlide(index){
    carouselSlide.style.transform = `translateX(${-index * 100}%)`;
}

// Suivant / Précédent
nextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
    slidePaused = true;
});
prevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
    slidePaused = true;
});

// Auto-slide toutes les 5 secondes
setInterval(() => {
    if(!slidePaused){
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }
}, 5000);

// Clic sur image pour agrandir
slides.forEach(slide => {
    const img = slide.querySelector('img');
    if(img){
        img.addEventListener('click', () => {
            slidePaused = !slidePaused;
            if(slidePaused) window.open(img.src, '_blank');
        });
    }
});

/* ===== Boîte de commentaire ===== */
const commentForm = document.getElementById('commentForm');
const commentList = document.getElementById('commentList');

if(commentForm){
    commentForm.addEventListener('submit', e => {
        e.preventDefault();
        const comment = document.getElementById('commentaire').value.trim();
        if(comment){
            const li = document.createElement('li');
            li.textContent = comment;
            commentList.appendChild(li);
            commentForm.reset();
        }
    });
}



// ===== ANIMATION NOM =====
const username = document.getElementById("username");

if(username){
    const text = username.innerText;
    username.innerText = "";
    let i = 0;

    function type(){
        if(i < text.length){
            username.innerText += text[i++];
            setTimeout(type, 80);
        }
    }
    type();
}

// ===== MENU HAMBURGER =====
const toggle = document.getElementById('menuToggle');
const navbar = document.querySelector('.navbar');

if(toggle){
    toggle.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });
}

// ===== DROPDOWN MOBILE =====
document.querySelectorAll('.dropdown > a').forEach(menu => {
    menu.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
            e.preventDefault();
            this.parentElement.classList.toggle('active');
        }
    });
});