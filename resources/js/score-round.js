document.addEventListener('DOMContentLoaded', () => {
    const scoreBoard = document.querySelector('[data-score-board]');

    if (!scoreBoard) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    scoreBoard.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-score-adjust]');

        if (!button) {
            return;
        }

        const row = button.closest('[data-score-row]');
        const value = row.querySelector('[data-score-value]');
        const currentScore = Number.parseInt(value.textContent, 10);
        const adjustment = Number.parseInt(button.dataset.scoreAdjust, 10);
        const nextScore = Math.max(1, currentScore + adjustment);
        const error = row.querySelector('[data-score-error]');
        const targetPar = Number.parseInt(row.dataset.targetPar, 10);
        const actualPar = Number.parseInt(row.dataset.actualPar, 10);
        const targetComparison = row.querySelector('[data-score-vs-target]');
        const actualComparison = row.querySelector('[data-score-vs-actual]');

        if (nextScore === currentScore) {
            return;
        }

        value.textContent = nextScore;
        updateComparison(targetComparison, nextScore - targetPar);
        updateComparison(actualComparison, nextScore - actualPar);
        row.dataset.saving = 'true';
        error?.classList.add('hidden');

        try {
            const response = await fetch(row.dataset.scoreUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ strokes: nextScore }),
            });

            if (!response.ok) {
                throw new Error('Score update failed.');
            }
        } catch (error) {
            value.textContent = currentScore;
            updateComparison(targetComparison, currentScore - targetPar);
            updateComparison(actualComparison, currentScore - actualPar);
            row.dataset.saving = 'false';
            row.dataset.error = 'true';
            row.querySelector('[data-score-error]')?.classList.remove('hidden');
            return;
        }

        row.dataset.saving = 'false';
        row.dataset.error = 'false';
    });
});

function updateComparison(element, value) {
    if (!element) {
        return;
    }

    element.textContent = value > 0 ? `+${value}` : value;
}
