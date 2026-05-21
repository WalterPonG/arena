document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.btn-delete');
    if (!btn) return;

    const id = btn.dataset.id;

    if (!confirm('¿Eliminar esta entrada?')) return;

    try {
        const res = await fetch(`/admin/entradas/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Error al eliminar');

        btn.closest('tr').remove();

    } catch (err) {
        alert(err.message);
    }
});
