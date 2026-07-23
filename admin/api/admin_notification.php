<?php if (isset($_SESSION['toast'])): ?>
  <div id="toast" 
       class="fixed top-20 right-5 z-50 px-4 py-3 rounded-lg shadow-lg hidden text-white transition transform duration-500 ease-in-out flex items-center space-x-2"
       style="opacity: 0; transform: translateY(-20px);">
    <span id="toastMessage"></span>
  </div>

  <script>
    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast');
      const messageBox = document.getElementById('toastMessage'); 

      const bgClasses = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
      };

      // Reset and apply classes
      toast.className = 'fixed top-20 right-5 z-50 px-4 py-3 rounded-lg shadow-lg text-white transition transform duration-500 ease-in-out flex items-center space-x-2';
      toast.classList.add(bgClasses[type] || 'bg-blue-500');

      messageBox.textContent = message;

      // Make it visible with animation
      toast.classList.remove('hidden');
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';

      // Hide after 3 seconds
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.classList.add('hidden'), 500);
      }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
      showToast("<?= $_SESSION['toast']['message'] ?>", "<?= $_SESSION['toast']['type'] ?>");
    });
  </script>

  <?php unset($_SESSION['toast']); ?>
<?php endif; ?>


