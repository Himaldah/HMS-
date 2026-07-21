
<div id="toast" class="fixed top-20 right-5 z-50 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 hidden">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
  </svg>
  <span id="toastMessage">Success! Process completed. </span>
</div>


<script>
  function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');

    toastMessage.textContent = message;

    toast.classList.remove('bg-green-500', 'bg-red-500', 'bg-blue-500');
    if (type === 'success') toast.classList.add('bg-green-500');
    if (type === 'error') toast.classList.add('bg-red-500');
    if (type === 'info') toast.classList.add('bg-blue-500');

    toast.classList.remove('hidden');
    toast.classList.add('flex');

    setTimeout(() => {
      toast.classList.remove('flex');
      toast.classList.add('hidden');
    }, 5000); 
  }
</script>


<?php if (isset($_SESSION['toast-2'])): ?>
  <div id="toast-2" 
       class="fixed top-20 right-5 z-50 px-4 py-3 rounded-lg shadow-lg hidden text-white transition transform duration-500 ease-in-out flex items-center space-x-2"
       style="opacity: 0; transform: translateY(-20px);">
    <span id="toastMessage-2"></span>
  </div>

  <script>
    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast-2');
      const messageBox = document.getElementById('toastMessage-2');

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
        setTimeout(() => toast.classList.add('hidden'), 500); // Delay hiding to allow fade-out
      }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
      showToast("<?= $_SESSION['toast-2']['message'] ?>", "<?= $_SESSION['toast-2']['type'] ?>");
    });
  </script>

  <?php unset($_SESSION['toast-2']); ?>
<?php endif; ?>


