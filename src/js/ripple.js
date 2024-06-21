$(document).ready(function () {
    // Initialize ripple effect state
    var isRippleEnabled = localStorage.getItem('rippleState') === 'true';

    // Function to initialize or destroy ripple effect
    function initializeRipple() {
        if (isRippleEnabled) {
            $('.ripple').ripples({
                resolution: 256,
                dropRadius: 10,
                perturbance: 0.02
            });
        } else {
            $('.ripple').ripples('destroy');
        }
    }

    // Call initializeRipple on document ready
    initializeRipple();

    // Resize event handler
    $(window).on('resize', function () {
        if (isRippleEnabled) {
            $('.ripple').ripples('updateSize');
        }
    });

    // Toggle button event handler
    $('#Ripplebutton').on('click', function () {
        toggleRippleEffect();
    });

    // Function to toggle ripple effect
    function toggleRippleEffect() {
        if (isRippleEnabled) {
            $('.ripple').ripples('destroy');
        } else {
            $('.ripple').ripples({
                resolution: 256,
                dropRadius: 10,
                perturbance: 0.02
            });
        }
        isRippleEnabled = !isRippleEnabled;
        localStorage.setItem('rippleState', isRippleEnabled);
    }

    // Handle visibility change
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            // Reinitialize ripple effect based on localStorage state when the document becomes visible
            isRippleEnabled = localStorage.getItem('rippleState') === 'true';
            initializeRipple();
        }
    });

    // Function to create random ripple
    function createRandomRipple() {
        if (isRippleEnabled) {
            var $element = $('.ripple');
            var x = Math.random() * $element.width();
            var y = Math.random() * $element.height();

            $element.ripples('drop', x, y, 10, 0.05);
        }
    }
});