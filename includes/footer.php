<!-- Footer -->
<footer class="bg-blue-900 text-white w-full mt-10">
  <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-8">

    <!-- About -->
    <div>
      <h3 class="text-lg font-semibold mb-3">About Us</h3>
      <p class="text-sm">
        HMS (Hospital Management System) is designed to provide easy access to medical appointments, doctor consultations, and digital reports—securely and reliably.
      </p>
    </div>

    <!-- Quick Links -->
    <div>
      <h3 class="text-lg font-semibold mb-3">Quick Links</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="index.php" class="hover:text-blue-200">Home</a></li>
        <li><a href="departments.php" class="hover:text-blue-200">Book Appointment</a></li>
        <li><a href="about.php" class="hover:text-blue-200">About</a></li>
        <li><a href="contact.php" class="hover:text-blue-200">Contact</a></li>
        <!-- <li><a href="faq.php" class="hover:text-blue-200">FAQ</a></li> -->
      </ul>
    </div>

    <!-- Services -->
    <div>
      <h3 class="text-lg font-semibold mb-3">Our Services</h3>
      <ul class="space-y-2 text-sm">
        <li>Online Appointment</li>
        <li>Health Reports</li>
        <li>24/7 Support</li>
        <li>Doctor Profiles</li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div>
      <h3 class="text-lg font-semibold mb-3">Contact</h3>
      <p class="text-sm">Email: contact.hms@gmail.com</p>
      <p class="text-sm">Phone: +977-9812345678</p>
      <div class="flex space-x-4 mt-2">
        <a href="#" class="hover:text-blue-200"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-blue-200"><i class="fab fa-twitter"></i></a>
        <a href="#" class="hover:text-blue-200"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>

    <div>
      <h3 class="text-lg font-semibold mb-3">Stay Updated</h3>
      <form action="ns_subscribe.php" method="POST" class="flex flex-col gap-2">
        <input type="email" name="nsemail" placeholder="Your Email" class="w-full p-2 rounded text-black" required>
        <button type="submit" class="bg-pink-500 hover:bg-pink-600 rounded py-2 px-3 transition text-sm">
          Subscribe
        </button>
      </form>
    </div>
  </div>

  <div class="text-center text-sm py-3">
    &copy; 2026 Hospital Management System. All rights reserved.
  </div>


</footer>
