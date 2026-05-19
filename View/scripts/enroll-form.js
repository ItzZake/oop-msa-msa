// Enrollment Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form');
  
  if (form) {
    form.addEventListener('submit', function(e) {
      // Basic validation can be added here
      console.log('Enrollment form submitted');
    });
  }
});
