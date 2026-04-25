
// SLIDESHOW
let slideIndex = 0;
showSlides();

// Next/previous controls
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides(slideIndex = n - 1); // Adjust index for 0-based array
}

function showSlides() {
    let i;
    let slides = document.getElementsByClassName("custom-mySlides");
    let dots = document.getElementsByClassName("custom-dot");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}    
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" custom-active", "");
    }
    slides[slideIndex-1].style.display = "block";  
    dots[slideIndex-1].className += " custom-active";
    setTimeout(showSlides, 3000); // Change image every 3 seconds
}
