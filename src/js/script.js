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

