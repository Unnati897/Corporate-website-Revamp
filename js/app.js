// js/main.js
document.getElementById('year').textContent = new Date().getFullYear();

const form = document.getElementById('contactForm');
const alertBox = document.getElementById('formAlert');

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const data = {
    name: document.getElementById('name').value.trim(),
    email: document.getElementById('email').value.trim(),
    phone: document.getElementById('phone').value.trim(),
    message: document.getElementById('message').value.trim()
  };

  // basic client-side validation
  if (!data.name || !data.email) {
    showAlert('Please provide name and email', 'danger');
    return;
  }

  try {
    const res = await fetch('/php/submit_contact.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const result = await res.json();
    if (result.success) {
      showAlert('Thanks! We received your message.', 'success');
      form.reset();
    } else {
      showAlert(result.message || 'Submission failed', 'danger');
    }
  } catch (err) {
    showAlert('Network error. Try again later.', 'danger');
    console.error(err);
  }
});

function showAlert(text, type='info') {
  alertBox.style.display = 'block';
  alertBox.className = `alert alert-${type}`;
  alertBox.textContent = text;
  setTimeout(()=>{ alertBox.style.display='none'; }, 5000);
}

