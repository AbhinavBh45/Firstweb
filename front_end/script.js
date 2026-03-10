// Add animation when "Book Now" is clicked
document.querySelectorAll('.btn-book').forEach(button => {
    button.addEventListener('click', () => {
        button.style.transform = 'scale(1.15)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 300);
    });
});
alert("Welcome to Cinebook")