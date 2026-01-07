
document.addEventListener('click', (e) => {
  // Delete (soft archive)
  if (e.target.classList.contains('btn-delete')) {
    const id = e.target.dataset.id;
    fetch('event_delete.php', { method: 'POST', body: new URLSearchParams({ id }) })
      .then(r => r.json()).then(j => { if (j.ok) location.reload(); else alert(j.message || 'Error'); });
  }
  // Publish/Unpublish
  if (e.target.classList.contains('btn-publish')) {
    const id = e.target.dataset.id;
    const status = e.target.dataset.status;
    fetch('event_status.php', { method: 'POST', body: new URLSearchParams({ id, status }) })
      .then(r => r.json()).then(j => { if (j.ok) location.reload(); else alert(j.message || 'Error'); });
  }
});

// Form submit (Create/Update)
const form = document.getElementById('eventForm');
if (form) {
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const action = form.getAttribute('action');
    const data = new FormData(form);
    fetch(action, { method: 'POST', body: data })
      .then(r => r.json()).then(j => {
        if (j.ok) { alert(j.message || 'Success'); window.location.href = 'events_list.php'; }
        else if (j.errors) { alert(Object.values(j.errors).join('\n')); }
        else { alert(j.message || 'Error'); }
      });
  });
}
