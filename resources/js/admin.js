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

document.addEventListener('DOMContentLoaded', () => {

    const input = document.getElementById('buscar-entrada');

    if (!input) return;

    input.addEventListener('input', () => {

        const value = input.value.trim();

        document.querySelectorAll('tbody tr').forEach(row => {

            const id = row.dataset.entradaId;

            if (value === '' || id.includes(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

        });

    });

});

