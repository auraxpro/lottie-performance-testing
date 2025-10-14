/**
 * Back to Index Navigation Script
 * Add this script to test case pages to enable easy navigation back to index
 */

// Create back button
function createBackButton() {
    const backButton = document.createElement('a');
    backButton.href = '../index.html';
    backButton.className = 'back-to-index';
    backButton.innerHTML = '← Back to Index';
    backButton.style.cssText = `
        position: fixed;
        top: 20px;
        left: 20px;
        background: rgba(255,255,255,0.95);
        color: #333;
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        z-index: 10000;
        font-size: 14px;
        border: 1px solid rgba(0,0,0,0.1);
    `;
    
    // Hover effects
    backButton.addEventListener('mouseenter', function() {
        this.style.background = 'white';
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
    });
    
    backButton.addEventListener('mouseleave', function() {
        this.style.background = 'rgba(255,255,255,0.95)';
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.15)';
    });
    
    document.body.appendChild(backButton);
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', createBackButton);
} else {
    createBackButton();
}

// Add keyboard shortcut (Escape key to go back)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.location.href = '../index.html';
    }
});

console.log('💡 Navigation: Press ESC key or click "← Back to Index" to return to main page');
