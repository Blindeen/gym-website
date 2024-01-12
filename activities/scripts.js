import {setSessionStorage} from "../utils.js";

const likeButtons = document.querySelectorAll('button');

let favoriteActivities = JSON.parse(sessionStorage.getItem('favoriteActivities')) ?? [];
Array.from(likeButtons).forEach(btn => {
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

            setSessionStorage('favoriteActivities', JSON.stringify(favoriteActivities));
        }
    });
});
