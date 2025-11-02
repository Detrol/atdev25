// Form validation and interaction enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('[x-data]');
    
    // Custom smooth scroll function with easing
    function smoothScrollTo(targetPosition, duration = 800) {
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        let startTime = null;

        // Easing function (easeInOutCubic)
        function easeInOutCubic(t) {
            return t < 0.5
                ? 4 * t * t * t
                : 1 - Math.pow(-2 * t + 2, 3) / 2;
        }

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1);
            const ease = easeInOutCubic(progress);

            window.scrollTo(0, startPosition + (distance * ease));

            if (timeElapsed < duration) {
                requestAnimationFrame(animation);
            }
        }

        requestAnimationFrame(animation);
    }

    // Smooth scroll to anchors without hash in URL
    // Handles both #section and /#section links
    document.querySelectorAll('a[href^="#"], a[href^="/#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');

            // Skip empty hash
            if (href === '#' || href === '/#') return;

            // Extract hash from href (handles both #section and /#section)
            const hash = href.includes('#') ? href.substring(href.indexOf('#')) : null;
            if (!hash) return;

            // Check if we're navigating to current page
            const targetPath = href.startsWith('/#') ? '/' : window.location.pathname;
            const isCurrentPage = window.location.pathname === targetPath;

            // Only smooth scroll if we're on the same page
            if (isCurrentPage) {
                e.preventDefault();
                const target = document.querySelector(hash);
                if (target) {
                    // Use offsetTop for more reliable positioning
                    // (getBoundingClientRect can be affected by animations/intersect)
                    const targetPosition = target.offsetTop;

                    // Use custom smooth scroll
                    smoothScrollTo(targetPosition, 800);

                    // Remove hash from URL without adding to browser history
                    setTimeout(() => {
                        history.replaceState(null, '', window.location.pathname);
                    }, 10);
                }
            }
            // If different page, let browser navigate normally (will load page then scroll to hash)
        });
    });

    // Handle hash on page load (when navigating from another page with /#section)
    window.addEventListener('load', function() {
        if (window.location.hash) {
            const hash = window.location.hash;
            const target = document.querySelector(hash);
            if (target) {
                // Small delay to ensure page is fully rendered
                setTimeout(() => {
                    const targetPosition = target.offsetTop;
                    smoothScrollTo(targetPosition, 800);

                    // Remove hash from URL
                    setTimeout(() => {
                        history.replaceState(null, '', window.location.pathname);
                    }, 10);
                }, 100);
            }
        }
    });

    // Auto-generate slug from title (for admin project forms)
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput && !slugInput.value) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.dataset.manuallyEdited) {
                const slug = this.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '') // Remove diacritics
                    .replace(/å/g, 'a')
                    .replace(/ä/g, 'a')
                    .replace(/ö/g, 'o')
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            }
        });
        
        slugInput.addEventListener('input', function() {
            this.dataset.manuallyEdited = 'true';
        });
    }

    // Confirm before leaving form with unsaved changes
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        let formChanged = false;
        
        form.addEventListener('input', function() {
            formChanged = true;
        });
        
        form.addEventListener('submit', function() {
            formChanged = false;
        });
        
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    });
});