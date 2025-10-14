<?php
/**
 * WordPress Template Examples for Lottie Integration
 * 
 * Copy these examples into your theme templates to add optimized Lottie animations.
 * Make sure functions-lottie-optimized.php is included in your theme first.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}


/**
 * =======================
 * EXAMPLE 1: HERO SECTION
 * =======================
 */
?>

<!-- Hero Section with Lottie Animation -->
<section class="hero-section" style="position: relative; min-height: 600px; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Welcome to Our Amazing Product</h1>
                <p>Transform your workflow with cutting-edge technology.</p>
                <a href="#" class="btn btn-primary">Get Started</a>
            </div>
            <div class="col-md-6">
                <?php 
                // Hero animation - loads immediately (above the fold)
                echo talty_get_lottie('/wp-content/uploads/animations/hero.json', array(
                    'width' => '100%',
                    'height' => '500px',
                    'immediate' => true, // Critical: loads immediately
                    'renderer' => 'svg',
                    'class' => 'hero-animation'
                ));
                ?>
            </div>
        </div>
    </div>
</section>


<?php
/**
 * =======================
 * EXAMPLE 2: FEATURES SECTION
 * =======================
 */
?>

<!-- Features Section with Lazy-Loaded Animations -->
<section class="features-section" style="padding: 80px 0;">
    <div class="container">
        <h2 class="text-center mb-5">Our Features</h2>
        <div class="row">
            
            <!-- Feature 1 -->
            <div class="col-md-4 mb-4">
                <?php 
                // Feature animation - lazy loads when scrolled into view
                echo talty_get_lottie('/wp-content/uploads/animations/feature-1.json', array(
                    'width' => '200px',
                    'height' => '200px',
                    'immediate' => false, // Lazy load
                    'class' => 'feature-icon mx-auto'
                ));
                ?>
                <h3 class="text-center mt-3">Fast Performance</h3>
                <p class="text-center">Lightning-fast load times with optimized delivery.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-md-4 mb-4">
                <?php 
                echo talty_get_lottie('/wp-content/uploads/animations/feature-2.json', array(
                    'width' => '200px',
                    'height' => '200px',
                    'immediate' => false,
                    'class' => 'feature-icon mx-auto'
                ));
                ?>
                <h3 class="text-center mt-3">Easy to Use</h3>
                <p class="text-center">Intuitive interface designed for everyone.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-md-4 mb-4">
                <?php 
                echo talty_get_lottie('/wp-content/uploads/animations/feature-3.json', array(
                    'width' => '200px',
                    'height' => '200px',
                    'immediate' => false,
                    'renderer' => 'canvas', // Use canvas for complex animations
                    'class' => 'feature-icon mx-auto'
                ));
                ?>
                <h3 class="text-center mt-3">Secure & Reliable</h3>
                <p class="text-center">Enterprise-grade security and 99.9% uptime.</p>
            </div>
            
        </div>
    </div>
</section>


<?php
/**
 * =======================
 * EXAMPLE 3: SHORTCODE IN CONTENT
 * =======================
 */

/**
 * In WordPress editor, use these shortcodes:
 * 
 * Basic usage:
 * [lottie src="/wp-content/uploads/animations/my-animation.json"]
 * 
 * With custom dimensions:
 * [lottie src="/wp-content/uploads/animations/my-animation.json" width="400px" height="400px"]
 * 
 * Above the fold (loads immediately):
 * [lottie src="/wp-content/uploads/animations/hero.json" immediate="true"]
 * 
 * Canvas renderer (better for complex animations):
 * [lottie src="/wp-content/uploads/animations/complex.json" renderer="canvas"]
 * 
 * No loop:
 * [lottie src="/wp-content/uploads/animations/one-time.json" loop="false"]
 */


/**
 * =======================
 * EXAMPLE 4: ACF INTEGRATION
 * =======================
 */

// If using Advanced Custom Fields (ACF), create a field group with:
// - Field Name: lottie_animation_file (File Upload, return URL)
// - Field Name: lottie_animation_width (Text)
// - Field Name: lottie_animation_height (Text)

?>

<section class="custom-animation-section">
    <?php if (function_exists('get_field')): ?>
        <?php 
        $animation_url = get_field('lottie_animation_file');
        $animation_width = get_field('lottie_animation_width') ?: '400px';
        $animation_height = get_field('lottie_animation_height') ?: '400px';
        
        if ($animation_url):
        ?>
            <div class="container">
                <?php 
                echo talty_get_lottie($animation_url, array(
                    'width' => $animation_width,
                    'height' => $animation_height,
                    'immediate' => false,
                    'class' => 'acf-lottie-animation'
                ));
                ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>


<?php
/**
 * =======================
 * EXAMPLE 5: ELEMENTOR INTEGRATION
 * =======================
 */

/**
 * For Elementor page builder:
 * 1. Add a "HTML" widget
 * 2. Paste this code:
 */
?>

<!-- Elementor HTML Widget Content -->
<div class="elementor-lottie-wrapper" style="max-width: 500px; margin: 0 auto;">
    <div class="lottie-animation" 
         data-lottie-src="/wp-content/uploads/animations/elementor-animation.json"
         data-lottie-loop="true"
         data-lottie-autoplay="true"
         data-lottie-renderer="svg"
         style="width: 100%; height: 400px;">
    </div>
</div>


<?php
/**
 * =======================
 * EXAMPLE 6: LOADING STATE
 * =======================
 */
?>

<!-- Button with Lottie Loading Animation -->
<div class="loading-button-wrapper">
    <button id="submit-button" class="btn btn-primary" onclick="submitForm()">
        <span class="button-text">Submit Form</span>
        <div id="loading-animation" 
             class="lottie-animation" 
             data-lottie-src="/wp-content/uploads/animations/loading-spinner.json"
             data-lottie-loop="true"
             data-lottie-renderer="svg"
             style="width: 30px; height: 30px; display: none; margin-left: 10px;">
        </div>
    </button>
</div>

<script>
function submitForm() {
    const button = document.getElementById('submit-button');
    const buttonText = button.querySelector('.button-text');
    const loadingAnim = document.getElementById('loading-animation');
    
    // Show loading animation
    buttonText.textContent = 'Submitting...';
    loadingAnim.style.display = 'inline-block';
    button.disabled = true;
    
    // Initialize Lottie if not already loaded
    if (typeof lottie !== 'undefined' && !loadingAnim.hasAttribute('data-loaded')) {
        lottie.loadAnimation({
            container: loadingAnim,
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: loadingAnim.dataset.lottieSrc
        });
        loadingAnim.setAttribute('data-loaded', 'true');
    }
    
    // Simulate form submission
    setTimeout(() => {
        buttonText.textContent = 'Success!';
        loadingAnim.style.display = 'none';
        button.disabled = false;
    }, 2000);
}
</script>

<style>
.loading-button-wrapper .btn {
    display: inline-flex;
    align-items: center;
}
</style>


<?php
/**
 * =======================
 * EXAMPLE 7: CONDITIONAL LOADING BY PAGE
 * =======================
 * Add this to functions.php
 */

/**
 * Only load Lottie on specific pages
 */
function talty_conditional_lottie_pages($has_lottie) {
    // Add page IDs or slugs that use Lottie
    $lottie_pages = array(
        'home',
        'about',
        'services',
        'contact'
    );
    
    // Check if current page is in the list
    if (is_page($lottie_pages)) {
        return true;
    }
    
    // Check specific post IDs
    $lottie_post_ids = array(123, 456, 789);
    if (is_single($lottie_post_ids)) {
        return true;
    }
    
    // Check categories
    if (is_category('animations')) {
        return true;
    }
    
    return $has_lottie;
}
// add_filter('talty_has_lottie', 'talty_conditional_lottie_pages');


/**
 * =======================
 * EXAMPLE 8: CUSTOM POST TYPE INTEGRATION
 * =======================
 */

/**
 * Add Lottie meta box to custom post type
 */
function talty_add_lottie_meta_box() {
    add_meta_box(
        'talty_lottie_meta',
        'Lottie Animation',
        'talty_lottie_meta_box_callback',
        'portfolio', // Your custom post type
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'talty_add_lottie_meta_box');

function talty_lottie_meta_box_callback($post) {
    wp_nonce_field('talty_lottie_meta_box', 'talty_lottie_nonce');
    $value = get_post_meta($post->ID, '_lottie_animation_url', true);
    ?>
    <label for="lottie_animation_url">Animation JSON URL:</label>
    <input type="text" 
           id="lottie_animation_url" 
           name="lottie_animation_url" 
           value="<?php echo esc_attr($value); ?>" 
           style="width: 100%;" 
           placeholder="/wp-content/uploads/animations/portfolio-1.json">
    <p class="description">Enter the URL to your Lottie JSON file.</p>
    <?php
}

function talty_save_lottie_meta_box($post_id) {
    if (!isset($_POST['talty_lottie_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['talty_lottie_nonce'], 'talty_lottie_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['lottie_animation_url'])) {
        update_post_meta($post_id, '_lottie_animation_url', sanitize_text_field($_POST['lottie_animation_url']));
    }
}
add_action('save_post', 'talty_save_lottie_meta_box');

// Display in single portfolio template:
?>

<div class="portfolio-animation">
    <?php 
    $animation_url = get_post_meta(get_the_ID(), '_lottie_animation_url', true);
    if ($animation_url) {
        echo talty_get_lottie($animation_url, array(
            'width' => '100%',
            'height' => '500px',
            'immediate' => is_singular('portfolio'), // Load immediately on single portfolio pages
        ));
    }
    ?>
</div>


<?php
/**
 * =======================
 * EXAMPLE 9: WOOCOMMERCE INTEGRATION
 * =======================
 */

/**
 * Add Lottie animation to product pages
 */
function talty_woocommerce_product_lottie() {
    global $product;
    
    // Get product meta
    $animation_url = get_post_meta($product->get_id(), '_product_lottie_animation', true);
    
    if ($animation_url) {
        echo '<div class="product-lottie-animation">';
        echo talty_get_lottie($animation_url, array(
            'width' => '100%',
            'height' => '400px',
            'immediate' => true,
            'class' => 'woo-product-animation'
        ));
        echo '</div>';
    }
}
// add_action('woocommerce_before_single_product_summary', 'talty_woocommerce_product_lottie', 15);


/**
 * =======================
 * EXAMPLE 10: RESPONSIVE SIZING
 * =======================
 */
?>

<!-- Responsive Lottie Animation -->
<style>
.responsive-lottie-wrapper {
    position: relative;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

.responsive-lottie-wrapper .lottie-animation {
    width: 100%;
    height: auto;
    aspect-ratio: 1 / 1; /* Maintain square aspect ratio */
}

@media (max-width: 768px) {
    .responsive-lottie-wrapper {
        max-width: 400px;
    }
}

@media (max-width: 480px) {
    .responsive-lottie-wrapper {
        max-width: 300px;
    }
}
</style>

<div class="responsive-lottie-wrapper">
    <?php 
    echo talty_get_lottie('/wp-content/uploads/animations/responsive.json', array(
        'width' => '100%',
        'height' => 'auto',
        'class' => 'lottie-animation'
    ));
    ?>
</div>

