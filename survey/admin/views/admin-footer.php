<script>
    lucide.createIcons();


    function toggleCollapse(header) {
        const card = header.parentElement;
        card.classList.toggle('collapsed');
    }

    // Super admin check
    const isSuperAdmin = true; // false to hide buttons
    document.querySelectorAll('.super-admin-only').forEach(el => {
        el.style.display = isSuperAdmin ? 'inline-flex' : 'none';
    });

  // Burger menu toggle for sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const burgerMenu = document.querySelector('.burger-menu');
        const sidebar = document.querySelector('.admin-sidebar');

        if (burgerMenu && sidebar) {
          burgerMenu.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });

      // Close sidebar when clicking outside on mobile
          document.addEventListener('click', function(e) {
            if (window.innerWidth <= 1024) {
              if (!sidebar.contains(e.target) && !burgerMenu.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
      }
  });

    // Prevent survey card collapsing by stopPropgation()
  document.querySelectorAll('.survey-id-section, .survey-version-select, survey-title-actions, .survey-action-btn')
  .forEach(el => el.addEventListener('click', e => e.stopPropagation()));
</script>

</body>
</html>
