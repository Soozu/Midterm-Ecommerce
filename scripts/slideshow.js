var slideIndex = 1;
showSlides(slideIndex);

// Preload images
document.querySelectorAll(".slide img").forEach(img => {
    const src = img.getAttribute('src');
    if (src) {
        const image = new Image();
        image.src = src;
    }
});

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

// Keyboard controls
document.addEventListener('keydown', function(event) {
  if (event.key === 'ArrowLeft') {
    plusSlides(-1);
  } else if (event.key === 'ArrowRight') {
    plusSlides(1);
  }
});

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("slide");
  if (n > slides.length) {slideIndex = 1;}    
  if (n < 1) {slideIndex = slides.length;}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  slides[slideIndex-1].style.display = "block";  
}
