const likeButtons = document.querySelectorAll('button');

Array.from(likeButtons).forEach(btn => {
    let favoriteActivities = JSON.parse(sessionStorage.getItem('favoriteActivities')) ?? [];
    const activityID = btn.getAttribute('data-id');
    const isAdded = favoriteActivities.some(obj => obj.id === activityID);
    if (isAdded) {
        btn.setAttribute('class', 'remove-button');
        btn.innerText = 'Dislike';
    }

    btn.addEventListener('click', () => {
        if (activityID) {
            const isAlreadyAdded = favoriteActivities.some(obj => obj.id === activityID);
            if (isAlreadyAdded) {
                favoriteActivities = favoriteActivities.filter(obj => obj.id !== activityID);
                btn.setAttribute('class', 'like-button');
                btn.innerText = 'Like';
            } else {
                favoriteActivities.push({
                    id: activityID,
                    name: btn.getAttribute('data-name'),
                });
                btn.setAttribute('class', 'remove-button');
                btn.innerText = 'Dislike';
            }

            sessionStorage.setItem('favoriteActivities', JSON.stringify(favoriteActivities));
        }
    });
});
