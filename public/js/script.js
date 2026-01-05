// ANIMATION
document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".custom-anim");
    items.forEach((item, index) => {
        setTimeout(() => {
        item.classList.add("show");
        }, index * 150); // jeda antar item 150ms
    });
});

// COLORED BOX
$(document).ready(function() {
    const colors = [
        '#795548',
        '#694a9d',
        '#607D8B',
        '#FF9800',
        '#8BC34A',
        '#3F51B5',
        '#00BCD4'
    ];
    $('.zk-detail-content').each(function() {
        const randomColor = colors[Math.floor(Math.random() * colors.length)];
        $(this).css('background-color', randomColor);
    });
});

// TOMBOL BACK
$(document).ready(function() {
  $('.back-button').appendTo('body');
  $('.app-container .back-button').remove();
  $('.back-button').show();
});