

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Full Screen Slider</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto');

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Roboto', sans-serif;
  background: #333;
  color: #fff;
  line-height: 1.6;
}

.slider {
  position: relative;
  overflow: hidden;
  height: 100vh;
  width: 100vw;
}

.slide {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: opacity 0.4s ease-in-out;
}

.slide.current {
  opacity: 1;
}

.slide .content {
  position: absolute;
  bottom: 70px;
  left: -600px;
  opacity: 0;
  width: 600px;
  background-color: rgba(255, 255, 255, 0.8);
  color: #333;
  padding: 35px;
}

.slide .content h1 {
  margin-bottom: 10px;
}

.slide.current .content {
  opacity: 1;
  transform: translateX(600px);
  transition: all 0.7s ease-in-out 0.3s;
}

.buttons button#next {
  position: absolute;
  top: 40%;
  right: 15px;
}

.buttons button#prev {
  position: absolute;
  top: 40%;
  left: 15px;
}

.buttons button {
  border: 2px solid #fff;
  background-color: transparent;
  color: #fff;
  cursor: pointer;
  padding: 13px 15px;
  border-radius: 50%;
  outline: none;
}

.buttons button:hover {
  background-color: #fff;
  color: #333;
}

@media (max-width: 500px) {
  .slide .content {
    bottom: -300px;
    left: 0;
    width: 100%;
  }

  .slide.current .content {
    transform: translateY(-300px);
  }
}



/* Backgorund Images */
 
.slide:first-child {
  background: url('https://source.unsplash.com/RyRpq9SUwAU/1600x900') no-repeat
    center top/cover;
}
.slide:nth-child(2) {
  background: url('https://source.unsplash.com/BeOW_PJjA0w/1600x900') no-repeat
    center top/cover;
}
.slide:nth-child(3) {
  background: url('https://source.unsplash.com/TMOeGZw9NY4/1600x900') no-repeat
    center top/cover;
}
.slide:nth-child(4) {
  background: url('https://source.unsplash.com/yXpA_eCbtzI/1600x900') no-repeat
    center top/cover;
}
.slide:nth-child(5) {
  background: url('https://source.unsplash.com/ULmaQh9Gvbg/1600x900') no-repeat
    center top/cover;
} 
.slide:nth-child(6)  {
  background: url('https://source.unsplash.com/ggZuL3BTSJU/1600x900') no-repeat
    center center/cover;
}
    </style>
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
      integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>

    <div class="slider">
      <div class="slide current">
        <div class="content">
          <h1>Slide One</h1>
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit hic
            maxime, voluptatibus labore doloremque vero!
          </p>
        </div>
      </div>
      <div class="slide">
        <div class="content">
          <h1>Slide Two</h1>
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit hic
            maxime, voluptatibus labore doloremque vero!
          </p>
        </div>
      </div>
      <div class="slide">
        <div class="content">
          <h1>Slide Three</h1>
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit hic
            maxime, voluptatibus labore doloremque vero!
          </p>
        </div>
      </div>
      <div class="slide">
        <div class="content">
          <h1>Slide Four</h1>
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sit hic
            maxime, voluptatibus labore doloremque vero!
          </p>
        </div>
      </div>
      
    </div>
    <div class="buttons">
      <button id="prev"><i class="fas fa-arrow-left"></i></button>
      <button id="next"><i class="fas fa-arrow-right"></i></button>
    </div>

    <script>
        const slides = document.querySelectorAll('.slide');
const next = document.querySelector('#next');
const prev = document.querySelector('#prev');
const auto = false; // Auto scroll
const intervalTime = 5000;
let slideInterval;

const nextSlide = () => {
  // Get current class
  const current = document.querySelector('.current');
  // Remove current class
  current.classList.remove('current');
  // Check for next slide
  if (current.nextElementSibling) {
    // Add current to next sibling
    current.nextElementSibling.classList.add('current');
  } else {
    // Add current to start
    slides[0].classList.add('current');
  }
  setTimeout(() => current.classList.remove('current'));
};

const prevSlide = () => {
  // Get current class
  const current = document.querySelector('.current');
  // Remove current class
  current.classList.remove('current');
  // Check for prev slide
  if (current.previousElementSibling) {
    // Add current to prev sibling
    current.previousElementSibling.classList.add('current');
  } else {
    // Add current to last
    slides[slides.length - 1].classList.add('current');
  }
  setTimeout(() => current.classList.remove('current'));
};

// Button events
next.addEventListener('click', e => {
    console.log('clicked');
  nextSlide();
  if (auto) {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, intervalTime);
  }
});

prev.addEventListener('click', e => {
  prevSlide();
  if (auto) {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, intervalTime);
  }
});

// Auto slide
if (auto) {
  // Run next slide at interval time
  slideInterval = setInterval(nextSlide, intervalTime);
}

    </script>
    
  </body>
</html>


