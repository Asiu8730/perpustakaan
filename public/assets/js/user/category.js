// assets/js/user/category.js
document.addEventListener('DOMContentLoaded', function(){
    const scrollLeft = document.getElementById('scrollLeft');
    const scrollRight = document.getElementById('scrollRight');
    const categoryScroll = document.getElementById('categoryScroll');

    if (!categoryScroll) return;

    scrollLeft.addEventListener('click', () => {
        categoryScroll.scrollBy({ left: -300, behavior: 'smooth' });
    });
    scrollRight.addEventListener('click', () => {
        categoryScroll.scrollBy({ left: 300, behavior: 'smooth' });
    });

    // disable/enable class when at ends (opsional)
    function updateArrows() {
        if (categoryScroll.scrollLeft <= 10) {
            scrollLeft.classList.add('disabled');
        } else {
            scrollLeft.classList.remove('disabled');
        }
        if (categoryScroll.scrollLeft + categoryScroll.clientWidth >= categoryScroll.scrollWidth - 10) {
            scrollRight.classList.add('disabled');
        } else {
            scrollRight.classList.remove('disabled');
        }
    }
    categoryScroll.addEventListener('scroll', updateArrows);
    updateArrows();
});
