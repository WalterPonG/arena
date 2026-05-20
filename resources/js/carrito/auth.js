export async function requireLogin() {
    const res = await fetch('/api/user', {
        headers: { 'Accept': 'application/json' }
    });

    if (res.ok) return true;

    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();

    return false;
}

/*export function requireLogin() {

    if (document.body.dataset.auth !== "1") {

        const modalEl = document.getElementById('loginModal');

        if (!modalEl) {
            alert('Debes iniciar sesión');
            return false;
        }

        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        return false;
    }

    return true;
}*/
