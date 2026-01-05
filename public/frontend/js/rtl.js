// RTL Support JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Check if current language is RTL
    const isRTL = document.documentElement.dir === 'rtl' || 
                   document.body.dir === 'rtl' || 
                   document.documentElement.lang === 'ar' ||
                   document.documentElement.lang === 'he' ||
                   document.documentElement.lang === 'fa';
    
    if (isRTL) {
        // Apply RTL-specific transformations
        applyRTLTransformations();
        
        // Handle dropdown arrows for RTL
        handleRTLDropdowns();
        
        // Handle navigation menu for RTL
        handleRTLNavigation();
    }
});

function applyRTLTransformations() {
    // Rotate dropdown arrows for RTL
    const dropdownArrows = document.querySelectorAll('.fa-angle-down, .fa-chevron-down, .fa-caret-down');
    dropdownArrows.forEach(arrow => {
        arrow.style.transform = 'rotate(180deg)';
    });
    
    // Handle select dropdowns
    const selectElements = document.querySelectorAll('select');
    selectElements.forEach(select => {
        select.style.backgroundPosition = 'left 0.75rem center';
        select.style.paddingLeft = '2.25rem';
        select.style.paddingRight = '0.75rem';
    });
}

function handleRTLDropdowns() {
    // Handle Bootstrap dropdowns
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        const arrow = toggle.querySelector('.btn-arrow');
        if (arrow) {
            arrow.style.transform = 'rotate(180deg)';
        }
    });
    
    // Handle custom dropdowns
    const customDropdowns = document.querySelectorAll('.dropdown > a span');
    customDropdowns.forEach(span => {
        const icon = span.querySelector('i');
        if (icon && icon.classList.contains('fa-angle-down')) {
            icon.style.transform = 'rotate(180deg)';
        }
    });
}

function handleRTLNavigation() {
    // Handle navigation menu items
    const navLinks = document.querySelectorAll('.nav-links li');
    navLinks.forEach(li => {
        const link = li.querySelector('a');
        if (link && li.classList.contains('dropdown')) {
            // Ensure proper flex direction for dropdown items
            link.style.flexDirection = 'row-reverse';
            link.style.justifyContent = 'space-between';
        }
    });
    
    // Handle mobile navigation
    const mobileNavLinks = document.querySelectorAll('.m-nav .nav-links li');
    mobileNavLinks.forEach(li => {
        const link = li.querySelector('a');
        if (link && li.classList.contains('dropdown')) {
            link.style.flexDirection = 'row-reverse';
            link.style.justifyContent = 'space-between';
        }
    });
}

// Handle dynamic content (for AJAX-loaded content)
function handleDynamicRTLContent() {
    setTimeout(() => {
        applyRTLTransformations();
        handleRTLDropdowns();
        handleRTLNavigation();
    }, 100);
}

// Export for use in other scripts
window.handleDynamicRTLContent = handleDynamicRTLContent; 