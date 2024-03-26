window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  // Check if the user has scrolled down a little bit
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    // If scrolled down, show the button
    document.getElementById("scrollButton").classList.add("show");
  } else {
    // Otherwise, hide the button
    document.getElementById("scrollButton").classList.remove("show");
  }
}

function scrollToTop() {
  // Scroll to the top of the page when the button is clicked
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}


var isCooldown = false;

function toggleStylesheetAndImages() {
  if (!isCooldown) {
    // Toggle CSS stylesheet
    var cssLink = document.getElementById('cssLink');
    if (cssLink.getAttribute('href') == 'src/css/dark.css') {
      cssLink.setAttribute('href', 'src/css/light.css');
    } else {
      cssLink.setAttribute('href', 'src/css/dark.css');
    }
    
    // Toggle images
    var imageElements = document.querySelectorAll('.toggle-image');
    imageElements.forEach(function(imageElement) {
      var currentSrc = imageElement.getAttribute('src');
      if (currentSrc.includes('light')) {
        imageElement.setAttribute('src', currentSrc.replace('light', 'dark'));
      } else {
        imageElement.setAttribute('src', currentSrc.replace('dark', 'light'));
      }
    });

    // Apply cooldown
    isCooldown = true;
    setTimeout(function() {
      isCooldown = false;
    }, 300); // 1000 milliseconds cooldown
  }
}