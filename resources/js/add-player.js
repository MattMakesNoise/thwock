document.addEventListener('DOMContentLoaded', () => {
    const addPlayerButton = document.getElementById('add-player');
    const playersContainer = document.getElementById('players-container');

    if (!addPlayerButton || !playersContainer) {
        return;
    }

    addPlayerButton.addEventListener('click', () => {
        const playerRows = playersContainer.querySelectorAll('.player-row');
        const nextIndex = playerRows.length;
        const playerRow = playerRows[0].cloneNode(true);

        playerRow.dataset.index = nextIndex;

        playerRow.querySelectorAll('input, select').forEach((field) => {
            field.name = field.name.replace(/players\[\d+]/, `players[${nextIndex}]`);
            field.removeAttribute('autofocus');

            if (field instanceof HTMLInputElement) {
                field.value = field.type === 'number' ? '0' : '';
            }

            if (field instanceof HTMLSelectElement) {
                field.value = 'all_par_4';
            }
        });

        playersContainer.insertBefore(playerRow, addPlayerButton.closest('.form-control'));
        playerRow.querySelector('input')?.focus();
    });
});
