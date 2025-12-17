<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileOverlay = document.getElementById('mobileSidebarOverlay');
    
    if (mobileToggle && mobileSidebar && mobileOverlay) {
        mobileToggle.addEventListener('click', function() {
            const isOpen = mobileSidebar.classList.contains('translate-x-0');
            
            if (isOpen) {
                mobileSidebar.classList.remove('translate-x-0');
                mobileSidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.remove('opacity-100', 'visible');
                mobileOverlay.classList.add('opacity-0', 'invisible');
            } else {
                mobileSidebar.classList.remove('-translate-x-full');
                mobileSidebar.classList.add('translate-x-0');
                mobileOverlay.classList.remove('opacity-0', 'invisible');
                mobileOverlay.classList.add('opacity-100', 'visible');
            }
        });
        
        mobileOverlay.addEventListener('click', function() {
            mobileSidebar.classList.remove('translate-x-0');
            mobileSidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.remove('opacity-100', 'visible');
            mobileOverlay.classList.add('opacity-0', 'invisible');
        });
    }
});

