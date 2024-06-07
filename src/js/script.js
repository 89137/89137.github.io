window.onload = function() {
  // Load theme from localStorage if available
  var savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    var cssLink = document.getElementById('cssLink');
    cssLink.setAttribute('href', savedTheme);

    // Set the correct images
    toggleImages(savedTheme.includes('dark') ? 'dark' : 'light');
  }
}

window.onscroll = function () { scrollFunction() };

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
    var newTheme = (cssLink.getAttribute('href') == 'src/css/dark.css') ? 'src/css/light.css' : 'src/css/dark.css';
    cssLink.setAttribute('href', newTheme);

    // Save the theme to localStorage
    localStorage.setItem('theme', newTheme);

    // Toggle images
    toggleImages(newTheme.includes('dark') ? 'dark' : 'light');

    // Apply cooldown
    isCooldown = true;
    setTimeout(function () {
      isCooldown = false;
    }, 300); // 300 milliseconds cooldown
  }
}

function toggleImages(theme) {
  var imageElements = document.querySelectorAll('.toggle-image');
  imageElements.forEach(function (imageElement) {
    var currentSrc = imageElement.getAttribute('src');
    if (theme === 'dark') {
      imageElement.setAttribute('src', currentSrc.replace('light', 'dark'));
    } else {
      imageElement.setAttribute('src', currentSrc.replace('dark', 'light'));
    }
  });
}


function openGameWindow() {
  const width = 900;
  const height = 620;
  const left = (screen.width - width) / 2;
  const top = (screen.height - height) / 2;
  const windowFeatures = `width=${width},height=${height},left=${left},top=${top},resizable,scrollbars=yes,status=1`;

  const gameWindow = window.open('', 'GameWindow', windowFeatures);

  const iframeHTML = `<iframe frameborder="0" src="https://itch.io/embed-upload/9869828?color=5d82c6" allowfullscreen="" width="900" height="620">
                          <a href="https://belljahh.itch.io/me-fish-no-crash">Play Me fish no crash on itch.io</a>
                      </iframe>`;

  gameWindow.document.write(iframeHTML);
  gameWindow.document.title = "Me Fish No Crash";
}