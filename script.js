// Load external components
function loadComponent(id, file) {
  fetch(file)
    .then(response => response.text())
    .then(data => {
      document.getElementById(id).innerHTML = data;

      if (file === "navbar.html") {
        const hamburger = document.getElementById("hamburger");
        const navLinks = document.getElementById("navLinks");
        const themeToggle = document.getElementById("themeToggle");
        const searchInput = document.getElementById("searchInput");

        // Hamburger toggle
        if (hamburger && navLinks) {
          hamburger.addEventListener("click", () => {
            navLinks.classList.toggle("active");
          });
        }

        // Dark mode toggle
        if (themeToggle) {
          themeToggle.addEventListener("click", () => {
            document.body.classList.toggle("dark");
            const mode = document.body.classList.contains("dark") ? "dark" : "light";
            localStorage.setItem("theme", mode);
            themeToggle.textContent = mode === "dark" ? "☀️" : "🌙";
          });

          const savedTheme = localStorage.getItem("theme");
          if (savedTheme === "dark") {
            document.body.classList.add("dark");
            themeToggle.textContent = "☀️";
          }
        }

        // Search filter
        if (searchInput) {
          searchInput.addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let links = navLinks.getElementsByTagName("a");
            for (let i = 0; i < links.length; i++) {
              let text = links[i].textContent.toLowerCase();
              links[i].style.display = text.includes(filter) ? "" : "none";
            }
          });
        }
      }
    })
    .catch(error => console.error("Error loading " + file, error));
}

document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("navbar")) loadComponent("navbar", "navbar.html");
  if (document.getElementById("footer")) loadComponent("footer", "footer.html");

  // Contact form validation and submission
  let contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const messageDiv = document.getElementById("contactMessage");

      try {
        const response = await fetch('process_contact.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        messageDiv.style.display = 'block';
        messageDiv.className = result.success ? 'success-message' : 'error-message';
        messageDiv.textContent = result.message;

        if (result.success) {
          this.reset();
        }
      } catch (error) {
        messageDiv.style.display = 'block';
        messageDiv.className = 'error-message';
        messageDiv.textContent = 'Sorry, there was an error sending your message. Please try again.';
      }
    });
  }

  // Give form validation
  let giveForm = document.getElementById("giveForm");
  if (giveForm) {
    giveForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const messageDiv = document.getElementById("donationMessage");

      try {
        const response = await fetch('process_donation.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        messageDiv.style.display = 'block';
        messageDiv.className = result.success ? 'success-message' : 'error-message';
        messageDiv.textContent = result.message;

        if (result.success) {
          this.reset();
        }
      } catch (error) {
        messageDiv.style.display = 'block';
        messageDiv.className = 'error-message';
        messageDiv.textContent = 'Sorry, there was an error processing your donation. Please try again.';
      }
    });
  }

  // Newsletter form
  const newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const messageDiv = document.getElementById("newsletterMessage");

      try {
        const response = await fetch('process_newsletter.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        messageDiv.style.display = 'block';
        messageDiv.className = result.success ? 'success-message' : 'error-message';
        messageDiv.textContent = result.message;

        if (result.success) {
          this.reset();
        }
      } catch (error) {
        messageDiv.style.display = 'block';
        messageDiv.className = 'error-message';
        messageDiv.textContent = 'Sorry, there was an error subscribing. Please try again.';
      }
    });
  }

  // Footer contact form
  const footerContactForm = document.getElementById('footerContactForm');
  if (footerContactForm) {
    footerContactForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const messageDiv = document.getElementById("footerContactMessage");

      try {
        const response = await fetch('process_contact.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        messageDiv.style.display = 'block';
        messageDiv.className = result.success ? 'success-message' : 'error-message';
        messageDiv.textContent = result.message;

        if (result.success) {
          this.reset();
        }
      } catch (error) {
        messageDiv.style.display = 'block';
        messageDiv.className = 'error-message';
        messageDiv.textContent = 'Sorry, there was an error sending your message. Please try again.';
      }
    });
  }

  // Initialize all modularized functions
  initializePage();
});

// Modularized function for lazy loading images
function enableLazyLoading() {
  const images = document.querySelectorAll("img");
  images.forEach(img => {
    img.setAttribute("loading", "lazy");
  });
}

// Modularized function for theme toggling
function setupThemeToggle() {
  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      document.body.classList.toggle("dark");
      const mode = document.body.classList.contains("dark") ? "dark" : "light";
      localStorage.setItem("theme", mode);
      themeToggle.textContent = mode === "dark" ? "☀️" : "🌙";
    });

    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
      document.body.classList.add("dark");
      themeToggle.textContent = "☀️";
    }
  }
}

// Email validation helper
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

function showGiving() {
  alert("Redirecting to secure giving page...");
}

// Social Media Sharing Functions
function shareOnFacebook() {
  const url = encodeURIComponent(window.location.href);
  const title = encodeURIComponent("Word Liberty Chapel International - Transforming lives through Christ");
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${title}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent("Join us at Word Liberty Chapel International - Transforming lives through Christ #WLCI");
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent("Join us at Word Liberty Chapel International - Transforming lives through Christ\n\n");
  window.open(`https://wa.me/?text=${text}${url}`, '_blank');
}

// Sermon Sharing Functions
function shareSermon(title, url) {
  const fullUrl = encodeURIComponent(url);
  const sermonTitle = encodeURIComponent(`${title} - Word Liberty Chapel International`);
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${fullUrl}&quote=${sermonTitle}`, '_blank', 'width=600,height=400');
}

function shareSermonTwitter(title, url) {
  const fullUrl = encodeURIComponent(url);
  const text = encodeURIComponent(`Watch "${title}" from Word Liberty Chapel International #WLCI #Sermon`);
  window.open(`https://twitter.com/intent/tweet?url=${fullUrl}&text=${text}`, '_blank', 'width=600,height=400');
}

function shareSermonWhatsApp(title, url) {
  const fullUrl = encodeURIComponent(url);
  const text = encodeURIComponent(`Watch "${title}" from Word Liberty Chapel International\n\n${url}`);
  window.open(`https://wa.me/?text=${text}`, '_blank');
}

// Event Sharing Functions
function shareEvent(title, description) {
  const url = encodeURIComponent(window.location.href);
  const eventTitle = encodeURIComponent(`${title} - Word Liberty Chapel International`);
  const desc = encodeURIComponent(description);
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${eventTitle}%20-%20${desc}`, '_blank', 'width=600,height=400');
}

function shareEventTwitter(title, description) {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent(`${title} at Word Liberty Chapel International! ${description} #WLCI #Event`);
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function shareEventWhatsApp(title, description) {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent(`${title} at Word Liberty Chapel International!\n\n${description}\n\n${url}`);
  window.open(`https://wa.me/?text=${text}`, '_blank');
}
