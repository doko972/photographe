import { showToast } from './toast.js';

export function initVote() {
    const form = document.getElementById('votes-form');
    if (!form) return;

    const selects = form.querySelectorAll('.rank-select');

    selects.forEach(sel => {
        sel.addEventListener('change', () => {
            enforceUniqueRanks(sel, selects);
            updateVotedState(sel);
        });
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const votes = [];
        selects.forEach(sel => {
            if (sel.value) {
                votes.push({ photo_id: sel.dataset.photoId, rank: parseInt(sel.value) });
            }
        });

        if (votes.length === 0) {
            showToast('⚔️ Attribuez au moins un rang avant de sauvegarder.');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const res = await fetch('/mes-favoris/voter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ votes }),
            });

            const data = await res.json();

            if (data.success) {
                showToast('🛡️ Vos votes ont été enregistrés !');
            } else {
                showToast('❌ Erreur lors de l\'enregistrement.');
            }
        } catch {
            showToast('❌ Une erreur est survenue.');
        }
    });
}

function enforceUniqueRanks(changed, allSelects) {
    const chosenRank = changed.value;
    if (!chosenRank) return;

    allSelects.forEach(sel => {
        if (sel !== changed && sel.value === chosenRank) {
            sel.value = '';
            updateVotedState(sel);
        }
    });
}

function updateVotedState(sel) {
    sel.classList.toggle('rank-assigned', !!sel.value);
    const card = sel.closest('.fav-card');
    if (card) card.classList.toggle('voted', !!sel.value);
}
