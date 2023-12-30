const hamburgerMenu = () => {
    const openButton = document.querySelector('.hamburger-open-button');
    const closeButton = document.querySelector('.hamburger-close-button');
    const hamburgerMenu = document.querySelector('.mobile-menu');

    openButton.addEventListener('click', () => hamburgerMenu.style.display = 'flex');

    closeButton.addEventListener('click', () => hamburgerMenu.style.display = 'none');
};

const scrollToTop = () => {
    const scrollButton = document.querySelector('.scroll-button');

    if (window.scrollY > 0) {
        scrollButton.style.visibility = 'visible';
    }

    window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
            scrollButton.style.visibility = 'visible';
        } else {
            scrollButton.style.visibility = 'hidden';
        }
    });

    scrollButton.addEventListener('click', () => window.scrollTo({top: 0, behavior: 'smooth'}));
};

const slider = () => {
    const images = [{
        text: 'We\'re more than just a place to break a sweat. We\'re more than just a place to break a sweat. We\'re your fitness sanctuary and your second home, whether you\'re an athlete or just a beginner. We\'ve got something for everyone.',
        imgSrc: 'assets/img/white-woman-workout.png',
    }, {
        text: 'Revamp your fitness journey with CrossFit at our gym. Experience high-intensity, functional workouts that build strength, agility, and endurance. Join our supportive community of athletes and achieve your personal best with expert coaches guiding you every step of the way.',
        imgSrc: 'assets/img/man-workout.png',
    }, {
        text: 'Unlock your potential and build lean, strong muscles with our tailored fitness programs. Our expert trainers will help you sculpt the physique you desire through effective workouts and nutrition guidance. Elevate your strength and confidence.',
        imgSrc: 'assets/img/afroamerican-man-workout.png',
    },];

    const rightPane = document.querySelector('.right-pane');
    const img = rightPane.querySelector('img');

    const leftPane = document.querySelector('.left-pane');
    const paragraph = leftPane.querySelector('p');

    let i = 1;
    const timeout = 8000;

    setInterval(() => {
        const {text, imgSrc} = images[i];
        img.setAttribute('src', imgSrc);
        paragraph.textContent = text;
        i === images.length - 1 ? i = 0 : ++i;
    }, timeout);
};

addEventListener('DOMContentLoaded', () => {
    const badge = document.querySelectorAll(".badge");
    const favoriteActivities = JSON.parse(sessionStorage.getItem('favoriteActivities')) ?? [];

    const len = favoriteActivities.length;
    if (len > 0) {
        badge.forEach(el => {
            el.style.display = 'flex';
            el.innerText = favoriteActivities.length;
        });
    }
});

hamburgerMenu();
scrollToTop();
slider();
