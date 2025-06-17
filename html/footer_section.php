<?php
// Set default footer color if not defined, matching page heading color
if (!isset($footer_color)) {
    $footer_color = isset($page_heading_color) ? $page_heading_color : '#6f42c1'; // Match with heading or fallback
}
?>
                </div> <!-- Close container-xxl -->
            </div> <!-- Close content-wrapper -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme text-white" style="background-color: <?php echo $footer_color; ?>;">
                <div class="container-xxl">
                    <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                        <div class="text-body mb-2 mb-md-0 order-2 order-md-1">
                            <span class="text-white">©</span>
                            <script>document.write(new Date().getFullYear());</script>
                            <span class="text-white"> - All rights reserved</span>
                        </div>
                        <div class="d-flex align-items-center order-1 order-md-2 mb-2 mb-md-0">
                            <a href="https://sbkdigi.in/" class="footer-link text-white" target="_blank" rel="noopener">
                                <span class="text-white">Powered by</span> SBK Details
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
        </div> <!-- Close layout-page -->
    </div> <!-- Close layout-container -->

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div> <!-- Close layout-wrapper -->

<!-- Core JS -->
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>

<!-- Main JS -->
<script src="../assets/js/main.js"></script>

<!-- Page-specific JS -->
<?php if (isset($additional_js)) echo $additional_js; ?>

<!-- GitHub buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Custom page scripts -->
<?php if (isset($page_scripts)) echo $page_scripts; ?>
</body>
</html>
