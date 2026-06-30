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

        playerRow.querySelectorAll('input').forEach((input) => {
            input.name = input.name.replace(/players\[\d+]/, `players[${nextIndex}]`);
            input.value = '';
            input.removeAttribute('autofocus');
        });

        playersContainer.insertBefore(playerRow, addPlayerButton.closest('.form-control'));
        playerRow.querySelector('input')?.focus();
    });
});
