document.addEventListener("DOMContentLoaded", () => {
  // Initialize AOS Animation Library
  AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: true,
    mirror: false,
    offset: 100,
  });

  // Load Navbar and Footer Dynamically
  const loadHTML = async (url, placeholderId) => {
    try {
      const response = await fetch(url);
      if (!response.ok) throw new Error(`Failed to load ${url}`);
      const html = await response.text();
      document.getElementById(placeholderId).innerHTML = html;
    } catch (error) {
      console.error(error);
    }
  };

  Promise.all([
    loadHTML("nav.html", "nav-placeholder"),
    loadHTML("footer.html", "footer-placeholder"),
  ]).then(() => {
    // Update active link in navbar
    const currentPath =
      window.location.pathname.split("/").pop() || "index.html";
    const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
    navLinks.forEach((link) => {
      link.classList.remove("active");
      if (link.getAttribute("href") === currentPath) {
        link.classList.add("active");
      }
    });

    // Sticky Navbar Logic
    const navbar = document.querySelector(".navbar");
    if (navbar) {
      const handleScroll = () => {
        // Get all logo images except mobile logos
        const logoImages = document.querySelectorAll(
          ".navbar-logo:not(.mobile-logo)",
        );

        if (window.scrollY > 50) {
          navbar.classList.add("scrolled", "navbar-light");
          navbar.classList.remove("navbar-dark");
          // Change to regular logo when scrolled
          logoImages.forEach((img) => {
            img.src = "images/logo.png";
            img.classList.remove("logo-white");
          });
        } else {
          navbar.classList.remove("scrolled", "navbar-light");
          navbar.classList.add("navbar-dark");
          // Change to white logo when not scrolled
          logoImages.forEach((img) => {
            img.src = "images/logo_white.png";
            img.classList.add("logo-white");
          });
        }
      };
      window.addEventListener("scroll", handleScroll);
      handleScroll(); // Trigger once on load to set initial state
    }
  });

  // Scroll to Top Button
  const scrollTopBtn = document.querySelector(".scroll-top");

  if (scrollTopBtn) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollTopBtn.classList.add("active");
      } else {
        scrollTopBtn.classList.remove("active");
      }
    });

    scrollTopBtn.addEventListener("click", () => {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });
  }

  // Animated Counters
  const counters = document.querySelectorAll(".counter");
  const speed = 200; // The lower the slower

  const animateCounters = () => {
    counters.forEach((counter) => {
      const updateCount = () => {
        const target = +counter.getAttribute("data-target");
        const count = +counter.innerText;

        // Lower inc to slow and higher to fast
        const inc = target / speed;

        // Check if target is reached
        if (count < target) {
          // Add inc to count and output in counter
          counter.innerText = Math.ceil(count + inc);
          // Call function every ms
          setTimeout(updateCount, 20);
        } else {
          counter.innerText = target;
        }
      };

      // Only animate if element is in view (Intersection Observer)
      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
          updateCount();
          observer.disconnect();
        }
      });

      observer.observe(counter);
    });
  };

  if (counters.length > 0) {
    animateCounters();
  }
});
