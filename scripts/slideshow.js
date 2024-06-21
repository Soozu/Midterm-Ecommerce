let slideIndex = 0;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    let slides = document.querySelectorAll('.slide');
    if (n >= slides.length) { slideIndex = 0 }
    if (n < 0) { slideIndex = slides.length - 1 }
    slides.forEach((slide, index) => {
        slide.style.display = 'none';
        slide.classList.remove('active');
    });
    slides[slideIndex].style.display = 'block';
    slides[slideIndex].classList.add('active');
}

// Automatic slideshow
setInterval(() => {
    plusSlides(1);
}, 3000); // Change slide every 3 seconds